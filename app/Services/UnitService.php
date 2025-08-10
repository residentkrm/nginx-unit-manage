<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class UnitService
{
    protected string $apiUrl;
    protected int $timeout;
    protected bool $verifySsl;

    public function __construct()
    {
        $this->apiUrl = config('unit.api_url');
        $this->timeout = config('unit.api_timeout');
        $this->verifySsl = config('unit.verify_ssl');
    }

    /**
     * Extract error message from Unit API response
     */
    protected function extractErrorMessage(string $body): string
    {
        $errorData = json_decode($body, true);
        
        if (json_last_error() === JSON_ERROR_NONE && is_array($errorData)) {
            if (isset($errorData['error']) && isset($errorData['detail'])) {
                return $errorData['error'] . ': ' . $errorData['detail'];
            } elseif (isset($errorData['detail'])) {
                return $errorData['detail'];
            } elseif (isset($errorData['error'])) {
                return $errorData['error'];
            }
        }
        
        return $body;
    }

    /**
     * Make HTTP request to Unit API
     */
    protected function request(string $method, string $endpoint, array $data = []): array
    {
        try {
            // Check if using Unix socket
            if (str_starts_with($this->apiUrl, 'unix:')) {
                return $this->requestUnixSocket($method, $endpoint, $data);
            }

            // HTTP request
            $url = rtrim($this->apiUrl, '/') . '/' . ltrim($endpoint, '/');
            
            $response = Http::timeout($this->timeout)
                ->withoutVerifying(!$this->verifySsl)
                ->{strtolower($method)}($url, $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json() ?? [],
                ];
            }

            return [
                'success' => false,
                'error' => $this->extractErrorMessage($response->body()),
                'status' => $response->status(),
            ];
        } catch (Exception $e) {
            Log::error('Unit API request failed', [
                'method' => $method,
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Make request via Unix socket using curl
     */
    protected function requestUnixSocket(string $method, string $endpoint, array $data = []): array
    {
        $socketPath = str_replace('unix:', '', $this->apiUrl);
        $url = 'http://localhost' . '/' . ltrim($endpoint, '/');
        
        $curlCommand = sprintf(
            'curl -s -w "\n%%{http_code}" --unix-socket %s -X %s -H "Content-Type: application/json"',
            escapeshellarg($socketPath),
            escapeshellarg(strtoupper($method))
        );

        if (!empty($data) && in_array(strtoupper($method), ['PUT', 'POST', 'PATCH'])) {
            $jsonData = json_encode($data);
            $curlCommand .= ' -d ' . escapeshellarg($jsonData);
        }

        $curlCommand .= ' ' . escapeshellarg($url);

        $output = shell_exec($curlCommand . ' 2>&1');

        if (empty($output)) {
            return [
                'success' => false,
                'error' => 'Failed to connect to Unit API',
            ];
        }

        // Extract HTTP status code from the end
        $lines = explode("\n", trim($output));
        $httpCode = (int)array_pop($lines);
        $body = implode("\n", $lines);

        if ($httpCode < 200 || $httpCode >= 300) {
            return [
                'success' => false,
                'error' => $this->extractErrorMessage($body),
                'status' => $httpCode,
            ];
        }

        $decoded = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'Invalid JSON response: ' . $body,
            ];
        }

        return [
            'success' => true,
            'data' => $decoded ?? [],
        ];
    }

    /**
     * Get current configuration
     */
    public function getConfig(): array
    {
        return $this->request('GET', '/config');
    }

    /**
     * Update configuration
     */
    public function updateConfig(array $config): array
    {
        return $this->request('PUT', '/config', $config);
    }

    /**
     * Get list of applications
     */
    public function getApplications(): array
    {
        $result = $this->getConfig();
        
        if (!$result['success']) {
            return $result;
        }

        return [
            'success' => true,
            'data' => $result['data']['applications'] ?? [],
        ];
    }

    /**
     * Get specific application
     */
    public function getApplication(string $name): array
    {
        $result = $this->getConfig();
        
        if (!$result['success']) {
            return $result;
        }

        $applications = $result['data']['applications'] ?? [];
        
        if (!isset($applications[$name])) {
            return [
                'success' => false,
                'error' => "Application '{$name}' not found",
            ];
        }

        return [
            'success' => true,
            'data' => $applications[$name],
        ];
    }

    /**
     * Create or update application
     */
    public function saveApplication(string $name, array $config): array
    {
        $result = $this->getConfig();
        
        if (!$result['success']) {
            return $result;
        }

        $currentConfig = $result['data'];
        $currentConfig['applications'][$name] = $config;

        return $this->updateConfig($currentConfig);
    }

    /**
     * Delete application
     */
    public function deleteApplication(string $name): array
    {
        $result = $this->getConfig();
        
        if (!$result['success']) {
            return $result;
        }

        $currentConfig = $result['data'];
        
        if (!isset($currentConfig['applications'][$name])) {
            return [
                'success' => false,
                'error' => "Application '{$name}' not found",
            ];
        }

        unset($currentConfig['applications'][$name]);

        return $this->updateConfig($currentConfig);
    }

    /**
     * Get list of listeners
     */
    public function getListeners(): array
    {
        $result = $this->getConfig();
        
        if (!$result['success']) {
            return $result;
        }

        return [
            'success' => true,
            'data' => $result['data']['listeners'] ?? [],
        ];
    }

    /**
     * Create or update listener
     */
    public function saveListener(string $address, array $config): array
    {
        $result = $this->getConfig();
        
        if (!$result['success']) {
            return $result;
        }

        $currentConfig = $result['data'];
        
        // Ensure listeners key exists
        if (!isset($currentConfig['listeners'])) {
            $currentConfig['listeners'] = [];
        }
        
        // Validate config is an object (associative array)
        if (empty($config) || !is_array($config)) {
            return [
                'success' => false,
                'error' => 'Listener configuration must be a non-empty object',
            ];
        }
        
        $currentConfig['listeners'][$address] = $config;

        return $this->updateConfig($currentConfig);
    }

    /**
     * Delete listener
     */
    public function deleteListener(string $address): array
    {
        $result = $this->getConfig();
        
        if (!$result['success']) {
            return $result;
        }

        $currentConfig = $result['data'];
        
        if (!isset($currentConfig['listeners'][$address])) {
            return [
                'success' => false,
                'error' => "Listener '{$address}' not found",
            ];
        }

        unset($currentConfig['listeners'][$address]);

        return $this->updateConfig($currentConfig);
    }

    /**
     * Get routes
     */
    public function getRoutes(): array
    {
        $result = $this->getConfig();
        
        if (!$result['success']) {
            return $result;
        }

        return [
            'success' => true,
            'data' => $result['data']['routes'] ?? [],
        ];
    }

    /**
     * Create or update route
     */
    public function saveRoute(string $name, array $config): array
    {
        $result = $this->getConfig();
        
        if (!$result['success']) {
            return $result;
        }

        $currentConfig = $result['data'];
        $currentConfig['routes'][$name] = $config;

        return $this->updateConfig($currentConfig);
    }

    /**
     * Delete route
     */
    public function deleteRoute(string $name): array
    {
        $result = $this->getConfig();
        
        if (!$result['success']) {
            return $result;
        }

        $currentConfig = $result['data'];
        
        if (!isset($currentConfig['routes'][$name])) {
            return [
                'success' => false,
                'error' => "Route '{$name}' not found",
            ];
        }

        unset($currentConfig['routes'][$name]);

        return $this->updateConfig($currentConfig);
    }

    /**
     * Get Unit status (modules, connections, requests, applications)
     */
    public function getStatus(): array
    {
        return $this->request('GET', 'status');
    }

    /**
     * Test connection to Unit API
     */
    public function testConnection(): array
    {
        return $this->getConfig();
    }
}

