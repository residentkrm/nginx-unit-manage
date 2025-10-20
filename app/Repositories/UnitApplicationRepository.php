<?php

namespace App\Repositories;

use App\Models\UnitApplication;
use Illuminate\Database\Eloquent\Collection;

class UnitApplicationRepository
{
    public function all(): Collection
    {
        return UnitApplication::orderBy('created_at', 'desc')->get();
    }

    public function findByName(string $name): ?UnitApplication
    {
        return UnitApplication::where('name', $name)->first();
    }

    public function findOrFail(string $name): UnitApplication
    {
        return UnitApplication::where('name', $name)->firstOrFail();
    }

    public function create(array $data): UnitApplication
    {
        return UnitApplication::create($data);
    }

    public function update(UnitApplication $application, array $data): bool
    {
        return $application->update($data);
    }

    public function delete(string $name): bool
    {
        return UnitApplication::where('name', $name)->delete();
    }

    public function getActive(): Collection
    {
        return UnitApplication::where('active', true)->get();
    }

    public function getInactive(): Collection
    {
        return UnitApplication::where('active', false)->get();
    }
}
