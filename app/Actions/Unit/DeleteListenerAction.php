<?php

namespace App\Actions\Unit;

use App\Exceptions\Unit\ListenerActiveException;
use App\Models\UnitListener;
use App\Repositories\UnitListenerRepository;
use Illuminate\Support\Facades\DB;

class DeleteListenerAction
{
    public function __construct(
        protected UnitListenerRepository $repository
    ) {}

    public function execute(UnitListener $listener): void
    {
        if ($listener->active) {
            throw new ListenerActiveException;
        }

        DB::transaction(function () use ($listener) {
            $this->repository->delete($listener->address);
        });
    }
}
