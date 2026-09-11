<?php

namespace Database\Seeders;

use App\Models\Sample;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * The seven lines of work, each with placeholder sample slots. A sample is a
     * title, or a [category, title] pair when the service splits its work under
     * sub-headings. Real footage drops into these rows through the studio;
     * nothing here invents a client.
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
            'name' => 'Infographics',
            'tagline' => 'Complex ideas, made legible at a glance',
            'description' => 'Static and animated infographics that turn data, processes and policy into visuals people actually read, designed for slides, print, social and course pages alike.',
            'accent' => ['#FFD166', '#B4530A'],
            'samples' => ['Annual report spread', 'Process poster', 'Data story carousel'],
        ],
        [
            'name' => 'Animation',
            'tagline' => 'Drawn by hand, modelled in depth',
            'description' => 'Character and vector animation for explainers, campaign films and course openers, alongside product and process animation in three dimensions for the ideas that only make sense when you can turn them around and look inside.',
            'accent' => ['#69FFF7', '#008680'],
            'samples' => [
                ['2D Animation', 'Concept explainer'],
                ['2D Animation', 'Character short'],
                ['2D Animation', 'Campaign opener'],
                ['3D Animation', 'Product teardown'],
                ['3D Animation', 'Process visualisation'],
                ['3D Animation', 'Environment flythrough'],
            ],
        ],
        [
            'name' => 'AI Videos',
            'tagline' => 'Synthetic presenters, produced properly',
            'description' => 'AI-assisted presenters and voice for content that needs to ship at volume, scripted and directed with the same care as a live shoot.',
            'accent' => ['#F2AA84', '#A31009'],
            'samples' => ['Presenter-led module', 'Multilingual variant', 'Rapid update cut'],
        ],
        [
            'name' => 'Smart Board',
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
        [
            'name' => 'Swayam',
            'tagline' => 'MOOC lectures, produced to broadcast standard',
            'description' => 'End-to-end production for SWAYAM and MOOC courses: studio-recorded lectures, multi-camera sessions and screen capture, edited, captioned and packaged to platform specification.',
            'accent' => ['#C1FAFB', '#00615C'],
            'samples' => ['Studio lecture capture', 'Course trailer', 'Multi-camera session'],
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

            foreach ($definition['samples'] as $index => $sample) {
                [$category, $title] = is_array($sample) ? $sample : [null, $sample];

                Sample::updateOrCreate(
                    ['service_id' => $service->id, 'title' => $title],
                    ['category' => $category, 'sort_order' => $index]
                );
            }
        }
    }
}
