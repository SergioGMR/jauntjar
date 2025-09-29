<?php

namespace App\Livewire;

use App\Models\City as Ciudad;
use App\Models\Country;
use Livewire\Component;
use Livewire\WithPagination;

class PlaneadosListado extends Component
{
    use WithPagination;

    public function render()
    {
        $planeados = Ciudad::with('country')
            ->where('visited', false)
            ->orderBy(Country::select('name')->whereColumn('country_id', 'countries.id'))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.planeados-listado', compact('planeados'));
    }
}
