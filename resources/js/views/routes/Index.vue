<template>
    <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">Routes</h1>
                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Manage your Nginx Unit routes</p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <router-link to="/unit/routes/create" class="block rounded-md bg-blue-600 dark:bg-blue-500 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors">
                        Add route
                    </router-link>
                </div>
            </div>
            <div v-if="loading" class="mt-8 text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>
            <div v-else class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 dark:ring-gray-700 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">Name</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Description</th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                    <tr v-for="route in routes" :key="route.name" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                            <code class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-800 dark:text-gray-200">{{ route.name }}</code>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            <div class="flex items-center">
                                                <form @submit.prevent="toggleRoute(route.name)" class="flex items-center">
                                                    <button type="submit" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 dark:focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800" :class="route.active ? 'bg-green-600 dark:bg-green-500' : 'bg-gray-200 dark:bg-gray-600'">
                                                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="route.active ? 'translate-x-5' : 'translate-x-0'"></span>
                                                    </button>
                                                </form>
                                                <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                                                    {{ route.active ? 'Active' : 'Inactive' }}
                                                    <span v-if="route.active && route.is_active_in_unit" class="text-green-600 dark:text-green-400">✓</span>
                                                    <span v-else-if="route.active" class="text-yellow-600 dark:text-yellow-400">⚠</span>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ route.description || '-' }}
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <div class="flex items-center justify-end gap-2">
                                                <router-link :to="`/unit/routes/${route.name}`" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">View</router-link>
                                                <router-link :to="`/unit/routes/${route.name}/edit`" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Edit</router-link>
                                                <button @click="deleteRoute(route.name)" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="routes.length === 0">
                                        <td colspan="4" class="py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                            <p>No routes found. <router-link to="/unit/routes/create" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">Create one</router-link></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useApi } from '../../composables/useApi';
import { useAlert } from '../../composables/useAlert';
const { get, post, delete: del } = useApi();
const { showAlert } = useAlert();
const router = useRouter();
const loading = ref(true);
const routes = ref([]);
const loadRoutes = async () => {
    loading.value = true;
    const result = await get('/unit/routes');
    if (result.success) {
        routes.value = result.data.data || [];
    }
    loading.value = false;
};
const toggleRoute = async (name) => {
    const result = await post(`/unit/routes/${name}/toggle`, {});
    if (result.success) {
        showAlert('Route toggled successfully', 'success');
        await loadRoutes();
    } else {
        showAlert(result.error || 'Failed to toggle route', 'error');
    }
};
const deleteRoute = async (name) => {
    if (!confirm('Are you sure you want to delete this route?')) return;
    const result = await del(`/unit/routes/${name}`);
    if (result.success) {
        showAlert('Route deleted successfully', 'success');
        await loadRoutes();
    } else {
        showAlert(result.error || 'Failed to delete route', 'error');
    }
};
onMounted(loadRoutes);
</script>
