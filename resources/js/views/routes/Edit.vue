<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1
                    class="text-3xl font-semibold text-gray-900 dark:text-white"
                >
                    Edit Route: {{ name }}
                </h1>
            </div>
        </div>
        <div v-if="loading" class="mt-8 text-center py-12">
            <div
                class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"
            ></div>
        </div>
        <form v-else class="mt-8" @submit.prevent="submit">
            <div
                class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 transition-colors"
            >
                <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button
                            type="button"
                            :class="
                                activeTab === 'form'
                                    ? 'border-blue-500 text-blue-600 dark:text-blue-400'
                                    : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'
                            "
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                            @click="activeTab = 'form'"
                        >
                            Форма
                        </button>
                        <button
                            type="button"
                            :class="
                                activeTab === 'json'
                                    ? 'border-blue-500 text-blue-600 dark:text-blue-400'
                                    : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'
                            "
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                            @click="
                                activeTab = 'json';
                                syncToJson();
                            "
                        >
                            JSON
                        </button>
                    </nav>
                </div>
                <div v-show="activeTab === 'form'" class="space-y-6">
                    <div
                        class="border-t border-gray-200 dark:border-gray-700 pt-6"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        >
                            Match Conditions
                        </h3>
                        <div>
                            <label
                                for="matchHost"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Host (optional)</label
                            >
                            <input
                                id="matchHost"
                                v-model="form.matchHost"
                                type="text"
                                placeholder="example.com"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                            />
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Match requests by Host header
                            </p>
                        </div>
                        <div class="mt-4">
                            <label
                                for="matchUri"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >URI Pattern (optional)</label
                            >
                            <input
                                id="matchUri"
                                v-model="form.matchUri"
                                type="text"
                                placeholder="/api/*"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                            />
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Match requests by URI pattern (supports
                                wildcards)
                            </p>
                        </div>
                    </div>
                    <div
                        class="border-t border-gray-200 dark:border-gray-700 pt-6"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        >
                            Action
                        </h3>
                        <div>
                            <label
                                for="actionType"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Action Type</label
                            >
                            <select
                                id="actionType"
                                v-model="form.actionType"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                            >
                                <option value="pass">Pass</option>
                                <option value="return">Return</option>
                                <option value="share">Share</option>
                            </select>
                        </div>
                        <div v-if="form.actionType === 'pass'" class="mt-4">
                            <label
                                for="actionPass"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Pass To</label
                            >
                            <input
                                id="actionPass"
                                v-model="form.actionPass"
                                type="text"
                                required
                                placeholder="applications/myapp"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                            />
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Application or route to pass requests to
                            </p>
                        </div>
                        <div v-if="form.actionType === 'return'" class="mt-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label
                                        for="returnCode"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Status Code</label
                                    >
                                    <input
                                        id="returnCode"
                                        v-model.number="form.returnCode"
                                        type="number"
                                        required
                                        min="100"
                                        max="599"
                                        placeholder="200"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="returnBody"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Response Body (optional)</label
                                    >
                                    <input
                                        id="returnBody"
                                        v-model="form.returnBody"
                                        type="text"
                                        placeholder="OK"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                                    />
                                </div>
                            </div>
                        </div>
                        <div v-if="form.actionType === 'share'" class="mt-4">
                            <label
                                for="actionShare"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Share Path</label
                            >
                            <input
                                id="actionShare"
                                v-model="form.actionShare"
                                type="text"
                                required
                                placeholder="/var/www/html"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                            />
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Path to static files directory
                            </p>
                        </div>
                    </div>
                    <div>
                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Description</label
                        >
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                        ></textarea>
                    </div>
                </div>
                <div v-show="activeTab === 'json'" class="space-y-6">
                    <div>
                        <label
                            for="configJson"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Configuration (JSON)</label
                        >
                        <textarea
                            id="configJson"
                            v-model="jsonConfig"
                            rows="15"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"
                        ></textarea>
                        <p
                            class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                        >
                            Enter configuration as JSON
                        </p>
                    </div>
                    <div>
                        <label
                            for="descriptionJson"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Description</label
                        >
                        <textarea
                            id="descriptionJson"
                            v-model="form.description"
                            rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                        ></textarea>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <router-link
                        to="/unit/routes"
                        class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200"
                        >Cancel</router-link
                    >
                    <button
                        type="submit"
                        :disabled="saving"
                        class="rounded-md bg-blue-600 dark:bg-blue-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 dark:focus-visible:outline-blue-500 transition-colors disabled:opacity-50"
                    >
                        {{ saving ? "Updating..." : "Update" }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useApi } from "../../composables/useApi";
