<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $status = 'coming')
    {
        $query = \App\Models\Event::query();

        // Map the URL string to your is_active integers
        if ($status === 'now') {
            $query->where('is_active', 1);
            $title = "Live Now";
        } elseif ($status === 'past') {
            $query->where('is_active', 0);
            $title = "Past Events";
        } else {
            // Default to 'coming'
            $query->where('is_active', 2);
            $title = "Upcoming Events";
        }

        $events = $query->orderBy('date', 'desc')->get();
        
        // Sidebar: Always show the 'Coming' (is_active = 2) events
        $sidebarEvents = \App\Models\Event::where('is_active', 2)->take(5)->get();
        $customers = \App\Models\User::all();

        return view('dashboard.events.event', compact('events', 'sidebarEvents', 'title', 'status', 'customers'));
    }
    // Show form to create event
    public function create()
    {
        return view('dashboard.events.event-create');
    }
    public function showPublicEvents()
    {
        $allEvents = \App\Models\Event::orderBy('date', 'asc')->get();

        $nowEvents = $allEvents->where('is_active', 1);
        $comingEvents = $allEvents->where('is_active', 2);
        $pastEvents = $allEvents->where('is_active', 0);

        // In your Controller
        $userTickets = [];
        if (auth()->check()) {
            $userTickets = auth()->user()->tickets()
                ->whereIn('status', ['pending', 'confirmed', 'approved']) // Ensure 'confirmed' is here!
                ->pluck('event') 
                ->map(fn($item) => trim($item)) // Remove any hidden spaces
                ->toArray();
        }

        return view('events', compact('nowEvents', 'comingEvents', 'pastEvents', 'userTickets'));
    }
    // Save the event
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'company'     => 'required',
            'date'        => 'required|date',
            'is_active'   => 'required|integer',
            'image'       => 'required|image',
            'location'    => 'nullable|string',
            'video_url'   => 'nullable|string',
            'description' => 'nullable|string',

            // ✅ validate ticket types
            'tickets.*.name' => 'required|string',
            'tickets.*.type' => 'required|in:solo,relay',
            'tickets.*.national_price' => 'required|numeric',
            'tickets.*.foreign_price' => 'required|numeric',
            'tickets.*.max_slots' => 'nullable|integer',
            'tickets.*.prefix' => 'nullable|string|max:10',
            'tickets.*.start_number' => 'nullable|integer|min:1',
        ]);

        // Create event
        $event = new \App\Models\Event();
        $event->name        = $request->name;
        $event->company     = $request->company;
        $event->date        = $request->date;
        $event->location    = $request->location;
        $event->video_url   = $request->video_url;
        $event->description = $request->description;
        $event->is_active   = $request->is_active;

        if ($request->hasFile('image')) {
            $event->image_path = $request->file('image')->store('events', 'public');
        }

        $event->save();

        // ✅ Save ticket types
        if ($request->has('tickets')) {
            foreach ($request->tickets as $ticket) {

                if (empty($ticket['name'])) continue;

                // Handle image upload
                $nationalImage = null;
                $foreignImage = null;

                if (isset($ticket['national_image']) && $ticket['national_image'] instanceof \Illuminate\Http\UploadedFile) {
                    $nationalImage = $ticket['national_image']->store('tickets', 'public');
                }

                if (isset($ticket['foreign_image']) && $ticket['foreign_image'] instanceof \Illuminate\Http\UploadedFile) {
                    $foreignImage = $ticket['foreign_image']->store('tickets', 'public');
                }

                $event->ticketTypes()->create([
                    'name' => $ticket['name'],
                    'type' => $ticket['type'],
                    'national_price' => $ticket['national_price'],
                    'foreign_price' => $ticket['foreign_price'],
                    'national_image' => $nationalImage,
                    'foreign_image' => $foreignImage,
                    'max_slots' => $ticket['max_slots'] ?? null,
                    'prefix'         => $ticket['prefix'],     
                    'start_number'   => $ticket['start_number']
                ]);
            }
        }

        $statusMap = [0 => 'past', 1 => 'now', 2 => 'coming'];

        return redirect()->route('events.index', $statusMap[$request->is_active]);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $event = \App\Models\Event::findOrFail($id);

        // Logic for status
        $status = 'past';
        if ($event->is_active == 1) $status = 'live';
        if ($event->is_active == 2) $status = 'coming';

        return view('events.detail', [
            'event'    => $event,
            'title'    => $event->name,
            'location' => $event->location ?? 'Myanmar', // Dynamic Location
            'video'    => $event->video_url ?? 'K_FvDL_anrs', // Default ID if empty
            'date'     => \Carbon\Carbon::parse($event->date)->format('d M Y'),
            'image'    => 'storage/' . $event->image_path,
            'status'   => $status,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $event = \App\Models\Event::findOrFail($id);
        return view('dashboard.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
{
    $event = \App\Models\Event::findOrFail($id);

    // 1. Validate - REMOVED 'price' because it's not in your form anymore
    $request->validate([
        'name'        => 'required|string|max:255',
        'date'        => 'nullable|date',
        'is_active'   => 'required',
        'location'    => 'nullable|string',
        'video_url'   => 'nullable|string',
        'description' => 'nullable|string',
        'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    // 2. Assign values from your form
    $event->name        = $request->name;
    $event->date        = $request->date;
    $event->is_active   = $request->is_active;
    $event->location    = $request->location;
    $event->video_url   = $request->video_url;
    $event->description = $request->description;

    // 3. Handle Image Upload
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('events', 'public');
        $event->image_path = $path;
    }

    $event->save();

    // 4. Redirect back to the list
    $statusMap = [0 => 'past', 1 => 'now', 2 => 'coming'];
    $statusKey = $statusMap[$request->is_active] ?? 'now';

    return redirect()->route('events.index', $statusKey)
                     ->with('success', 'Event updated successfully!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $event = \App\Models\Event::findOrFail($id);

        // 1. Delete the image file from the 'public' disk if it exists
        if ($event->image_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($event->image_path);
        }

        // 2. Delete the database record
        $event->delete();

        // 3. Redirect back with a success message
        return redirect()->back()->with('success', 'Event deleted successfully!');
    }
}
