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
            'race_name' => 'required|string|max:255|unique:races,name',
            'card_titles' => 'required|array',
            'card_titles.*' => 'required|string|max:255',
            'card_images' => 'required|array',
            'card_images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'race_name.unique' => 'A race with this name already exists. Please choose a different name.',
        ]);

        // 1. Create Race Tab
        $race = Race::create([
            'name' => $request->race_name,
            'is_active' => true
        ]);

        // 2. Upload and save multiple dynamic info cards
        if ($request->has('card_titles')) {
            foreach ($request->card_titles as $index => $title) {
                if ($request->hasFile("card_images.$index")) {
                    $file = $request->file("card_images.$index");
                    
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/races'), $filename);

                    RaceCard::create([
                        'race_id' => $race->id,
                        'title' => $title,
                        'image' => 'uploads/races/' . $filename,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Race and cards created successfully!');
    }

    // Update an existing race name and/or upload additional cards
    public function update(Request $request, $id)
    {
        $race = Race::findOrFail($id);

        // Validate incoming data (reads new_card_* keys from the edit form layout)
        $request->validate([
            'race_name' => 'required|string|max:255|unique:races,name,' . $id,
            'new_card_titles' => 'nullable|array',
            'new_card_titles.*' => 'required_with:new_card_images.*|string|max:255',
            'new_card_images' => 'nullable|array',
            'new_card_images.*' => 'required_with:new_card_titles.*|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'race_name.unique' => 'A race with this name already exists.',
        ]);

        // Update race name
        $race->update([
            'name' => $request->race_name
        ]);

        // Process and append additional newly uploaded cards
        if ($request->has('new_card_titles')) {
            foreach ($request->new_card_titles as $index => $title) {
                if ($request->hasFile("new_card_images.$index")) {
                    $file = $request->file("new_card_images.$index");

                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/races'), $filename);

                    RaceCard::create([
                        'race_id' => $race->id,
                        'title' => $title,
                        'image' => 'uploads/races/' . $filename,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Race updated successfully!');
    }

    // Delete a single card from a race safely
    public function destroyCard($id)
    {
        $card = RaceCard::findOrFail($id);

        // Delete the image file from public folder
        if ($card->image && file_exists(public_path($card->image))) {
            unlink(public_path($card->image));
        }

        $card->delete();

        return redirect()->back()->with('success', 'Card removed successfully!');
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
        
        foreach ($race->cards as $card) {
            if ($card->image && file_exists(public_path($card->image))) {
                unlink(public_path($card->image));
            }
        }
        
        $race->delete();
        return redirect()->back()->with('success', 'Race completely deleted!');
    }

    // Pull only active race categories and their uploaded image cards
    public function showPublicRaces()
    {
        $races = Race::where('is_active', true)->with('cards')->get();
        return view('race', compact('races'));
    }
}