@php
    $stats = $this->stats;

    $tiles = [
        ['label' => 'Service lines', 'value' => $stats['services'], 'note' => $stats['published'].' published'],
        ['label' => 'Samples', 'value' => $stats['samples'], 'note' => $stats['pending'].' awaiting footage'],
        ['label' => 'Ready', 'value' => $stats['samples'] - $stats['pending'], 'note' => 'with a film or link'],
    ];
@endphp

<div>
    <h1 class="text-2xl font-bold text-white">Studio overview</h1>
    <p class="mt-1 text-sm text-white/40">What is live, and what is still waiting on assets.</p>

    <div class="mt-6 grid gap-4 sm:grid-cols-3">
        @foreach ($tiles as $tile)
            <div class="rounded-2xl border border-white/10 bg-ink-800 p-5">
                <p class="text-sm text-white/50">{{ $tile['label'] }}</p>
                <p class="mt-2 text-3xl font-bold tabular-nums text-white">{{ $tile['value'] }}</p>
                <p class="mt-1 text-xs text-white/30">{{ $tile['note'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-8 rounded-2xl border border-dashed border-white/10 p-8 text-center">
        <p class="text-sm text-white/50">Sample management lands here next.</p>
    </div>
</div>
