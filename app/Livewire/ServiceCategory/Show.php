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
        // Fetch approved providers (mocking the relationship to the category for now by just getting all approved providers)
        $providers = \App\Models\User::approvedProvider()->paginate(10);

        return view('livewire.service-category.show', [
            'providers' => $providers,
        ]);
    }
}
