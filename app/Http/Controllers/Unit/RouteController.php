<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Repositories\UnitRouteRepository;
use App\Services\UnitService;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    protected UnitService $unitService;
    protected UnitRouteRepository $repository;

    public function __construct(UnitService $unitService, UnitRouteRepository $repository)
    {
        $this->unitService = $unitService;
        $this->repository = $repository;
    }

    public function index()
    {
        $routes = $this->repository->all();
        
        // Try to get active routes from Unit API (optional, won't fail if server is down)
        $activeRoutes = [];
        try {
            $apiResult = $this->unitService->getRoutes();
            if ($apiResult['success']) {
                $activeRoutes = $apiResult['data'];
            }
        } catch (\Exception $e) {
            // Silently ignore - server might be down, we work with DB only
        }
        
        return response()->json([
            'data' => $routes->map(function ($route) use ($activeRoutes) {
                $routeData = $route->toArray();
                $routeData['is_active_in_unit'] = isset($activeRoutes[$route->name]);
                return $routeData;
            })
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'config' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $config = json_decode($validated['config'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['success' => false, 'error' => 'Invalid JSON format'], 400);
        }

        // Save to database only (inactive by default)
        $route = $this->repository->updateOrCreate($validated['name'], [
            'config' => $config,
            'description' => $validated['description'] ?? null,
            'active' => false,
        ]);

        return response()->json(['success' => true, 'data' => $route], 201);
    }

    public function show(string $name)
    {
        $route = $this->repository->findOrFail($name);
        return response()->json(['success' => true, 'data' => $route]);
    }

    public function update(Request $request, string $name)
    {
        $validated = $request->validate([
            'config' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $config = json_decode($validated['config'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['success' => false, 'error' => 'Invalid JSON format'], 400);
        }

        // Update database only - no server deployment
        $route = $this->repository->updateOrCreate($name, [
            'config' => $config,
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json(['success' => true, 'data' => $route]);
    }

    public function destroy(string $name)
    {
        $route = $this->repository->findOrFail($name);

        if ($route->active) {
            return response()->json(['success' => false, 'error' => 'Please deactivate the route before deleting it.'], 400);
        }

        $this->repository->delete($name);

        return response()->json(['success' => true], 204);
    }

    /**
     * Toggle route active status - only method that deploys to server
     */
    public function toggle(Request $request, string $name)
    {
        $route = $this->repository->findOrFail($name);
        
        if ($route->active) {
            // Deactivate - remove from Unit
            $result = $this->unitService->deleteRoute($name);
            if ($result['success']) {
                $route->update(['active' => false]);
                $message = 'Route deactivated successfully';
            } else {
                $error = $result['error'] ?? 'Failed to deactivate route';
                return response()->json(['success' => false, 'error' => $error], 400);
            }
        } else {
            // Activate - deploy to Unit
            $result = $this->unitService->saveRoute($name, $route->config);
            if ($result['success']) {
                $route->update(['active' => true]);
                $message = 'Route activated and deployed successfully';
            } else {
                $error = $result['error'] ?? 'Failed to activate route';
                return response()->json(['success' => false, 'error' => $error], 400);
            }
        }

        return response()->json(['success' => true, 'message' => $message]);
    }
}
