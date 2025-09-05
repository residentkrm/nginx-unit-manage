<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Repositories\UnitApplicationRepository;
use App\Repositories\UnitListenerRepository;
use App\Repositories\UnitRouteRepository;
use App\Services\UnitService;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected UnitService $unitService;
    protected UnitApplicationRepository $appRepository;
    protected UnitListenerRepository $listenerRepository;
    protected UnitRouteRepository $routeRepository;

    public function __construct(
        UnitService $unitService,
        UnitApplicationRepository $appRepository,
        UnitListenerRepository $listenerRepository,
        UnitRouteRepository $routeRepository
    ) {
        $this->unitService = $unitService;
        $this->appRepository = $appRepository;
        $this->listenerRepository = $listenerRepository;
        $this->routeRepository = $routeRepository;
    }

    public function index()
    {
        // Try to test connection (won't fail if server is down)
        $connected = false;
        $status = null;
        try {
            $connectionTest = $this->unitService->testConnection();
            $connected = $connectionTest['success'] ?? false;
            
            // Get status if connected
            if ($connected) {
                $statusResult = $this->unitService->getStatus();
                if ($statusResult['success']) {
                    $status = $statusResult['data'];
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to get Unit API status: ' . $e->getMessage());
        }
        
        $applicationsCount = $this->appRepository->all()->count();
        $applicationsActive = $this->appRepository->getActive()->count();
        
        $listenersCount = $this->listenerRepository->all()->count();
        $listenersActive = $this->listenerRepository->getActive()->count();
        
        $routesCount = $this->routeRepository->all()->count();
        $routesActive = $this->routeRepository->getActive()->count();

        return response()->json([
            'connected' => $connected,
            'status' => $status,
            'applicationsCount' => $applicationsCount,
            'applicationsActive' => $applicationsActive,
            'listenersCount' => $listenersCount,
            'listenersActive' => $listenersActive,
            'routesCount' => $routesCount,
            'routesActive' => $routesActive,
        ]);
    }
}
