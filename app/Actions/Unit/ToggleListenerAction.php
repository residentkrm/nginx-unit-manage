<?php

namespace App\Actions\Unit;

use App\Exceptions\Unit\UnitApiException;
use App\Models\UnitListener;
use App\Repositories\UnitApplicationRepository;
use App\Services\UnitService;
use Illuminate\Support\Facades\DB;

class ToggleListenerAction
{
    public function __construct(
        protected UnitService $unitService,
        protected UnitApplicationRepository $appRepository
    ) {}

    public function execute(UnitListener $listener): UnitListener
    {
        return DB::transaction(function () use ($listener) {
            if ($listener->active) {
                // Deactivate - remove from Unit
                $result = $this->unitService->deleteListener($listener->address);
                if (! $result['success']) {
                    throw new UnitApiException($result['error'] ?? 'Failed to deactivate listener');
                }
                $listener->update(['active' => false]);
            } else {
                // Activate - deploy to Unit
                $config = is_array($listener->config) ? $listener->config : [];

                // If pass is set, add it to config
                $passValue = null;
                if (! empty($listener->pass)) {
                    $passValue = $listener->pass;
                    $config['pass'] = $passValue;
                } elseif (isset($config['pass'])) {
                    $passValue = $config['pass'];
                }

                // Check if pass points to an application and verify it exists
                if ($passValue && str_starts_with($passValue, 'applications/')) {
                    $appName = str_replace('applications/', '', $passValue);

                    // Check if application exists in Unit API
                    $appsResult = $this->unitService->getApplications();
                    if ($appsResult['success'] && ! isset($appsResult['data'][$appName])) {
                        // Check if application exists in database
                        $appInDb = $this->appRepository->findByName($appName);
                        if ($appInDb) {
                            throw new UnitApiException("Application '{$appName}' exists in database but is not activated in Unit. Please activate the application first.");
                        } else {
                            throw new UnitApiException("Application '{$appName}' does not exist. Please create and activate it first.");
                        }
                    }
                }

                // Ensure config is not empty - Unit API requires an object
                if (empty($config)) {
                    throw new UnitApiException('Listener configuration is empty. Please add at least a "pass" field or configuration.');
                }

                // Ensure config is an associative array (object), not indexed array
                $configJson = json_encode($config, JSON_FORCE_OBJECT);
                $config = json_decode($configJson, true);

                $result = $this->unitService->saveListener($listener->address, $config);
                if (! $result['success']) {
                    throw new UnitApiException($result['error'] ?? 'Failed to activate listener');
                }
                $listener->update(['active' => true]);
            }

            return $listener->fresh();
        });
    }
}
