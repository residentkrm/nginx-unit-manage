<?php

namespace App\Actions\Unit;

use App\Exceptions\Unit\UnitApiException;
use App\Models\UnitListener;
use App\Repositories\UnitListenerRepository;
use App\Services\UnitService;
use Illuminate\Support\Facades\DB;

class UpdateListenerAction
{
    public function __construct(
        protected UnitListenerRepository $repository,
        protected UnitService $unitService
    ) {}

    public function execute(UnitListener $listener, array $data): UnitListener
    {
        return DB::transaction(function () use ($listener, $data) {
            // If listener is active, update on Unit API first
            if ($listener->active) {
                // Build config object
                $config = $data['config'];
                if (!empty($data['pass'])) {
                    $config['pass'] = $data['pass'];
                }

                // Ensure config is an associative array (object), not indexed array
                $configJson = json_encode($config, JSON_FORCE_OBJECT);
                $config = json_decode($configJson, true);

                $result = $this->unitService->saveListener($listener->address, $config);
                
                if (!$result['success']) {
                    throw new UnitApiException($result['error'] ?? 'Failed to update listener on Unit server');
                }
            }

            // Update in database
            $this->repository->update($listener, [
                'pass' => $data['pass'] ?? null,
                'config' => $data['config'],
                'description' => $data['description'] ?? null,
            ]);

            return $listener->fresh();
        });
    }
}
