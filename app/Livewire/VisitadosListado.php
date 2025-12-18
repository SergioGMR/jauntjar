<?php

namespace App\Livewire;

use App\Models\City as Ciudad;
use App\Models\Country;
use Livewire\Component;
use Livewire\WithPagination;

class VisitadosListado extends Component
{
    use WithPagination;

    public function render()
    {
        $visitados = Ciudad::with('country', 'classification')
            ->where('visited', true)
            ->orderBy(Country::select('name')->whereColumn('country_id', 'countries.id'))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.visitados-listado', compact('visitados'));
    }
}
