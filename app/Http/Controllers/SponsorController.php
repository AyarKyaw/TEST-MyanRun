<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use App\Models\DinnerTicket;
use App\Models\Dinner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class SponsorController extends Controller
{
    /**
     * Display list of sponsors.
     */
    public function index($status)
    {
        $sponsors = Sponsor::with('dinner')->latest()->get();

        return view('dashboard.sponsor.index', [
            'sponsors' => $sponsors,
            'status'   => $status 
        ]);
    }

    /**
     * Show create form with dinner selection.
     */
    public function create()
    {
        $dinners = Dinner::where('is_active', 1)->get();
        return view('dashboard.sponsor.create', compact('dinners'));
    }

    /**
     * Store sponsor and link to a specific dinner.
     */
    public function store(Request $request) 
    {
        $request->validate([
            'dinner_id'    => 'required|exists:dinners,id',
            'company'      => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email'        => 'required|email',
            'phone'        => 'required',
            'quantity'     => 'required|integer|min:1',
            'code_prefix'  => 'nullable|string|max:10',
        ]);

        $sponsor = Sponsor::create([
            'dinner_id'    => $request->dinner_id,
            'company'      => $request->company,
            'contact_name' => $request->contact_name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'viber'        => $request->viber,
            'quantity'     => $request->quantity,
        ]);

        return redirect()->route('admin.sponsor.index', 'now')
                        ->with('success', 'Sponsor profile and ' . $request->quantity . ' codes generated successfully!');
    }

    /**
     * Batch Print Tickets with Capacity Enforcement.
     */
    
    public function batchPrint($id)
{
    set_time_limit(0);
    ini_set('memory_limit', '512M');

    try {
        $sponsor = Sponsor::findOrFail($id);
        $dinner = Dinner::findOrFail($sponsor->dinner_id);
        $maxTickets = (int)($sponsor->quantity ?? 1);

        // 1. Capacity Check
        $sponsorSeatsTaken = DinnerTicket::where('dinner_id', $dinner->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->whereNotNull('sponsor_id')
            ->sum('quantity');

        if ($dinner->sponsor_capacity > 0) {
            $availableSponsorSeats = $dinner->sponsor_capacity - $sponsorSeatsTaken;
            if ($maxTickets > $availableSponsorSeats) {
                return back()->with('error', "Cannot print. Only {$availableSponsorSeats} sponsor seats remaining.");
            }
        }

        // 2. Setup Paths
        $tempDir = public_path('uploads/temp');
        $templatePath = public_path('images/ticket.jpg'); 
        $fontPath = public_path('assets/fonts/arial.ttf');

        if (!File::exists($templatePath)) return "ERROR: Template image not found.";
        if (!File::isDirectory($tempDir)) File::makeDirectory($tempDir, 0777, true, true);

        $zipFileName = Str::slug($sponsor->company) . '_tickets_' . time() . '.zip';
        $zipPath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
            return "ERROR: Could not create ZIP file.";
        }

        // 3. Generation Loop
        for ($i = 1; $i <= $maxTickets; $i++) {
            $image = @imagecreatefromjpeg($templatePath);
            if (!$image) continue;

            $white = imagecolorallocate($image, 255, 255, 255);
            
            // Generate a unique code for THIS specific ticket/seat
            $uniqueSeatCode = 'SPN-' . strtoupper(Str::random(8));

            // A. Create the Main Ticket Record (The group/purchase)
            $ticket = DinnerTicket::create([
                'sponsor_id'         => $sponsor->id,
                'dinner_id'          => $dinner->id,
                'ticket_no'          => $uniqueSeatCode,
                'type'               => 'Sponsored',
                'status'             => 'confirmed',
                'price'              => 0,
                'quantity'           => 1,
            ]);

            // // B. Create the Scan Code Record (The individual entry)
            // // This is what the publicVerify function actually checks
            // \App\Models\SponsorCode::create([
            //     'dinner_id'          => $dinner->id,
            //     'dinner_ticket_id'   => $ticket->id,
            //     'sponsor_id'         => $sponsor->id,
            //     'used_by_name'       => $sponsor->company . " (Guest $i)",
            //     'code'               => $uniqueSeatCode,
            //     'max_uses'           => 1,
            //     'used_count'         => 0,
            //     'status'             => 'available' // Important: start as available
            // ]);

            if (File::exists($fontPath)) {
                // Add Text
                imagettftext($image, 20, 0, 980, 45, $white, $fontPath, $uniqueSeatCode);
                imagettftext($image, 22, 0, 1050, 90, $white, $fontPath, strtoupper($sponsor->company));
                imagettftext($image, 16, 0, 950, 145, $white, $fontPath, $sponsor->contact_name);
                imagettftext($image, 16, 0, 950, 200, $white, $fontPath, $sponsor->phone);

                // C. Link QR to the Verification URL
                $verifyUrl = "https://test-myanrun.itplus.net.mm/ticket/verify/" . $uniqueSeatCode;
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($verifyUrl);
                
                $qrCodeImage = @imagecreatefrompng($qrUrl);
                if ($qrCodeImage) {
                    imagecopy($image, $qrCodeImage, 950, 230, 0, 0, 150, 150);
                    imagedestroy($qrCodeImage);
                }
            }

            // D. Add to ZIP
            ob_start();
            imagepng($image);
            $imageData = ob_get_clean();
            imagedestroy($image);

            $zip->addFromString("Ticket_" . $uniqueSeatCode . ".png", $imageData);
        }

        $zip->close();

        if (File::exists($zipPath)) {
            while (ob_get_level()) { ob_end_clean(); }
            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        return "ERROR: Zip file was not generated.";

    } catch (\Exception $e) {
        return "CRASH ERROR: " . $e->getMessage();
    }
}

    public function toggleStatus($id)
    {
        $sponsor = Sponsor::findOrFail($id);
        $sponsor->status = ($sponsor->status === 'Paid') ? 'Pending' : 'Paid';
        $sponsor->save();

        return back()->with('success', 'Status updated to ' . $sponsor->status);
    }

    public function show($id)
    {
        $sponsor = Sponsor::with(['codes', 'dinner'])->findOrFail($id);
        $tickets = DinnerTicket::where('sponsor_id', $id)
                    ->with('registration')
                    ->latest()
                    ->get();

        return view('dashboard.sponsor.details', compact('sponsor', 'tickets'));
    }
}