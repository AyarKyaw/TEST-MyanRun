<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use Illuminate\Support\Facades\File;

class StoryController extends Controller
{
    /**
     * Show the form for creating a new story.
     */
    public function create()
    {
        return view('dashboard.cms.story'); // Change this to your actual blade path
    }

    /**
     * Store a newly created story in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming data
        $request->validate([
            'company' => 'required|string|max:255',
            'title'   => 'required', // CKEditor content
            'image'   => 'required|image|mimes:jpeg,png,jpg,webp,avif|max:5120', // 5MB Max
        ]);

        try {
            $story = new Story();
            $story->company = $request->company;
            $story->title = $request->title;

            // 2. Handle the Image Upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Create a unique name: e.g., 170835421.webp
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                
                // Set the destination path: public/uploads/stories
                $destinationPath = public_path('uploads/stories');

                // Create folder if it doesn't exist
                if (!File::isDirectory($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }

                // Move the file to the folder
                $image->move($destinationPath, $imageName);
                
                // Save the path string to the database
                $story->image = 'uploads/stories/' . $imageName;
            }

            // 3. Save to Database
            $story->save();

            return redirect()->back()->with('success', 'Story created successfully!');

        } catch (\Exception $e) {
            // If something goes wrong, return with an error message
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Optional: Display all stories in the Content List
     */
    public function index()
    {
        $stories = Story::latest()->paginate(10); 
        return view('blog', compact('stories'));
    }
}