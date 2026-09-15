<?php

namespace Database\Seeders;

use App\Models\Sample;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * The seven lines of work, each with its sample slots (real film titles
     * where the client has supplied footage). A sample is a
     * title, or a [category, title] pair when the service splits its work under
     * sub-headings. Real footage drops into these rows through the studio;
     * nothing here invents a client.
     */
    private const SERVICES = [
        [
            'name' => 'Infographics',
            'tagline' => 'Complex ideas, made legible at a glance',
            'description' => 'Static and animated infographics that turn data, processes and policy into visuals people actually read, designed for slides, print, social and course pages alike.',
            'accent' => ['#FFD166', '#B4530A'],
            'samples' => ['Advance Settings', 'Vision: The Golden Circle', 'BIT WR 1.1.1.1', 'Creating an Effective IMC Plan'],
        ],
        [
            'name' => 'Animation',
            'tagline' => 'Drawn by hand, modelled in depth',
            'description' => 'Character and vector animation for explainers, campaign films and course openers, alongside product and process animation in three dimensions for the ideas that only make sense when you can turn them around and look inside.',
            'accent' => ['#69FFF7', '#008680'],
            'samples' => [
                ['2D Animation', 'Code of Conduct'],
                ['2D Animation', 'IEEE'],
                ['2D Animation', 'NBM 2024 Presentation Deck'],
                ['2D Animation', 'QR Charging'],
                ['3D Animation', 'Blood Vessel'],
                ['3D Animation', 'RedFx'],
            ],
        ],
        [
            'name' => 'AI Videos',
            'tagline' => 'Synthetic presenters, produced properly',
            'description' => 'AI-assisted presenters and voice for content that needs to ship at volume, scripted and directed with the same care as a live shoot.',
            'accent' => ['#F2AA84', '#A31009'],
            'samples' => ['AI Based Learning Sample', 'Emversity EWS'],
        ],
        [
            'name' => 'Smart Board',
            'tagline' => 'The film, before the film',
            'description' => 'Boards and animatics that settle pacing, framing and intent before a single frame is produced, so production time is spent building rather than deciding.',
            'accent' => ['#F8847E', '#800D07'],
            'samples' => ['Pricing Fundamentals, Methods & Strategies'],
        ],
        [
            'name' => 'Motion Graphics',
            'tagline' => 'Type, data and identity in motion',
            'description' => 'Titles, lower thirds, data visualisation and brand systems in motion, for films that need to look like they belong to someone.',
            'accent' => ['#FEF1DE', '#A31009'],
            'samples' => ['Adulting', 'Digital Design', 'Emerging Technologies (Hindi Dubbing)', 'IEEE Cybersecurity Intro', 'Sample Video 1'],
        ],
        [
            'name' => 'Articulate',
            'tagline' => 'Storyline and Rise builds that people finish',
            'description' => 'Interactive courses authored in Articulate Storyline and Rise, from scripting and screen design through to SCORM packaging and LMS handover.',
            'accent' => ['#15D9A1', '#00615C'],
            'samples' => ['Introduction to Semiotic Analysis', 'Sales and Operations Planning', 'DSU Unit 8.1', 'DSU Unit 8.2', 'Problem Solving using C: Unit 12.1'],
        ],
        [
            'name' => 'Swayam',
            'tagline' => 'MOOC lectures, produced to broadcast standard',
            'description' => 'End-to-end production for SWAYAM and MOOC courses: studio-recorded lectures, multi-camera sessions and screen capture, edited, captioned and packaged to platform specification.',
            'accent' => ['#C1FAFB', '#00615C'],
            'samples' => ['Cyber Security Course Intro'],
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
