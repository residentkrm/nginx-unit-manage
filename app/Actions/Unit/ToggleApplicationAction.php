<?php

namespace App\Actions\Unit;

use App\Exceptions\Unit\UnitApiException;
use App\Models\UnitApplication;
use App\Services\UnitService;
use Illuminate\Support\Facades\DB;

class ToggleApplicationAction
{
    public function __construct(
        protected UnitService $unitService
    ) {}

    public function execute(UnitApplication $application): UnitApplication
    {
        return DB::transaction(function () use ($application) {
            if ($application->active) {
                // Deactivate - remove from Unit
                $result = $this->unitService->deleteApplication($application->name);
                if (! $result['success']) {
                    throw new UnitApiException($result['error'] ?? 'Failed to deactivate application');
                }
                $application->update(['active' => false]);
            } else {
                // Activate - deploy to Unit
                $config = array_merge(['type' => $application->type], $application->config);
                $result = $this->unitService->saveApplication($application->name, $config);
                if (! $result['success']) {
                    throw new UnitApiException($result['error'] ?? 'Failed to activate application');
                }
                $application->update(['active' => true]);
            }

            return $application->fresh();
        });
    }
}
