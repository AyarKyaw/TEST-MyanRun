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
        // 1. Prevent timeout for large batches
        set_time_limit(0);

        try {
            $sponsor = Sponsor::findOrFail($id);
            
            // Get the specific dinner this sponsor belongs to
            $dinner = Dinner::findOrFail($sponsor->dinner_id);
            $requestedQty = (int)$sponsor->quantity;

            // --- CAPACITY CHECK ---
            // Calculate how many sponsor seats are already taken (Confirmed or Pending)
            $sponsorSeatsTaken = DinnerTicket::where('dinner_id', $dinner->id)
                ->whereIn('status', ['confirmed', 'pending'])
                ->whereNotNull('sponsor_id')
                ->sum('quantity');

            if ($dinner->sponsor_capacity > 0) {
                $availableSponsorSeats = $dinner->sponsor_capacity - $sponsorSeatsTaken;
                
                if ($requestedQty > $availableSponsorSeats) {
                    return back()->with('error', "Cannot print. This dinner only has {$availableSponsorSeats} sponsor seats remaining, but this sponsor requires {$requestedQty} seats.");
                }
            }

            // --- PREPARE DIRECTORIES ---
            $tempDir = public_path('uploads/temp');
            $templatePath = public_path('images/ticket.jpg'); 
            $fontPath = public_path('assets/fonts/arial.ttf');

            if (!File::exists($templatePath)) return back()->with('error', "Template image not found at: " . $templatePath);
            if (!File::isDirectory($tempDir)) File::makeDirectory($tempDir, 0777, true, true);

            $zipFileName = Str::slug($sponsor->company) . '_' . $sponsor->phone . '.zip';
            $zipPath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
                return back()->with('error', "Could not create ZIP file.");
            }

            // --- GENERATION LOOP ---
            for ($i = 1; $i <= $requestedQty; $i++) {
                $image = @imagecreatefromjpeg($templatePath);
                if (!$image) continue;

                $white = imagecolorallocate($image, 255, 255, 255);
                $batchTicketId = 'SPN-' . strtoupper(Str::random(6));

                // 2. Save each ticket to DB linked to the specific dinner
                DinnerTicket::create([
                    'sponsor_id'         => $sponsor->id,
                    'dinner_id'          => $dinner->id,
                    'dinner_register_id' => null, 
                    'ticket_no'          => $batchTicketId,
                    'type'               => 'Sponsored',
                    'status'             => 'confirmed',
                    'price'              => 0,
                    'quantity'           => 1, // Sponsor tickets are usually individual
                ]);

                if (File::exists($fontPath)) {
                    // Draw Text
                    imagettftext($image, 18, 0, 980, 40, $white, $fontPath, $batchTicketId);
                    imagettftext($image, 22, 0, 1050, 90, $white, $fontPath, strtoupper($sponsor->company));
                    imagettftext($image, 16, 0, 950, 145, $white, $fontPath, $sponsor->contact_name);
                    imagettftext($image, 16, 0, 950, 200, $white, $fontPath, $sponsor->phone);
            
                    // Generate QR
                    $checkInUrl = route('dinner.verify', $batchTicketId); // Assuming route exists
                    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($checkInUrl);
                    
                    $qrCodeImage = @imagecreatefrompng($qrUrl);
                    if ($qrCodeImage) {
                        imagecopy($image, $qrCodeImage, 950, 230, 0, 0, 150, 150);
                        imagedestroy($qrCodeImage);
                    }
                }

                // Add to ZIP
                ob_start();
                imagepng($image);
                $imageData = ob_get_clean();
                imagedestroy($image);

                $zip->addFromString("Ticket_" . $batchTicketId . ".png", $imageData);
                
                // Anti-throttle for QR API
                if ($requestedQty > 15) { usleep(100000); } 
            }
            
            $zip->close();

            if (File::exists($zipPath)) {
                return response()->download($zipPath)->deleteFileAfterSend(true);
            }

            return back()->with('error', "Zip file was not generated.");

        } catch (\Exception $e) {
            return back()->with('error', "System Error: " . $e->getMessage());
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