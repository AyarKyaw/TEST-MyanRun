<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\Event; // Import your Event model here
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::pluck('value', 'key');
        $global_info = (object) $settings->all();
        
        return view('dashboard.settings.index', compact('global_info'));
    }

    public function homeindex()
    {
        $settings = SiteSetting::pluck('value', 'key');
        
        // Safely decode home-banner array if it exists as a string in the DB
        $banners = isset($settings['home-banner']) ? json_decode($settings['home-banner'], true) : [];
        if (!is_array($banners)) {
            $banners = [];
        }

        // Fetch non-past events so the template sidebar loop functions correctly
        $activeEvents = Event::where('is_active', '!=', 0) // Fetches non-past items
                            ->orderBy('id', 'desc')
                            ->take(2) // Grabs the top 2 events
                            ->get();

        $global_info = (object) $settings->all();
        
        // Pass activeEvents down to the blade view matrix
        return view('dashboard.settings.home', compact('global_info', 'banners', 'activeEvents'));
    }

    public function update(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'global_email'      => 'required|email',
            'global_address'    => 'required|string|max:500',
            'global_phones'     => 'nullable|array',
            'global_phones.*'   => 'nullable|string|max:50',
            'social_platforms'  => 'nullable|array',
            'social_urls'       => 'nullable|array',
            'social_urls.*'     => 'nullable|url',
        ]);

        // 2. Filter Phone Numbers
        $phones = array_filter($request->input('global_phones', []), fn($v) => !empty($v));

        // 3. Process Social Media Links
        $socialLinks = [];
        $platforms = $request->input('social_platforms', []);
        $urls = $request->input('social_urls', []);

        foreach ($platforms as $index => $platform) {
            if (!empty($urls[$index])) {
                $socialLinks[] = [
                    'platform' => $platform,
                    'url'      => $urls[$index]
                ];
            }
        }

        // 4. Map to Database Keys
        $settingsToUpdate = [
            'email'          => $validated['global_email'],
            'street_address' => $validated['global_address'],
            'phone_numbers'  => json_encode(array_values($phones)),
            'social_links'   => json_encode($socialLinks),
        ];

        // 5. Sync to Database
        foreach ($settingsToUpdate as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Cache::forget('site_settings');

        return redirect()->back()->with('success', 'Global Identity records synced successfully!');
    }

    /**
     * Update Home Settings & Banners
     */
    /**
     * Update Home Settings & Banners
     */
    public function homeupdate(Request $request)
    {
        // 1. Validate incoming arrays and files
        $request->validate([
            'banner_images'             => 'nullable|array',
            'banner_images.*'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'existing_banner_images'    => 'nullable|array',
            'existing_banner_images.*'  => 'nullable|string',
        ]);

        // 2. Fetch current record from SiteSetting to manage server storage cleanup later
        $setting = SiteSetting::where('key', 'home-banner')->first();
        $currentBanners = $setting ? json_decode($setting->value, true) : [];
        if (!is_array($currentBanners)) {
            $currentBanners = [];
        }

        $finalBanners = [];
        
        // Grab files and existing text paths
        $uploadedFiles = $request->file('banner_images', []);
        $existingBannersInput = $request->input('existing_banner_images', []);

        // Find the maximum index between existing paths and new file uploads
        $maxIndex = max(count($existingBannersInput), count($uploadedFiles));

        // 3. Process all indexes sequentially up to the absolute highest index submitted
        for ($i = 0; $i < $maxIndex; $i++) {
            $path = $existingBannersInput[$i] ?? null;

            // Check if a new file upload exists for this specific index
            if ($request->hasFile("banner_images.{$i}")) {
                $file = $request->file("banner_images.{$i}");
                if ($file->isValid()) {
                    // Generate clean file name
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    
                    // Move file straight to public/images/home_banner directory
                    $file->move(public_path('images/home_banner'), $filename);
                    $newPath = 'images/home_banner/' . $filename;

                    // Clean up the old file replaced at this position if it exists
                    if (!empty($path)) {
                        $absoluteOldPath = public_path($path);
                        if (file_exists($absoluteOldPath)) {
                            @unlink($absoluteOldPath);
                        }
                    }

                    $path = $newPath;
                }
            }

            // Save row into final array if a path is present
            if (!empty($path)) {
                $finalBanners[] = [
                    'image_path' => $path
                ];
            }
        }

        // 4. Garbage Collection: Delete old files that were completely removed from the UI matrix
        $savedPaths = array_column($finalBanners, 'image_path');
        foreach ($currentBanners as $oldBanner) {
            $oldPath = is_array($oldBanner) ? ($oldBanner['image_path'] ?? '') : '';
            if (!empty($oldPath) && !in_array($oldPath, $savedPaths)) {
                $absolutePath = public_path($oldPath);
                if (file_exists($absolutePath)) {
                    @unlink($absolutePath);
                }
            }
        }

        // 5. Commit structured updates back to Database row entry
        SiteSetting::updateOrCreate(
            ['key' => 'home-banner'],
            ['value' => json_encode(array_values($finalBanners))]
        );

        Cache::forget('site_settings');

        return redirect()->back()->with('success', 'Home banner slide system modified successfully!');
    }
}