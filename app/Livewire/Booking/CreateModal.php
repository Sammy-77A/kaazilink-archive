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
    public function openModal($providerId, $serviceSlug = null)
    {
        $this->providerId = $providerId;
        $this->isOpen = true;
        $this->reset(['serviceSlug', 'serviceAreaId', 'scheduledAt']);
        $this->resetValidation();

        $provider = User::find($providerId);
        if ($provider) {
            if ($serviceSlug) {
                $this->serviceSlug = $serviceSlug;
            } else {
                $firstService = $provider->providerServices->first();
                if ($firstService) {
                    $this->serviceSlug = $firstService->service_slug;
                }
            }
            $firstArea = $provider->serviceAreas->first();
            if ($firstArea) {
                $this->serviceAreaId = $firstArea->id;
            }
        }

        $this->dispatch('wirekit-modal-show', name: 'booking-create-modal');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->dispatch('wirekit-modal-close', name: 'booking-create-modal');
    }

    public function submit()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Gracefully resolve name to numeric ID if text name was submitted
        if ($this->serviceAreaId && !is_numeric($this->serviceAreaId)) {
            $area = ServiceArea::where('name', $this->serviceAreaId)
                ->orWhereRaw('LOWER(name) = ?', [strtolower(trim($this->serviceAreaId))])
                ->first();
            if ($area) {
                $this->serviceAreaId = $area->id;
            }
        }

        // Gracefully resolve service name to slug if service name was submitted
        if ($this->serviceSlug) {
            $allServices = collect(ServiceDirectory::getServices());
            $foundService = $allServices->first(function($s) {
                return strtolower($s['name']) === strtolower(trim($this->serviceSlug))
                    || strtolower($s['slug']) === strtolower(trim($this->serviceSlug));
            });
            if ($foundService) {
                $this->serviceSlug = $foundService['slug'];
            }
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

        $providerService = $provider->providerServices()
            ->where('service_slug', $this->serviceSlug)
            ->first();

        $booking = Booking::create([
            'client_id' => Auth::id(),
            'provider_id' => $provider->id,
            'service_slug' => $this->serviceSlug,
            'price' => $providerService ? $providerService->price : null,
            'scheduled_at' => $this->scheduledAt ?: null,
            'status' => 'requested',
        ]);

        // Send email notification to provider
        $provider->notify(new \App\Notifications\NewBookingRequestNotification($booking));

        $this->isOpen = false;
        $this->dispatch('wirekit-modal-close', name: 'booking-create-modal');
        
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
            $services = $provider->providerServices->map(function($ps) {
                return [
                    'value' => $ps->service_slug,
                    'label' => $ps->display_name . ' (' . $ps->formatted_price . ')',
                ];
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
