<?php

namespace App\Http\Controllers\Unit;

use App\Actions\Unit\CreateApplicationAction;
use App\Actions\Unit\DeleteApplicationAction;
use App\Actions\Unit\ToggleApplicationAction;
use App\Actions\Unit\UpdateApplicationAction;
use App\Exceptions\Unit\ApplicationActiveException;
use App\Exceptions\Unit\ApplicationAlreadyExistsException;
use App\Exceptions\Unit\UnitApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Unit\StoreApplicationRequest;
use App\Http\Requests\Unit\UpdateApplicationRequest;
use App\Repositories\UnitApplicationRepository;
use App\Services\UnitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
    protected UnitApplicationRepository $repository;

    public function __construct(UnitApplicationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applications = $this->repository->all();

        // Try to get active applications from Unit API (optional, won't fail if server is down)
        $activeApplications = [];
        try {
            $unitService = app(UnitService::class);
            $apiResult = $unitService->getApplications();
            if ($apiResult['success']) {
                $activeApplications = $apiResult['data'];
            }
        } catch (\Exception $e) {
            Log::error('Failed to get applications from Unit API: '.$e->getMessage());
        }

        return response()->json([
            'data' => $applications->map(function ($app) use ($activeApplications) {
                $appData = $app->toArray();
                $appData['is_active_in_unit'] = isset($activeApplications[$app->name]);

                return $appData;
            }),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApplicationRequest $request, CreateApplicationAction $action)
    {
        $validated = $request->validated();

        $config = json_decode($validated['config'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON format'], 400);
        }

        try {
            $application = $action->execute([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'config' => $config,
                'description' => $validated['description'] ?? null,
            ]);

            return response()->json(['success' => true, 'data' => $application], 201);
        } catch (ApplicationAlreadyExistsException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $name)
    {
        $application = $this->repository->findOrFail($name);

        return response()->json(['success' => true, 'data' => $application]);
    }

    /**
     * Update the specified resource in storage.
     * Only updates database, does not deploy to server.
     */
    public function update(UpdateApplicationRequest $request, string $name, UpdateApplicationAction $action)
    {
        $application = $this->repository->findOrFail($name);
        $validated = $request->validated();

        $config = json_decode($validated['config'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON format'], 400);
        }

        $application = $action->execute($application, [
            'type' => $validated['type'],
            'config' => $config,
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json(['success' => true, 'data' => $application]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $name, DeleteApplicationAction $action)
    {
        $application = $this->repository->findOrFail($name);

        try {
            $action->execute($application);

            return response()->json(['success' => true], 204);
        } catch (ApplicationActiveException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Toggle application active status - only method that deploys to server
     */
    public function toggle(Request $request, string $name, ToggleApplicationAction $action)
    {
        $application = $this->repository->findOrFail($name);

        try {
            $application = $action->execute($application);
            $message = $application->active
                ? 'Application activated and deployed successfully'
                : 'Application deactivated successfully';

            return response()->json(['success' => true, 'data' => $application, 'message' => $message]);
        } catch (UnitApiException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
