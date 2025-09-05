<?php

namespace App\Http\Controllers\Unit;

use App\Actions\Unit\CreateListenerAction;
use App\Actions\Unit\DeleteListenerAction;
use App\Actions\Unit\ToggleListenerAction;
use App\Actions\Unit\UpdateListenerAction;
use App\Exceptions\Unit\ListenerActiveException;
use App\Exceptions\Unit\ListenerAlreadyExistsException;
use App\Exceptions\Unit\UnitApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Unit\StoreListenerRequest;
use App\Http\Requests\Unit\UpdateListenerRequest;
use App\Repositories\UnitApplicationRepository;
use App\Repositories\UnitListenerRepository;
use App\Services\UnitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ListenerController extends Controller
{
    protected UnitListenerRepository $repository;

    public function __construct(UnitListenerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $listeners = $this->repository->all();
        
        // Try to get active listeners from Unit API (optional, won't fail if server is down)
        $activeListeners = [];
        try {
            $unitService = app(UnitService::class);
            $apiResult = $unitService->getListeners();
            if ($apiResult['success']) {
                $activeListeners = $apiResult['data'];
            }
        } catch (\Exception $e) {
            Log::error('Failed to get listeners from Unit API: ' . $e->getMessage());
        }
        
        return response()->json([
            'data' => $listeners->map(function ($listener) use ($activeListeners) {
                $listenerData = $listener->toArray();
                $listenerData['is_active_in_unit'] = isset($activeListeners[$listener->address]);
                return $listenerData;
            })
        ]);
    }

    public function store(StoreListenerRequest $request, CreateListenerAction $action)
    {
        $validated = $request->validated();

        $config = json_decode($validated['config'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON format'], 400);
        }

        if (!empty($validated['pass'])) {
            $config['pass'] = $validated['pass'];
        }

        try {
            $listener = $action->execute([
                'address' => $validated['address'],
                'pass' => $validated['pass'] ?? null,
                'config' => $config,
                'description' => $validated['description'] ?? null,
            ]);

            return response()->json(['success' => true, 'data' => $listener], 201);
        } catch (ListenerAlreadyExistsException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function show(string $address)
    {
        $address = urldecode($address);
        $listener = $this->repository->findOrFail($address);
        return response()->json(['success' => true, 'data' => $listener]);
    }

    public function update(UpdateListenerRequest $request, string $address, UpdateListenerAction $action)
    {
        $address = urldecode($address);
        $listener = $this->repository->findOrFail($address);
        $validated = $request->validated();

        $config = json_decode($validated['config'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON format'], 400);
        }

        if (!empty($validated['pass'])) {
            $config['pass'] = $validated['pass'];
        }

        $listener = $action->execute($listener, [
            'pass' => $validated['pass'] ?? null,
            'config' => $config,
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json(['success' => true, 'data' => $listener]);
    }

    public function destroy(string $address, DeleteListenerAction $action)
    {
        $address = urldecode($address);
        $listener = $this->repository->findOrFail($address);

        try {
            $action->execute($listener);
            return response()->json(['success' => true], 204);
        } catch (ListenerActiveException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Toggle listener active status - only method that deploys to server
     */
    public function toggle(Request $request, string $address, ToggleListenerAction $action)
    {
        $address = urldecode($address);
        $listener = $this->repository->findOrFail($address);

        try {
            $listener = $action->execute($listener);
            $message = $listener->active 
                ? 'Listener activated and deployed successfully' 
                : 'Listener deactivated successfully';
            return response()->json(['success' => true, 'data' => $listener, 'message' => $message]);
        } catch (UnitApiException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