import { useAlert } from "../../composables/useAlert";
const props = defineProps({
    name: String,
});
const { get, put } = useApi();
const { showAlert } = useAlert();
const route = useRoute();
const router = useRouter();
const loading = ref(true);
const saving = ref(false);
const activeTab = ref("form");
const name = ref(props.name || route.params.name);
const form = ref({
    matchHost: "",
    matchUri: "",
    actionType: "pass",
    actionPass: "",
    returnCode: 200,
    returnBody: "",
    actionShare: "",
    description: "",
});
const jsonConfig = ref("");
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
    if (form.value.actionType === "pass") {
        action.pass = form.value.actionPass;
    } else if (form.value.actionType === "return") {
        if (form.value.returnBody) {
            action.return = {
                code: form.value.returnCode,
                body: form.value.returnBody,
            };
        } else {
            action.return = form.value.returnCode;
        }
    } else if (form.value.actionType === "share") {
        action.share = form.value.actionShare;
    }
    config.action = action;
    jsonConfig.value = JSON.stringify(config, null, 2);
};
const loadRoute = async () => {
    loading.value = true;
    const result = await get(`/unit/routes/${name.value}`);
    if (result.success) {
        const routeData = result.data.data || result.data;
        const config = routeData.config || {};
        const match = config.match || {};
        const action = config.action || {};
        // Determine action type
        let actionType = "pass";
        let returnCode = 200;
        let returnBody = "";
        let actionPass = "";
        let actionShare = "";
        if (action.return !== undefined) {
            actionType = "return";
            if (typeof action.return === "object") {
                returnCode = action.return.code || 200;
                returnBody = action.return.body || "";
            } else {
                returnCode = action.return;
            }
        } else if (action.share !== undefined) {
            actionType = "share";
            actionShare = action.share;
        } else if (action.pass !== undefined) {
            actionType = "pass";
            actionPass = action.pass;
        }
        form.value = {
            matchHost: match.host || "",
            matchUri: match.uri || "",
            actionType: actionType,
            actionPass: actionPass,
            returnCode: returnCode,
            returnBody: returnBody,
            actionShare: actionShare,
            description: routeData.description || "",
        };
        jsonConfig.value = JSON.stringify(config, null, 2);
    } else {
        showAlert(result.error || "Failed to load route", "error");
        router.push("/unit/routes");
    }
    loading.value = false;
};
const submit = async () => {
    saving.value = true;
    try {
        let config;
        if (activeTab.value === "json") {
            try {
                config = JSON.parse(jsonConfig.value);
            } catch (_e) {
                showAlert("Invalid JSON format", "error");
                saving.value = false;
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
            if (form.value.actionType === "pass") {
                if (!form.value.actionPass) {
                    showAlert("Pass destination is required", "error");
                    saving.value = false;
                    return;
                }
                action.pass = form.value.actionPass;
            } else if (form.value.actionType === "return") {
                if (form.value.returnBody) {
                    action.return = {
                        code: form.value.returnCode,
                        body: form.value.returnBody,
                    };
                } else {
                    action.return = form.value.returnCode;
                }
            } else if (form.value.actionType === "share") {
                if (!form.value.actionShare) {
                    showAlert("Share path is required", "error");
                    saving.value = false;
                    return;
                }
                action.share = form.value.actionShare;
            }
            config.action = action;
        }
        const result = await put(`/unit/routes/${name.value}`, {
            config: JSON.stringify(config),
            description: form.value.description,
        });
        if (result.success) {
            showAlert("Route updated successfully", "success");
            router.push("/unit/routes");
        } else {
            showAlert(result.error || "Failed to update route", "error");
        }
    } catch (_error) {
        showAlert("Failed to update route", "error");
    } finally {
        saving.value = false;
    }
};
onMounted(loadRoute);
</script>
