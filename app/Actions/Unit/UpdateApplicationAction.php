<?php

namespace App\Actions\Unit;

use App\Exceptions\Unit\UnitApiException;
use App\Models\UnitApplication;
use App\Repositories\UnitApplicationRepository;
use App\Services\UnitService;
use Illuminate\Support\Facades\DB;

class UpdateApplicationAction
{
    public function __construct(
        protected UnitApplicationRepository $repository,
        protected UnitService $unitService
    ) {}

    public function execute(UnitApplication $application, array $data): UnitApplication
    {
        return DB::transaction(function () use ($application, $data) {
            // If application is active, update on Unit API first
            if ($application->active) {
                $config = array_merge(['type' => $data['type']], $data['config']);
                $result = $this->unitService->saveApplication($application->name, $config);

                if (! $result['success']) {
                    throw new UnitApiException($result['error'] ?? 'Failed to update application on Unit server');
                }
            }

            // Update in database
            $this->repository->update($application, [
                'type' => $data['type'],
                'config' => $data['config'],
                'description' => $data['description'] ?? null,
            ]);

            return $application->fresh();
        });
    }
}
