<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Story;

class StorySeeder extends Seeder
{
    public function run()
    {
        $stories = [
            "We run as ONE, everyone has FUN.",
            "Runderful Myanmar (RM) was Founded in 2020.",
            "Using a computer analogy, Myan RUN (MR) is not a separate entity or a subsidiary of RM. Rather, if RM functions as the CPU and Hard Disk, then MR represents the Random Access Memory and the Monitor.",
            "Our Vision",
            "Our Mission",
            "Our Objectives",
            "Our Motto",
            "Our Pathway",
            "Our Initiatives",
            "Our Run",
            "Our Future Plan"
        ];

        foreach ($stories as $index => $title) {
            Story::create([
                'company' => 'MyanRun',
                'title'   => $title,
                // Pointing to your existing folder path
                'image'   => 'images/home_banner/Our Stories (' . ($index + 1) . ').png',
            ]);
        }
    }
}