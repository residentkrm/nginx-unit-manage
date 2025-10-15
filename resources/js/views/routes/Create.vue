<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">Create Route</h1>
            </div>
        </div>
        <form @submit.prevent="submit" class="mt-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 transition-colors">
                <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button
                            type="button"
                            @click="activeTab = 'form'"
                            :class="activeTab === 'form' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        >
                            Форма
                        </button>
                        <button
                            type="button"
                            @click="activeTab = 'json'; syncToJson()"
                            :class="activeTab === 'json' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        >
                            JSON
                        </button>
                    </nav>
                </div>
                <div v-show="activeTab === 'form'" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input v-model="form.name" type="text" id="name" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Match Conditions</h3>
                        <div>
                            <label for="matchHost" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Host (optional)</label>
                            <input v-model="form.matchHost" type="text" id="matchHost" placeholder="example.com" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Match requests by Host header</p>
                        </div>
                        <div class="mt-4">
                            <label for="matchUri" class="block text-sm font-medium text-gray-700 dark:text-gray-300">URI Pattern (optional)</label>
                            <input v-model="form.matchUri" type="text" id="matchUri" placeholder="/api/*" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Match requests by URI pattern (supports wildcards)</p>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Action</h3>
                        <div>
                            <label for="actionType" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Action Type</label>
                            <select v-model="form.actionType" id="actionType" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                                <option value="pass">Pass</option>
                                <option value="return">Return</option>
                                <option value="share">Share</option>
                            </select>
                        </div>
                        <div v-if="form.actionType === 'pass'" class="mt-4">
                            <label for="actionPass" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pass To</label>
                            <input v-model="form.actionPass" type="text" id="actionPass" required placeholder="applications/myapp" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Application or route to pass requests to</p>
                        </div>
                        <div v-if="form.actionType === 'return'" class="mt-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="returnCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Code</label>
                                    <input v-model.number="form.returnCode" type="number" id="returnCode" required min="100" max="599" placeholder="200" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                                </div>
                                <div>
                                    <label for="returnBody" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Response Body (optional)</label>
                                    <input v-model="form.returnBody" type="text" id="returnBody" placeholder="OK" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                                </div>
                            </div>
                        </div>
                        <div v-if="form.actionType === 'share'" class="mt-4">
                            <label for="actionShare" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Share Path</label>
                            <input v-model="form.actionShare" type="text" id="actionShare" required placeholder="/var/www/html" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Path to static files directory</p>
                        </div>
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea v-model="form.description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"></textarea>
                    </div>
                </div>
                <div v-show="activeTab === 'json'" class="space-y-6">
                    <div>
                        <label for="nameJson" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input v-model="form.name" type="text" id="nameJson" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                    </div>
                    <div>
                        <label for="configJson" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Configuration (JSON)</label>
                        <textarea v-model="jsonConfig" id="configJson" rows="15" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"></textarea>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Enter configuration as JSON</p>
                    </div>
                    <div>
                        <label for="descriptionJson" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea v-model="form.description" id="descriptionJson" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <router-link to="/unit/routes" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200">Cancel</router-link>
                    <button type="submit" :disabled="loading" class="rounded-md bg-blue-600 dark:bg-blue-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 dark:focus-visible:outline-blue-500 transition-colors disabled:opacity-50">
                        {{ loading ? 'Creating...' : 'Create' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useApi } from '../../composables/useApi';
import { useAlert } from '../../composables/useAlert';
const { post } = useApi();
const { showAlert } = useAlert();
const router = useRouter();
const loading = ref(false);
const activeTab = ref('form');
const form = ref({
    name: '',
    matchHost: '',
    matchUri: '',
    actionType: 'pass',
    actionPass: '',
    returnCode: 200,
    returnBody: '',
    actionShare: '',
    description: ''
});
const jsonConfig = ref(JSON.stringify({
    match: {
        host: 'example.com'
    },
    action: {
        pass: 'applications/myapp'
    }
}, null, 2));
const syncToJson = () => {
    const config = {};
    // Match conditions
    const match = {};
    if (form.value.matchHost) {
        match.host = form.value.matchHost;
    }
    if (form.value.matchUri) {
        match.uri = form.value.matchUri;
    }
    if (Object.keys(match).length > 0) {
        config.match = match;
    }
    // Action
    const action = {};
    if (form.value.actionType === 'pass') {
        action.pass = form.value.actionPass || 'applications/myapp';
    } else if (form.value.actionType === 'return') {
        if (form.value.returnBody) {
            action.return = {
                code: form.value.returnCode,
                body: form.value.returnBody
            };
        } else {
            action.return = form.value.returnCode;
        }
    } else if (form.value.actionType === 'share') {
        action.share = form.value.actionShare || '/var/www/html';
    }
    config.action = action;
    jsonConfig.value = JSON.stringify(config, null, 2);
};
const submit = async () => {
    loading.value = true;
    try {
        let config;
        if (activeTab.value === 'json') {
            try {
                config = JSON.parse(jsonConfig.value);
            } catch (e) {
                showAlert('Invalid JSON format', 'error');
                loading.value = false;
                return;
            }
        } else {
            // Match conditions
            const match = {};
            if (form.value.matchHost) {
                match.host = form.value.matchHost;
            }
            if (form.value.matchUri) {
                match.uri = form.value.matchUri;
            }
            if (Object.keys(match).length > 0) {
                config.match = match;
            }
            // Action
            const action = {};
            if (form.value.actionType === 'pass') {
                if (!form.value.actionPass) {
                    showAlert('Pass destination is required', 'error');
                    loading.value = false;
                    return;
                }
                action.pass = form.value.actionPass;
            } else if (form.value.actionType === 'return') {
                if (form.value.returnBody) {
                    action.return = {
                        code: form.value.returnCode,
                        body: form.value.returnBody
                    };
                } else {
                    action.return = form.value.returnCode;
                }
            } else if (form.value.actionType === 'share') {
                if (!form.value.actionShare) {
                    showAlert('Share path is required', 'error');
                    loading.value = false;
                    return;
                }
                action.share = form.value.actionShare;
            }
            config.action = action;
        }
        const result = await post('/unit/routes', {
            name: form.value.name,
            config: JSON.stringify(config),
            description: form.value.description
        });
        if (result.success) {
            showAlert('Route created successfully', 'success');
            router.push('/unit/routes');
        } else {
            showAlert(result.error || 'Failed to create route', 'error');
        }
    } catch (error) {
        showAlert('Failed to create route', 'error');
    } finally {
        loading.value = false;
    }
};
</script>
