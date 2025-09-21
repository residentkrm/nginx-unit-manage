<template>
    <div class="px-4 sm:px-6 lg:px-8 pb-20">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">Listener: {{ address }}</h1>
                </div>
            </div>

            <div v-if="loading" class="mt-8 text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>

            <div v-else class="mt-8 bg-white dark:bg-gray-800 shadow rounded-lg p-6 transition-colors">
                <pre class="bg-gray-50 dark:bg-gray-900 p-4 rounded text-sm overflow-x-auto text-gray-900 dark:text-gray-100"><code>{{ JSON.stringify(listener, null, 2) }}</code></pre>
            </div>

            <div class="fixed bottom-4 right-4 flex gap-3">
                <router-link to="/unit/listeners" class="rounded-md bg-gray-600 dark:bg-gray-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 dark:hover:bg-gray-600 transition-colors">
                    Назад
                </router-link>
                <router-link :to="`/unit/listeners/${encodeURIComponent(address)}/edit`" class="rounded-md bg-blue-600 dark:bg-blue-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors">
                    Редактировать
                </router-link>
            </div>
        </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useApi } from '../../composables/useApi';
import { useAlert } from '../../composables/useAlert';

const props = defineProps({
    address: String
});

const { get } = useApi();
const { showAlert } = useAlert();
const route = useRoute();
const router = useRouter();
const loading = ref(true);
const listener = ref(null);

const address = computed(() => {
    const addr = props.address || route.params.address;
    return decodeURIComponent(addr);
});

onMounted(async () => {
    loading.value = true;
    const result = await get(`/unit/listeners/${encodeURIComponent(address.value)}`);
    if (result.success) {
        listener.value = result.data.data || result.data;
    } else {
        showAlert(result.error || 'Failed to load listener', 'error');
        router.push('/unit/listeners');
    }
    loading.value = false;
});
</script>

