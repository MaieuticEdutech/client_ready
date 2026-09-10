<?php

use App\Models\Sample;
use App\Models\Service;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    /**
     * What is published and what is still waiting on footage.
     */
    #[Computed]
    public function stats(): array
    {
        return [
            'services' => Service::count(),
            'published' => Service::published()->count(),
            'samples' => Sample::count(),
            'pending' => Sample::whereNull('video_path')->whereNull('video_url')->count(),
        ];
    }
};
