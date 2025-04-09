<?php

namespace App\Livewire;

use App\Models\Classification;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ClasificadosListado extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'total';
    public $sortDirection = 'desc';
    public $sortFields = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'total'],
        'sortDirection' => ['except' => 'desc'],
        'sortFields' => ['except' => []],
    ];

    // Definimos los métodos que pueden ser llamados desde el frontend
    protected function getListeners()
    {
        return [
            'sortBy',
            'clearSearch',
        ];
    }

    public function sortBy($field)
    {
        if (in_array($field, $this->sortFields)) {
            $key = array_search($field, $this->sortFields);
            unset($this->sortFields[$key]);
        } else {
            $this->sortFields[] = $field;
        }

        $this->sortBy = $field;
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function clearSearch()
    {
        $this->search = '';
    }

    public function render(): View
    {
        $query = Classification::query()->with(['city.country']);

        // Búsqueda
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('city.country', function ($q) {
                    $q->where('display', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('city', function ($q) {
                    $q->where('display', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Ordenamiento
        if (!empty($this->sortFields)) {
            foreach ($this->sortFields as $field) {
                $this->applyOrderBy($query, $field, $this->sortDirection);
            }
        } else {
            $this->applyOrderBy($query, $this->sortBy, $this->sortDirection);
        }

        $clasificados = $query->paginate(10);

        return view('livewire.clasificados-listado', compact('clasificados'));
    }

    /**
     * Aplica el ordenamiento teniendo en cuenta las relaciones
     */
    private function applyOrderBy($query, $field, $direction)
    {
        // Manejar campos de relaciones
        if ($field === 'city.country.display') {
            $query->join('cities', 'classifications.city_id', '=', 'cities.id')
                  ->join('countries', 'cities.country_id', '=', 'countries.id')
                  ->orderBy('countries.display', $direction)
                  ->select('classifications.*');
        } elseif ($field === 'city.display') {
            $query->join('cities', 'classifications.city_id', '=', 'cities.id')
                  ->orderBy('cities.display', $direction)
                  ->select('classifications.*');
        } else {
            // Campos normales
            $query->orderBy($field, $direction);
        }
    }
}
