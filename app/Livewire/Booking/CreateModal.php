<?php

namespace App\Livewire\Booking;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use App\Models\Booking;
use App\Models\ServiceArea;
use App\Data\ServiceDirectory;
use Illuminate\Support\Facades\Auth;

class CreateModal extends Component
{
    public $providerId;
    public $isOpen = false;
    
    public $serviceSlug = '';
    public $serviceAreaId = '';
    public $scheduledAt = '';

    protected $rules = [
        'serviceSlug' => 'required|string',
        'serviceAreaId' => 'required|exists:service_areas,id',
        'scheduledAt' => 'nullable|date|after:now',
    ];

    #[On('openBookingModal')]
    public function openModal($providerId)
    {
        $this->providerId = $providerId;
        $this->reset(['serviceSlug', 'serviceAreaId', 'scheduledAt']);
        $this->isOpen = true;
    }

    public function submit()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate();

        $provider = User::approvedProvider()->findOrFail($this->providerId);
        
        // Ensure provider offers this service
        if (!$provider->providerServices()->where('service_slug', $this->serviceSlug)->exists()) {
            $this->addError('serviceSlug', 'The provider does not offer this service.');
            return;
        }

        // Ensure provider serves this area
        if (!$provider->serviceAreas()->where('service_area_id', $this->serviceAreaId)->exists()) {
            $this->addError('serviceAreaId', 'The provider does not serve this area.');
            return;
        }

        Booking::create([
            'client_id' => Auth::id(),
            'provider_id' => $provider->id,
            'service_slug' => $this->serviceSlug,
            'scheduled_at' => $this->scheduledAt ?: null,
            'status' => 'requested',
        ]);

        $this->isOpen = false;
        
        // Dispatch success message via wirekit or standard session flash
        session()->flash('success', 'Booking requested successfully!');
        $this->dispatch('bookingCreated');
    }

    public function render()
    {
        $provider = $this->providerId ? User::find($this->providerId) : null;
        
        $services = [];
        $areas = [];
        
        if ($provider) {
            $allServices = collect(ServiceDirectory::getServices());
            $services = $provider->providerServices->map(function($ps) use ($allServices) {
                $s = $allServices->firstWhere('slug', $ps->service_slug);
                return ['value' => $s['slug'], 'label' => $s['name']];
            })->toArray();
            
            $areas = $provider->serviceAreas->map(function($area) {
                return ['value' => $area->id, 'label' => $area->name];
            })->toArray();
        }

        return view('livewire.booking.create-modal', [
            'provider' => $provider,
            'servicesOptions' => $services,
            'areasOptions' => $areas,
        ]);
    }
}
