<?php

namespace App\Livewire\ServiceCategory;

use Livewire\Component;

class Show extends Component
{
    public $slug;
    public $searchAreaId = null;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        $areas = \App\Models\ServiceArea::all()->map(function($area) {
            return ['value' => $area->id, 'label' => $area->name];
        })->toArray();
        $query = \App\Models\User::approvedProvider();

        if ($this->searchAreaId) {
            $searchArea = \App\Models\ServiceArea::where('id', $this->searchAreaId)
                                                 ->orWhere('name', $this->searchAreaId)
                                                 ->first();
            if ($searchArea) {
                $lat = $searchArea->latitude;
                $lng = $searchArea->longitude;
                
                $query->select('users.*')
                      ->join('service_area_user', 'users.id', '=', 'service_area_user.user_id')
                      ->join('service_areas', 'service_area_user.service_area_id', '=', 'service_areas.id')
                      ->selectRaw(
                          'MIN(6371 * acos(cos(radians(?)) * cos(radians(service_areas.latitude)) * cos(radians(service_areas.longitude) - radians(?)) + sin(radians(?)) * sin(radians(service_areas.latitude)))) AS distance',
                          [$lat, $lng, $lat]
                      )
                      ->groupBy('users.id')
                      ->orderBy('distance', 'asc');
            }
        }

        $providers = $query->with('serviceAreas')->paginate(10);

        $mapMarkers = collect($providers->items())->flatMap(function($provider) {
            return $provider->serviceAreas->map(function($area) use ($provider) {
                return [
                    'id' => $provider->id . '-' . $area->id,
                    'lat' => $area->latitude,
                    'lng' => $area->longitude,
                    'label' => $provider->name,
                    'body' => $area->name,
                ];
            });
        })->values()->toArray();

        return view('livewire.service-category.show', [
            'providers' => $providers,
            'mapMarkers' => $mapMarkers,
            'availableAreasOptions' => $areas,
        ])->layout('components.layouts.app');
    }
}
