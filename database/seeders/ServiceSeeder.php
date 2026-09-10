<?php

namespace Database\Seeders;

use App\Models\Sample;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * The six lines of work, each with placeholder sample slots. Real footage
     * drops into these rows through the studio; nothing here invents a client.
     */
    private const SERVICES = [
        [
            'name' => 'Articulate',
            'tagline' => 'Storyline and Rise builds that people finish',
            'description' => 'Interactive courses authored in Articulate Storyline and Rise, from scripting and screen design through to SCORM packaging and LMS handover.',
            'accent' => ['#15D9A1', '#00615C'],
            'samples' => ['Compliance module walkthrough', 'Onboarding course', 'Assessment interaction'],
        ],
        [
            'name' => '2D Animation',
            'tagline' => 'Drawn, rigged and timed by hand',
            'description' => 'Character and vector animation for explainers, campaign films and course openers, built frame by frame rather than from templates.',
            'accent' => ['#69FFF7', '#008680'],
            'samples' => ['Concept explainer', 'Character short', 'Campaign opener'],
        ],
        [
            'name' => '3D Animation',
            'tagline' => 'Modelled, lit and rendered',
            'description' => 'Product and process animation in three dimensions, for the ideas that only make sense when you can turn them around and look inside.',
            'accent' => ['#C1FAFB', '#00615C'],
            'samples' => ['Product teardown', 'Process visualisation', 'Environment flythrough'],
        ],
        [
            'name' => 'AI Videos',
            'tagline' => 'Synthetic presenters, produced properly',
            'description' => 'AI-assisted presenters and voice for content that needs to ship at volume, scripted and directed with the same care as a live shoot.',
            'accent' => ['#F2AA84', '#A31009'],
            'samples' => ['Presenter-led module', 'Multilingual variant', 'Rapid update cut'],
        ],
        [
            'name' => 'Storyboarding',
            'tagline' => 'The film, before the film',
            'description' => 'Boards and animatics that settle pacing, framing and intent before a single frame is produced, so production time is spent building rather than deciding.',
            'accent' => ['#F8847E', '#800D07'],
            'samples' => ['Brand film boards', 'Animatic pass', 'Shot breakdown'],
        ],
        [
            'name' => 'Motion Graphics',
            'tagline' => 'Type, data and identity in motion',
            'description' => 'Titles, lower thirds, data visualisation and brand systems in motion, for films that need to look like they belong to someone.',
            'accent' => ['#FEF1DE', '#A31009'],
            'samples' => ['Title sequence', 'Data explainer', 'Brand toolkit'],
        ],
    ];

    public function run(): void
    {
        foreach (self::SERVICES as $order => $definition) {
            $service = Service::updateOrCreate(
                ['slug' => Str::slug($definition['name'])],
                [
                    'name' => $definition['name'],
                    'tagline' => $definition['tagline'],
                    'description' => $definition['description'],
                    'accent_from' => $definition['accent'][0],
                    'accent_to' => $definition['accent'][1],
                    'sort_order' => $order,
                    'is_published' => true,
                ]
            );

            foreach ($definition['samples'] as $index => $title) {
                Sample::updateOrCreate(
                    ['service_id' => $service->id, 'title' => $title],
                    ['sort_order' => $index]
                );
            }
        }
    }
}
