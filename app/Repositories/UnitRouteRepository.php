<?php

namespace App\Repositories;

use App\Models\UnitRoute;
use Illuminate\Database\Eloquent\Collection;

class UnitRouteRepository
{
    public function all(): Collection
    {
        return UnitRoute::orderBy('created_at', 'desc')->get();
    }

    public function findByName(string $name): ?UnitRoute
    {
        return UnitRoute::where('name', $name)->first();
    }

    public function findOrFail(string $name): UnitRoute
    {
        return UnitRoute::where('name', $name)->firstOrFail();
    }

    public function create(array $data): UnitRoute
    {
        return UnitRoute::create($data);
    }

    public function updateOrCreate(string $name, array $data): UnitRoute
    {
        return UnitRoute::updateOrCreate(['name' => $name], $data);
    }

    public function delete(string $name): bool
    {
        return UnitRoute::where('name', $name)->delete();
    }

    public function getActive(): Collection
    {
        return UnitRoute::where('active', true)->get();
    }

    public function getInactive(): Collection
    {
        return UnitRoute::where('active', false)->get();
    }
}
