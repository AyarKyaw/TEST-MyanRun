<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\Event;
use Illuminate\Support\Facades\Cache;

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

        // NEW: Safely decode home-tickets array if it exists as a string in the DB
        $tickets = isset($settings['home-tickets']) ? json_decode($settings['home-tickets'], true) : [];
        if (!is_array($tickets)) {
            $tickets = [];
        }

        // Fetch non-past events so the template sidebar loop functions correctly
        $activeEvents = Event::where('is_active', '!=', 0)
                            ->orderBy('id', 'desc')
                            ->take(2)
                            ->get();

        $global_info = (object) $settings->all();
        
        // Pass activeEvents and tickets down to the blade view matrix
        return view('dashboard.settings.home', compact('global_info', 'banners', 'tickets', 'activeEvents'));
    }

    public function about()
    {
        $settings = SiteSetting::pluck('value', 'key')->all();
        
        // Safely decode about-metrics schema object array if it exists in the database
        $aboutSettings = isset($settings['about-metrics']) ? json_decode($settings['about-metrics'], true) : [];
        if (!is_array($aboutSettings)) {
            $aboutSettings = [];
        }

        return view('dashboard.settings.about', compact('aboutSettings'));
    }

    /**
     * Display standalone success stories interface
     */
    public function storiesIndex()
    {
        $settings = SiteSetting::pluck('value', 'key')->all();
        
        // Fetch and parse the dynamic array JSON string cleanly
        $stories = isset($settings['about-stories']) ? json_decode($settings['about-stories'], true) : [];
        if (!is_array($stories)) {
            $stories = [];
        }

        return view('dashboard.settings.story', compact('stories'));
    }

    /**
     * Update dynamic success stories array structures and handle asset cleanups
     */
    public function storiesUpdate(Request $request)
    {
        $request->validate([
            'story_titles'             => 'nullable|array',
            'story_titles.*'           => 'required_with:story_titles|string|max:255',
            'story_companies'          => 'nullable|array',
            'story_companies.*'        => 'required_with:story_companies|string|max:255',
            'existing_story_images'    => 'nullable|array',
            'existing_story_images.*'  => 'nullable|string',
            'story_images'             => 'nullable|array',
            'story_images.*'           => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:10240',
        ]);

        $storySetting = SiteSetting::where('key', 'about-stories')->first();
        $currentStories = $storySetting ? json_decode($storySetting->value, true) : [];
        if (!is_array($currentStories)) { $currentStories = []; }

        $finalStories = [];
        $titles = $request->input('story_titles', []);
        $companies = $request->input('story_companies', []);
        $existingImages = $request->input('existing_story_images', []);
        $uploadedFiles = $request->file('story_images', []);

        $maxIndex = max(count($existingImages), count($uploadedFiles), count($titles));

        for ($i = 0; $i < $maxIndex; $i++) {
            if (!isset($titles[$i])) {
                continue;
            }

            $imgPath = $existingImages[$i] ?? null;

            if ($request->hasFile("story_images.{$i}")) {
                $file = $request->file("story_images.{$i}");
                if ($file->isValid()) {
                    $filename = time() . '_story_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/stories'), $filename);
                    $newImgPath = 'images/stories/' . $filename;

                    // Garbage collection for single altered media file row
                    if (!empty($imgPath)) {
                        $absoluteOldPath = public_path($imgPath);
                        if (file_exists($absoluteOldPath)) { @unlink($absoluteOldPath); }
                    }
                    $imgPath = $newImgPath;
                }
            }

            $finalStories[] = [
                'title'      => $titles[$i],
                'company'    => $companies[$i],
                'image_path' => $imgPath ?? '',
            ];
        }

        // Global structural dynamic garbage collection: Delete assets of items dropped completely
        $savedImagePaths = array_filter(array_column($finalStories, 'image_path'));
        foreach ($currentStories as $oldStory) {
            $oldStoryPath = is_array($oldStory) ? ($oldStory['image_path'] ?? '') : '';
            if (!empty($oldStoryPath) && !in_array($oldStoryPath, $savedImagePaths)) {
                $absolutePath = public_path($oldStoryPath);
                if (file_exists($absolutePath)) { @unlink($absolutePath); }
            }
        }

        SiteSetting::updateOrCreate(
            ['key' => 'about-stories'],
            ['value' => json_encode(array_values($finalStories))]
        );

        Cache::forget('site_settings');

        return redirect()->back()->with('success', 'Success stories records modified and synced successfully!');
    }

    /**
     * Update fixed About Us Core Metrics (Awards, Followers, Events, Miles)
     */
    public function aboutupdate(Request $request)
    {
        // 1. Strict structural validation mapping for your fixed parameters
        $validated = $request->validate([
            'metrics' => 'required|array|size:4',
            
            'metrics.awards.value'    => 'required|string|max:50',
            'metrics.awards.title'    => 'required|string|max:255',
            
            'metrics.followers.value' => 'required|string|max:50',
            'metrics.followers.title' => 'required|string|max:255',
            
            'metrics.events.value'    => 'required|string|max:50',
            'metrics.events.title'    => 'required|string|max:255',
            
            'metrics.miles.value'     => 'required|string|max:50',
            'metrics.miles.title'     => 'required|string|max:255',
        ]);

        // 2. Persist metrics configurations payload down to database using target schema key
        SiteSetting::updateOrCreate(
            ['key' => 'about-metrics'],
            ['value' => json_encode($validated['metrics'])]
        );

        // 3. Clear system global configuration cache flags
        Cache::forget('site_settings');

        return redirect()->back()->with('success', 'About Us achievement metric parameters synchronized successfully!');
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
     * Update Home Settings, Banners & Tickets
     */
    public function homeupdate(Request $request)
    {
        // 1. Combined Validation for both Banners and Tickets structural payloads
        $request->validate([
            'banner_images'             => 'nullable|array',
            'banner_images.*'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'existing_banner_images'    => 'nullable|array',
            'existing_banner_images.*'  => 'nullable|string',

            // Ticket Fields Validation Matrix
            'ticket_images'             => 'nullable|array',
            'ticket_images.*'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'existing_ticket_images'    => 'nullable|array',
            'existing_ticket_images.*'  => 'nullable|string',
            'ticket_titles'             => 'nullable|array',
            'ticket_titles.*'           => 'required_with:ticket_titles|string|max:255',
            'ticket_locations'          => 'nullable|array',
            'ticket_locations.*'        => 'required_with:ticket_locations|string|max:255',
            'ticket_dates'              => 'nullable|array',
            'ticket_dates.*'            => 'required_with:ticket_dates|date',
            'ticket_times'              => 'nullable|array',
            'ticket_times.*'            => 'required_with:ticket_times|string',
            'ticket_prices'             => 'nullable|array',
            'ticket_prices.*'           => 'required_with:ticket_prices|string|max:100',
        ]);

        // ==========================================
        // PROCESS BLOCK A: HERO BANNER REPEATER
        // ==========================================
        $bannerSetting = SiteSetting::where('key', 'home-banner')->first();
        $currentBanners = $bannerSetting ? json_decode($bannerSetting->value, true) : [];
        if (!is_array($currentBanners)) { $currentBanners = []; }

        $finalBanners = [];
        $uploadedBannerFiles = $request->file('banner_images', []);
        $existingBannersInput = $request->input('existing_banner_images', []);
        $maxBannerIndex = max(count($existingBannersInput), count($uploadedBannerFiles));

        for ($i = 0; $i < $maxBannerIndex; $i++) {
            $path = $existingBannersInput[$i] ?? null;

            if ($request->hasFile("banner_images.{$i}")) {
                $file = $request->file("banner_images.{$i}");
                if ($file->isValid()) {
                    $filename = time() . '_banner_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/home_banner'), $filename);
                    $newPath = 'images/home_banner/' . $filename;

                    if (!empty($path)) {
                        $absoluteOldPath = public_path($path);
                        if (file_exists($absoluteOldPath)) { @unlink($absoluteOldPath); }
                    }
                    $path = $newPath;
                }
            }

            if (!empty($path)) {
                $finalBanners[] = ['image_path' => $path];
            }
        }

        // Garbage collection for removed banners
        $savedBannerPaths = array_column($finalBanners, 'image_path');
        foreach ($currentBanners as $oldBanner) {
            $oldPath = is_array($oldBanner) ? ($oldBanner['image_path'] ?? '') : '';
            if (!empty($oldPath) && !in_array($oldPath, $savedBannerPaths)) {
                $absolutePath = public_path($oldPath);
                if (file_exists($absolutePath)) { @unlink($absolutePath); }
            }
        }

        SiteSetting::updateOrCreate(
            ['key' => 'home-banner'],
            ['value' => json_encode(array_values($finalBanners))]
        );


        // ==========================================
        // PROCESS BLOCK B: EVENT TICKETS REPEATER
        // ==========================================
        $ticketSetting = SiteSetting::where('key', 'home-tickets')->first();
        $currentTickets = $ticketSetting ? json_decode($ticketSetting->value, true) : [];
        if (!is_array($currentTickets)) { $currentTickets = []; }

        $finalTickets = [];
        $uploadedTicketFiles = $request->file('ticket_images', []);
        $existingTicketsInput = $request->input('existing_ticket_images', []);
        
        $titles    = $request->input('ticket_titles', []);
        $locations = $request->input('ticket_locations', []);
        $dates     = $request->input('ticket_dates', []);
        $times     = $request->input('ticket_times', []);
        $prices    = $request->input('ticket_prices', []);

        // The exact total submission rows is evaluated dynamically by counting titles sent from UI layout
        $maxTicketIndex = max(count($existingTicketsInput), count($uploadedTicketFiles), count($titles));

        for ($i = 0; $i < $maxTicketIndex; $i++) {
            // If the title row structural map component layout was intentionally omitted/deleted, bypass saving it
            if (!isset($titles[$i])) {
                continue;
            }

            $imgPath = $existingTicketsInput[$i] ?? null;

            // Handle unique image upload mapping mechanics for each row node index
            if ($request->hasFile("ticket_images.{$i}")) {
                $file = $request->file("ticket_images.{$i}");
                if ($file->isValid()) {
                    $filename = time() . '_ticket_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/tickets'), $filename);
                    $newImgPath = 'images/tickets/' . $filename;

                    // Erase obsolete configuration media asset file cleanly from public directory
                    if (!empty($imgPath)) {
                        $absoluteOldTicketPath = public_path($imgPath);
                        if (file_exists($absoluteOldTicketPath)) { @unlink($absoluteOldTicketPath); }
                    }
                    $imgPath = $newImgPath;
                }
            }

            // Bind structured context to target storage matrix
            $finalTickets[] = [
                'image_path' => $imgPath ?? '',
                'title'      => $titles[$i] ?? '',
                'location'   => $locations[$i] ?? '',
                'date'       => $dates[$i] ?? '',
                'time'       => $times[$i] ?? '',
                'price'      => $prices[$i] ?? '',
            ];
        }

        // Garbage collection for completely deleted ticket configurations
        $savedTicketPaths = array_filter(array_column($finalTickets, 'image_path'));
        foreach ($currentTickets as $oldTicket) {
            $oldTicketPath = is_array($oldTicket) ? ($oldTicket['image_path'] ?? '') : '';
            if (!empty($oldTicketPath) && !in_array($oldTicketPath, $savedTicketPaths)) {
                $absoluteTicketPath = public_path($oldTicketPath);
                if (file_exists($absoluteTicketPath)) { @unlink($absoluteTicketPath); }
            }
        }

        // Save Ticket parameters serialized via json configuration schema
        SiteSetting::updateOrCreate(
            ['key' => 'home-tickets'],
            ['value' => json_encode(array_values($finalTickets))]
        );

        // Clear dynamic system cache dependencies
        Cache::forget('site_settings');

        return redirect()->back()->with('success', 'Homepage layout configurations and tickets system modified successfully!');
    }
}