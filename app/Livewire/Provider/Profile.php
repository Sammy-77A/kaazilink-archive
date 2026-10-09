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
        return view('livewire.provider.profile');
    }
}
