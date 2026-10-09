<?php

namespace App\Livewire\Provider;

use Livewire\Component;

class Profile extends Component
{
    public $slug;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        // Enforce gating: only approved providers can be viewed
        $provider = \App\Models\User::approvedProvider()->with('serviceAreas')->findOrFail($this->slug);

        $mapMarkers = $provider->serviceAreas->map(function($area) use ($provider) {
            return [
                'id' => $provider->id . '-' . $area->id,
                'lat' => $area->latitude,
                'lng' => $area->longitude,
                'label' => $provider->name,
                'body' => $area->name,
            ];
        })->toArray();

        return view('livewire.provider.profile', [
            'provider' => $provider,
            'mapMarkers' => $mapMarkers,
        ])->layout('components.layouts.app');
    }
}
