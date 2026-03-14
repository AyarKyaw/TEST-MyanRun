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

        // Generate invitation codes based on quantity
        $prefix = $request->code_prefix ?? 'SPN';
        for ($i = 0; $i < $request->quantity; $i++) {
            $sponsor->codes()->create([
                'code'       => strtoupper($prefix . '-' . Str::random(5)),
                'max_uses'   => 1, 
                'used_count' => 0,
            ]);
        }

        return redirect()->route('admin.sponsor.index', 'now')
                        ->with('success', 'Sponsor profile and ' . $request->quantity . ' codes generated successfully!');
    }

    /**
     * Batch Print Tickets with Capacity Enforcement.
     */
    
    public function batchPrint($id)
{
    // 1. Prevent timeout and clean buffer immediately
    set_time_limit(0);
    ini_set('memory_limit', '512M');

    try {
        $sponsor = Sponsor::findOrFail($id);
        $dinner = Dinner::findOrFail($sponsor->dinner_id);
        $maxTickets = (int)($sponsor->quantity ?? 1);

        // --- NEWER LOGIC: CAPACITY ENFORCEMENT ---
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

        // --- YOUR PREFERRED STRUCTURE: DIRECTORIES & ZIP ---
        $tempDir = public_path('uploads/temp');
        $templatePath = public_path('images/ticket.jpg'); 
        $fontPath = public_path('assets/fonts/arial.ttf');

        if (!File::exists($templatePath)) return "ERROR: Template image not found.";
        if (!File::isDirectory($tempDir)) File::makeDirectory($tempDir, 0777, true, true);

        $zipFileName = $sponsor->company . '_' . $sponsor->phone . '.zip';
        $zipPath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
            return "ERROR: Could not create ZIP file.";
        }

        // --- THE GENERATION LOOP ---
        for ($i = 1; $i <= $maxTickets; $i++) {
            $image = @imagecreatefromjpeg($templatePath);
            if (!$image) continue;

            $white = imagecolorallocate($image, 255, 255, 255);
            $batchTicketId = 'SPN-' . strtoupper(\Illuminate\Support\Str::random(6));

            // Save to DB (Linking to the sponsor's dinner)
            DinnerTicket::create([
                'sponsor_id'         => $sponsor->id,
                'dinner_id'          => $dinner->id,
                'dinner_register_id' => null, 
                'ticket_no'          => $batchTicketId,
                'type'               => 'Sponsored',
                'status'             => 'confirmed',
                'price'              => 0,
                'quantity'           => 1,
            ]);

            if (File::exists($fontPath)) {
                // Add Text (Your Coordinates)
                imagettftext($image, 18, 0, 980, 40, $white, $fontPath, $batchTicketId);
                imagettftext($image, 22, 0, 1050, 90, $white, $fontPath, strtoupper($sponsor->company));
                imagettftext($image, 16, 0, 950, 145, $white, $fontPath, $sponsor->contact_name);
                imagettftext($image, 16, 0, 950, 200, $white, $fontPath, $sponsor->phone);

                // Generate QR (External API)
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($batchTicketId);
                $qrCodeImage = @imagecreatefrompng($qrUrl);

                if ($qrCodeImage) {
                    imagecopy($image, $qrCodeImage, 950, 230, 0, 0, 150, 150);
                    imagedestroy($qrCodeImage);
                }
            }

            // Capture Data for ZIP
            ob_start();
            imagepng($image);
            $imageData = ob_get_clean();
            imagedestroy($image);

            $zip->addFromString("Ticket_" . $batchTicketId . ".png", $imageData);

            if ($maxTickets > 10) { usleep(100000); } 
        }

        $zip->close();

        // --- CRITICAL DOWNLOAD FIX ---
        if (File::exists($zipPath)) {
            // This clears any hidden errors/spaces that block downloads
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