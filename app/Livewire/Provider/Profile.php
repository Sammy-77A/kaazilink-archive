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
        $provider = \App\Models\User::approvedProvider()->findOrFail($this->slug);

        return view('livewire.provider.profile', [
            'provider' => $provider,
        ])->layout('components.layouts.app');
    }
}
