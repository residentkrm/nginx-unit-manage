<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Dashboard</h1>
        
        <div v-if="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>
        
        <div v-else>
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Applications</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                            {{ stats.applicationsCount }}
                                        </div>
                                        <div class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                            ({{ stats.applicationsActive }} active)
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Listeners</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                            {{ stats.listenersCount }}
                                        </div>
                                        <div class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                            ({{ stats.listenersActive }} active)
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Routes</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                            {{ stats.routesCount }}
                                        </div>
                                        <div class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                            ({{ stats.routesActive }} active)
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6" :class="connected ? 'text-green-400' : 'text-red-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Unit API</dt>
                                    <dd class="text-2xl font-semibold" :class="connected ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                        {{ connected ? 'Connected' : 'Disconnected' }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Information -->
            <div v-if="status" class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Modules -->
                <div v-if="status.modules" class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Loaded Modules</h2>
                    </div>
                    <div class="p-6">
                        <div v-for="(module, name) in status.modules" :key="name" class="mb-4 last:mb-0">
                            <div class="font-medium text-gray-900 dark:text-white mb-2">{{ name }}</div>
                            <div v-if="Array.isArray(module)" class="ml-4 space-y-2">
                                <div v-for="(item, idx) in module" :key="idx" class="text-sm text-gray-600 dark:text-gray-400">
                                    <div>Version: {{ item.version }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-500">Lib: {{ item.lib }}</div>
                                </div>
                            </div>
                            <div v-else class="ml-4 text-sm text-gray-600 dark:text-gray-400">
                                <div>Version: {{ module.version }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-500">Lib: {{ module.lib }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Connections & Requests -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Instance Statistics</h2>
                    </div>
                    <div class="p-6">
                        <div v-if="status.connections" class="mb-6">
                            <h3 class="text-md font-medium text-gray-900 dark:text-white mb-3">Connections</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Accepted</div>
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ status.connections.accepted || 0 }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Active</div>
                                    <div class="text-2xl font-semibold text-blue-600 dark:text-blue-400">{{ status.connections.active || 0 }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Idle</div>
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ status.connections.idle || 0 }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Closed</div>
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ status.connections.closed || 0 }}</div>
                                </div>
                            </div>
                        </div>
                        <div v-if="status.requests">
                            <h3 class="text-md font-medium text-gray-900 dark:text-white mb-3">Requests</h3>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Total</div>
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ status.requests.total || 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Applications Status -->
                <div v-if="status.applications && Object.keys(status.applications).length > 0" class="bg-white dark:bg-gray-800 shadow rounded-lg lg:col-span-2">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Applications Status</h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Application</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Processes</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Requests</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="(app, name) in status.applications" :key="name">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <div v-if="app.processes">
                                                <div>Running: <span class="font-semibold text-green-600 dark:text-green-400">{{ app.processes.running || 0 }}</span></div>
                                                <div>Starting: <span class="font-semibold text-yellow-600 dark:text-yellow-400">{{ app.processes.starting || 0 }}</span></div>
                                                <div>Idle: <span class="font-semibold text-gray-600 dark:text-gray-400">{{ app.processes.idle || 0 }}</span></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <div v-if="app.requests">
                                                Active: <span class="font-semibold text-blue-600 dark:text-blue-400">{{ app.requests.active || 0 }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Status Message -->
            <div v-else-if="connected" class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-800 dark:text-yellow-200">Status information is not available</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useApi } from '../composables/useApi';

const { get } = useApi();
const loading = ref(true);
const stats = ref({
    applicationsCount: 0,
    applicationsActive: 0,
    listenersCount: 0,
    listenersActive: 0,
    routesCount: 0,
    routesActive: 0,
});
const connected = ref(false);
const status = ref(null);

onMounted(async () => {
    const result = await get('/unit/dashboard');
    if (result.success) {
        stats.value = result.data;
        connected.value = result.data.connected || false;
        status.value = result.data.status || null;
    }
    loading.value = false;
});
</script>
