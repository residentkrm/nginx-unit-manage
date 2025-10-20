<?php

namespace App\Actions\Unit;

use App\Exceptions\Unit\ListenerAlreadyExistsException;
use App\Exceptions\Unit\UnitApiException;
use App\Repositories\UnitListenerRepository;
use App\Services\UnitService;
use Illuminate\Support\Facades\DB;

class CreateListenerAction
{
    public function __construct(
        protected UnitListenerRepository $repository,
        protected UnitService $unitService
    ) {}

    public function execute(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Check if listener already exists
            $existing = $this->repository->findByAddress($data['address']);
            if ($existing) {
                throw new ListenerAlreadyExistsException($data['address']);
            }

            // Build config object
            $config = $data['config'];
            if (! empty($data['pass'])) {
                $config['pass'] = $data['pass'];
            }

            // Ensure config is an associative array (object), not indexed array
            $configJson = json_encode($config, JSON_FORCE_OBJECT);
            $config = json_decode($configJson, true);

            // Apply to Unit API first
            $result = $this->unitService->saveListener($data['address'], $config);

            if (! $result['success']) {
                throw new UnitApiException($result['error'] ?? 'Failed to create listener on Unit server');
            }

            // Save to database with active=true
            return $this->repository->create([
                'address' => $data['address'],
                'pass' => $data['pass'] ?? null,
                'config' => $data['config'],
                'description' => $data['description'] ?? null,
                'active' => true,
            ]);
        });
    }
}
