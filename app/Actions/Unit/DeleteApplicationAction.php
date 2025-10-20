<?php

namespace App\Actions\Unit;

use App\Exceptions\Unit\ApplicationActiveException;
use App\Models\UnitApplication;
use App\Repositories\UnitApplicationRepository;
use Illuminate\Support\Facades\DB;

class DeleteApplicationAction
{
    public function __construct(
        protected UnitApplicationRepository $repository
    ) {}

    public function execute(UnitApplication $application): void
    {
        if ($application->active) {
            throw new ApplicationActiveException;
        }

        DB::transaction(function () use ($application) {
            $this->repository->delete($application->name);
        });
    }
}
