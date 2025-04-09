<?php

namespace App\Livewire\Components;

use App\Models\Classification;
use Livewire\Component;

class MapsGoogle extends Component
{
    public $mapType = 'hybrid';
    public $zoomLevel = 5;
    public $fitToBounds = true;
    public $centerPoint = ['lat' => 28.2925418, 'long' => -15.9515938];
    public $centerToBoundsCenter = true;
    public $markers = [];

    public function mount(): void
    {
        $this->markers = Classification::with('city')->get()->map(function ($classification) {
            return [
                'lat' => $classification->city->coordinates['lat'],
                'long' => $classification->city->coordinates['lng'],
                'title' => $classification->city->display,
                'info' => $classification->total,
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.components.maps-google');
    }
}
