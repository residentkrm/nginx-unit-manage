<?php

namespace App\Actions\Unit;

use App\Exceptions\Unit\ApplicationAlreadyExistsException;
use App\Exceptions\Unit\UnitApiException;
use App\Repositories\UnitApplicationRepository;
use App\Services\UnitService;
use Illuminate\Support\Facades\DB;

class CreateApplicationAction
{
    public function __construct(
        protected UnitApplicationRepository $repository,
        protected UnitService $unitService
    ) {}

    public function execute(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Check if application already exists
            $existing = $this->repository->findByName($data['name']);
            if ($existing) {
                throw new ApplicationAlreadyExistsException($data['name']);
            }

            // Apply to Unit API first
            $config = array_merge(['type' => $data['type']], $data['config']);
            $result = $this->unitService->saveApplication($data['name'], $config);
            
            if (!$result['success']) {
                throw new UnitApiException($result['error'] ?? 'Failed to create application on Unit server');
            }

            // Save to database with active=true
            return $this->repository->create([
                'name' => $data['name'],
                'type' => $data['type'],
                'config' => $data['config'],
                'description' => $data['description'] ?? null,
                'active' => true,
            ]);
        });
    }
}
