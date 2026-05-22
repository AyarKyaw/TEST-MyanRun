<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Race;
use App\Models\RaceCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RaceController extends Controller
{
    // Display races in the admin panel
    public function index() {
        $races = Race::with('cards')->get();
        return view('dashboard.races.index', compact('races'));
    }

    // Save a new race with its custom dynamic cards
    public function store(Request $request)
    {
        $request->validate([
            'race_name' => 'required|string|max:255',
            'card_titles' => 'required|array',
            'card_titles.*' => 'required|string|max:255',
            'card_images' => 'required|array',
            'card_images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // 1. Create Race Tab
        $race = Race::create([
            'name' => $request->race_name,
            'is_active' => true
        ]);

        // 2. Upload and save multiple dynamic info cards
        if ($request->hasFile('card_images')) {
            foreach ($request->file('card_images') as $index => $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                // Store inside public storage folder
                $file->move(public_path('uploads/races'), $filename);

                RaceCard::create([
                    'race_id' => $race->id,
                    'title' => $request->card_titles[$index],
                    'image' => 'uploads/races/' . $filename,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Race and cards created successfully!');
    }

    // Toggle visibility status (Disable/Enable)
    public function toggleStatus($id)
    {
        $race = Race::findOrFail($id);
        $race->is_active = !$race->is_active;
        $race->save();

        return redirect()->back()->with('success', 'Race visibility status updated!');
    }

    // Delete Race completely
    public function destroy($id)
    {
        $race = Race::with('cards')->findOrFail($id);
        
        // Remove file fragments from filesystem storage
        foreach ($race->cards as $card) {
            if (file_exists(public_path($card->image))) {
                unlink(public_path($card->image));
            }
        }
        
        $race->delete();
        return redirect()->back()->with('success', 'Race completely deleted!');
    }

    // Add this at the bottom of your controller class file
    public function showPublicRaces()
    {
        // Pull only active race categories and their uploaded image cards
        $races = Race::where('is_active', true)->with('cards')->get();

        // Return the public-facing race blade view
        return view('race', compact('races'));
    }
}
