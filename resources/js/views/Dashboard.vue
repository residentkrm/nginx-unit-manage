<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Dashboard</h1>
        <div v-if="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>
        <div v-else>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <ApplicationsIcon />
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
                                <ListenersIcon />
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
                                <RoutesIcon />
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
                                <StatusIcon :connected="connected" />
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
            <div v-if="status" class="grid grid-cols-1 gap-6 lg:grid-cols-2">
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
            <div v-else-if="connected" class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <WarningIcon />
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
import ApplicationsIcon from '../components/icons/ApplicationsIcon.vue';
import ListenersIcon from '../components/icons/ListenersIcon.vue';
import RoutesIcon from '../components/icons/RoutesIcon.vue';
import StatusIcon from '../components/icons/StatusIcon.vue';
import WarningIcon from '../components/icons/WarningIcon.vue';
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
