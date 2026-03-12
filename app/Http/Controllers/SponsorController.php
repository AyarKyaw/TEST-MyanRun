<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use App\Models\DinnerTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ZipArchive;
use Illuminate\Support\Facades\File;

class SponsorController extends Controller
{
    public function index($status)
    {
        $sponsors = Sponsor::latest()->get();

        return view('dashboard.sponsor.index', [
            'sponsors' => $sponsors,
            'status'   => $status 
        ]);
    }

    public function create()
    {
        return view('dashboard.sponsor.create');
    }

    public function store(Request $request) 
    {
        $request->validate([
            'company'      => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email'        => 'required|email',
            'phone'        => 'required',
            'quantity'     => 'required|integer|min:1',
            'code_prefix'  => 'nullable|string|max:10',
        ]);

        $sponsor = Sponsor::create([
            'company'      => $request->company,
            'contact_name' => $request->contact_name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'viber'        => $request->viber,
            'quantity'     => $request->quantity,
        ]);

        $prefix = $request->code_prefix ?? 'SPN';
        
        for ($i = 0; $i < $request->quantity; $i++) {
            $sponsor->codes()->create([
                'code'      => strtoupper($prefix . '-' . Str::random(5)),
                'max_uses'  => 1, 
                'used_count'=> 0,
            ]);
        }

        return redirect()->route('admin.sponsor.index', 'now')
                        ->with('success', 'Sponsor profile and ' . $request->quantity . ' codes generated successfully!');
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
        $sponsor = Sponsor::with(['sponsorCode', 'tickets.registration'])->findOrFail($id);

        $tickets = DinnerTicket::where('sponsor_id', $id)
                    ->with('registration')
                    ->latest()
                    ->get();

        return view('dashboard.sponsor.details', compact('sponsor', 'tickets'));
    }

    public function batchPrint($id)
    {
        // 1. Prevent timeout for large batches
        set_time_limit(0);

        try {
            $sponsor = Sponsor::findOrFail($id);
            $maxTickets = $sponsor->quantity ?? 1;

            $tempDir = public_path('uploads/temp');
            $templatePath = public_path('images/ticket.jpg'); 
            $fontPath = public_path('assets/fonts/arial.ttf');

            // Ensure directories exist
            if (!File::exists($templatePath)) return "ERROR: Template image not found.";
            if (!File::isDirectory($tempDir)) File::makeDirectory($tempDir, 0777, true, true);

            $zipFileName = 'Sponsor_Tickets_' . $sponsor->id . '_' . time() . '.zip';
            $zipPath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
                return "ERROR: Could not create ZIP file.";
            }

            for ($i = 1; $i <= $maxTickets; $i++) {
                // Create canvas from template
                $image = @imagecreatefromjpeg($templatePath);
                if (!$image) continue;

                $white = imagecolorallocate($image, 255, 255, 255);
                
                // Generate unique Ticket ID
                $batchTicketId = 'SPN-' . strtoupper(\Illuminate\Support\Str::random(6));

                // 2. Save to Database (Ensuring dinner_id is linked)
                $activeDinner = \App\Models\Dinner::where('is_active', 1)->first();
                
                \App\Models\DinnerTicket::create([
                    'sponsor_id'         => $sponsor->id,
                    'dinner_id'          => $activeDinner ? $activeDinner->id : null,
                    'dinner_register_id' => null, // Allowed if you ran the nullable migration
                    'ticket_no'          => $batchTicketId,
                    'type'               => 'Sponsored',
                    'status'             => 'confirmed',
                    'price'              => 0,
                    'quantity'           => 1,
                ]);

                if (File::exists($fontPath)) {
                    // 3. Add Text Info to Image
                    imagettftext($image, 18, 0, 980, 40, $white, $fontPath, $batchTicketId);
                    imagettftext($image, 22, 0, 1050, 90, $white, $fontPath, strtoupper($sponsor->company));
                    imagettftext($image, 16, 0, 950, 145, $white, $fontPath, $sponsor->contact_name);
                    imagettftext($image, 16, 0, 950, 200, $white, $fontPath, $sponsor->phone);
            
                    // 4. GENERATE QR CODE (External API Method like your DinnerController)
                    $checkInUrl = "https://test-myanrun.itplus.net.mm/ticket/verify/" . $batchTicketId;
                    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($checkInUrl);
                    
                    // Use the @ suppress to prevent crash if internet/VPN blips
                    $qrCodeImage = @imagecreatefrompng($qrUrl);

                    if ($qrCodeImage) {
                        // Merge QR onto Ticket
                        imagecopy($image, $qrCodeImage, 950, 230, 0, 0, 150, 150);
                        imagedestroy($qrCodeImage);
                    }
                }

                // 5. Capture Image Data for ZIP
                ob_start();
                imagepng($image);
                $imageData = ob_get_clean();
                imagedestroy($image);

                $zip->addFromString("Ticket_" . $batchTicketId . ".png", $imageData);
                
                // Tiny sleep to be nice to the QR API during large batches
                if ($maxTickets > 10) { usleep(100000); } // 0.1 second
            }
            
            $zip->close();

            if (File::exists($zipPath)) {
                if (ob_get_level()) ob_end_clean();
                return response()->download($zipPath)->deleteFileAfterSend(true);
            }

            return "ERROR: Zip file was not generated.";

        } catch (\Exception $e) {
            return "CRASH ERROR: " . $e->getMessage();
        }
    }
}