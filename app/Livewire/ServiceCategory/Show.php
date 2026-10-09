<?php

namespace App\Livewire\ServiceCategory;

use Livewire\Component;

class Show extends Component
{
    public $slug;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        return view('livewire.service-category.show');
    }
}
