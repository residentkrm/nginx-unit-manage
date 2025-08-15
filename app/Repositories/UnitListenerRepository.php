<?php

namespace App\Repositories;

use App\Models\UnitListener;
use Illuminate\Database\Eloquent\Collection;

class UnitListenerRepository
{
    public function all(): Collection
    {
        return UnitListener::orderBy('created_at', 'desc')->get();
    }

    public function findByAddress(string $address): ?UnitListener
    {
        return UnitListener::where('address', $address)->first();
    }

    public function findOrFail(string $address): UnitListener
    {
        return UnitListener::where('address', $address)->firstOrFail();
    }

    public function create(array $data): UnitListener
    {
        return UnitListener::create($data);
    }

    public function update(UnitListener $listener, array $data): bool
    {
        return $listener->update($data);
    }

    public function delete(string $address): bool
    {
        return UnitListener::where('address', $address)->delete();
    }

    public function getActive(): Collection
    {
        return UnitListener::where('active', true)->get();
    }

    public function getInactive(): Collection
    {
        return UnitListener::where('active', false)->get();
    }
}

