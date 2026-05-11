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
        // Get all settings and convert to an object so $global_info->key works in Blade
        $settings = SiteSetting::pluck('value', 'key');
        $global_info = (object) $settings->all();
        
        return view('dashboard.settings.index', compact('global_info'));
    }

    public function update(Request $request)
    {
        // 1. Validate using the EXACT names from your HTML (global_email, global_address)
        $validated = $request->validate([
            'global_email'   => 'required|email',
            'global_address' => 'required|string|max:500',
            'global_phones'  => 'nullable|array',
            'global_phones.*'=> 'nullable|string|max:50',
        ]);

        // 2. Filter out empty phone strings
        $phones = array_filter($request->input('global_phones', []), function($value) {
            return !is_null($value) && $value !== '';
        });

        // 3. Map the HTML input names to the Database Keys
        $settingsToUpdate = [
            'email'          => $validated['global_email'],   // HTML global_email -> DB email
            'street_address' => $validated['global_address'], // HTML global_address -> DB street_address
            'phone_numbers'  => json_encode(array_values($phones)), 
        ];

        // 4. Loop and Sync to Database
        foreach ($settingsToUpdate as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // 5. Clear cache so frontend (Header/Footer) updates
        Cache::forget('site_settings');

        return redirect()->back()->with('success', 'Global Identity records synced successfully!');
    }
}