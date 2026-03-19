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

        // Paths
        $tempDir = public_path('uploads/temp');
        $templatePath = public_path('images/ticket.jpg'); 
        $fontPath = public_path('assets/fonts/arial.ttf');

        if (!File::exists($templatePath)) return "ERROR: Template image not found.";
        if (!File::isDirectory($tempDir)) File::makeDirectory($tempDir, 0777, true, true);

        $zipFileName = Str::slug($sponsor->company) . $sponsor->viber . '.zip';
        $zipPath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
            return "ERROR: Could not create ZIP file.";
        }

        for ($i = 1; $i <= $maxTickets; $i++) {
            $image = @imagecreatefromjpeg($templatePath);
            if (!$image) continue;

            $white = imagecolorallocate($image, 255, 255, 255);
            
            // 1. Generate Security Data
            $uniqueCode = 'SPN-' . strtoupper(Str::random(8));
            $signatureHash = hash_hmac('sha256', $uniqueCode, config('app.key'));
            $shortSig = substr($signatureHash, 0, 10); // 10 chars to match publicVerify
            
            $securePayload = $uniqueCode . '-' . $shortSig;

            // 2. Create DinnerTicket (The Purchase)
            $ticket = DinnerTicket::create([
                'sponsor_id' => $sponsor->id,
                'dinner_id'  => $dinner->id,
                'ticket_no'  => $uniqueCode,
                'type'       => 'Sponsored',
                'status'     => 'confirmed',
                'price'      => 0,
                'quantity'   => 1,
            ]);

            // 3. Create SponsorCode (The Individual Seat for AppSheet)
            \App\Models\SponsorCode::create([
                'dinner_id'        => $dinner->id,
                'dinner_ticket_id' => $ticket->id,
                'sponsor_id'       => $sponsor->id,
                'code'             => $uniqueCode,
                'signature'        => $shortSig, // NOW SAVED FOR OFFLINE SCAN
                'used_by_name'     => $sponsor->company . " (Guest $i)",
                'max_uses'         => 1,
                'used_count'       => 0,
                'status'           => 'available'
            ]);

            if (File::exists($fontPath)) {
                // Add Text (Original Positions)
                imagettftext($image, 20, 0, 980, 45, $white, $fontPath, $uniqueCode);
                imagettftext($image, 22, 0, 1050, 90, $white, $fontPath, strtoupper($sponsor->company));
                imagettftext($image, 16, 0, 950, 145, $white, $fontPath, $sponsor->contact_name);
                imagettftext($image, 16, 0, 950, 200, $white, $fontPath, $sponsor->phone);

                // Add QR (150x150)
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($securePayload);
                $qrCodeRaw = @file_get_contents($qrUrl);
                if ($qrCodeRaw) {
                    $qrCodeImage = imagecreatefromstring($qrCodeRaw);
                    imagecopy($image, $qrCodeImage, 950, 230, 0, 0, 150, 150);
                    imagedestroy($qrCodeImage);
                }
            }

            ob_start();
            imagepng($image);
            $imageData = ob_get_clean();
            imagedestroy($image);
            $zip->addFromString("Ticket_" . $uniqueCode . ".png", $imageData);
        }

        $zip->close();
        return response()->download($zipPath)->deleteFileAfterSend(true);

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