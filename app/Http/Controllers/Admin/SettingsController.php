<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::pluck('value', 'key');
        $global_info = (object) $settings->all();
        
        return view('dashboard.settings.index', compact('global_info'));
    }

    public function update(Request $request)
    {
        // 1. Validation (Added social_platforms and social_urls)
        $validated = $request->validate([
            'global_email'      => 'required|email',
            'global_address'    => 'required|string|max:500',
            'global_phones'     => 'nullable|array',
            'global_phones.*'   => 'nullable|string|max:50',
            'social_platforms'  => 'nullable|array',
            'social_urls'       => 'nullable|array',
            'social_urls.*'     => 'nullable|url', // Ensures links are valid URLs
        ]);

        // 2. Filter Phone Numbers
        $phones = array_filter($request->input('global_phones', []), fn($v) => !empty($v));

        // 3. Process Social Media Links (Combine Platform + URL)
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
            'social_links'   => json_encode($socialLinks), // Save socials as JSON
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
}