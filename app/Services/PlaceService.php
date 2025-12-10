<?php

namespace App\Services;

use App\Models\Place;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PlaceService
{
    public const SORTABLE_COLUMNS = [
        'id',
        'name',
        'city',
        'state',
        'created_at',
        'updated_at',
    ];

    public function list(array $filters): LengthAwarePaginator
    {
        $query = Place::query();

        $filters = $this->normalizeFilters($filters);

        $name = $filters['name'] ?? null;
        if ($name) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%'.strtolower($name).'%']);
        }

        $city = $filters['city'] ?? null;
        if ($city) {
            $query->whereRaw('LOWER(city) LIKE ?', ['%'.strtolower($city).'%']);
        }

        $state = $filters['state'] ?? null;
        if ($state) {
            $query->whereRaw('UPPER(state) = ?', [strtoupper($state)]);
        }

        [$column, $direction] = $this->resolveSort($filters['sort'] ?? null);
        $query->orderBy($column, $direction);

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function create(array $data): Place
    {
        return Place::create($this->normalizeData($data));
    }

    public function update(Place $place, array $data): Place
    {
        $place->update($this->normalizeData($data));
        return $place;
    }

    public function delete(Place $place): void
    {
        $place->delete();
    }

    public function findBySlugOrFail(string $slug): Place
    {
        return Place::where('slug', $slug)->firstOrFail();
    }

    private function normalizeData(array $data): array
    {
        if (isset($data['state'])) {
            $data['state'] = strtoupper($data['state']);
        }

        return $data;
    }

    private function normalizeFilters(array $filters): array
    {
        if (isset($filters['per_page'])) {
            $filters['per_page'] = min(max((int) $filters['per_page'], 1), 100);
        }

        return $filters;
    }

    private function resolveSort(?string $sort): array
    {
        if ($sort === null) {
            return ['created_at', 'desc'];
        }

        $column = ltrim($sort, '-');
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';

        if (!in_array($column, self::SORTABLE_COLUMNS, true)) {
            $column = 'created_at';
            $direction = 'desc';
        }

        return [$column, $direction];
    }
}
