<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1
                    class="text-3xl font-semibold text-gray-900 dark:text-white"
                >
                    Applications
                </h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Manage your Nginx Unit applications
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <router-link
                    to="/unit/applications/create"
                    class="block rounded-md bg-blue-600 dark:bg-blue-500 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors"
                >
                    Add application
                </router-link>
            </div>
        </div>
        <div v-if="loading" class="mt-8 text-center py-12">
            <div
                class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"
            ></div>
        </div>
        <div v-else class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div
                    class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"
                >
                    <div
                        class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 dark:ring-gray-700 sm:rounded-lg"
                    >
                        <table
                            class="min-w-full divide-y divide-gray-300 dark:divide-gray-700"
                        >
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th
                                        scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6"
                                    >
                                        Name
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white"
                                    >
                                        Type
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white"
                                    >
                                        Status
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white"
                                    >
                                        Description
                                    </th>
                                    <th
                                        scope="col"
                                        class="relative py-3.5 pl-3 pr-4 sm:pr-6"
                                    >
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800"
                            >
                                <tr
                                    v-for="app in applications"
                                    :key="app.name"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                                >
                                    <td
                                        class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6"
                                    >
                                        <code
                                            class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-800 dark:text-gray-200"
                                            >{{ app.name }}</code
                                        >
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400"
                                    >
                                        {{ app.type }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-3 py-4 text-sm"
                                    >
                                        <div class="flex items-center">
                                            <form
                                                class="flex items-center"
                                                @submit.prevent="
                                                    toggleApp(app.name)
                                                "
                                            >
                                                <button
                                                    type="submit"
                                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 dark:focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                                                    :class="
                                                        app.active
                                                            ? 'bg-green-600 dark:bg-green-500'
                                                            : 'bg-gray-200 dark:bg-gray-600'
                                                    "
                                                >
                                                    <span
                                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                                        :class="
                                                            app.active
                                                                ? 'translate-x-5'
                                                                : 'translate-x-0'
                                                        "
                                                    ></span>
                                                </button>
                                            </form>
                                            <span
                                                class="ml-2 text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                {{
                                                    app.active
                                                        ? "Active"
                                                        : "Inactive"
                                                }}
                                                <span
                                                    v-if="
                                                        app.active &&
                                                        app.is_active_in_unit
                                                    "
                                                    class="text-green-600 dark:text-green-400"
                                                    >✓</span
                                                >
                                                <span
                                                    v-else-if="app.active"
                                                    class="text-yellow-600 dark:text-yellow-400"
                                                    >⚠</span
                                                >
                                            </span>
                                        </div>
                                    </td>
                                    <td
                                        class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400"
                                    >
                                        {{ app.description || "-" }}
                                    </td>
                                    <td
                                        class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                                    >
                                        <div
                                            class="flex items-center justify-end gap-2"
                                        >
                                            <router-link
                                                :to="`/unit/applications/${app.name}`"
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300"
                                                >View</router-link
                                            >
                                            <router-link
                                                :to="`/unit/applications/${app.name}/edit`"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
                                                >Edit</router-link
                                            >
                                            <button
                                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                                                @click="deleteApp(app.name)"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="applications.length === 0">
                                    <td
                                        colspan="5"
                                        class="py-8 text-center text-sm text-gray-500 dark:text-gray-400"
                                    >
                                        <p>
                                            No applications found.
                                            <router-link
                                                to="/unit/applications/create"
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300"
                                                >Create one</router-link
                                            >
                                        </p>
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
import { onMounted } from "vue";
import { useApplications } from "../../composables/unit/useApplications";

const {
    applications,
    loading,
    loadApplications,
    toggleApplication,
    deleteApplication,
} = useApplications();

const toggleApp = async (name) => {
    await toggleApplication(name);
};

const deleteApp = async (name) => {
    if (!confirm("Are you sure you want to delete this application?")) return;
    try {
        await deleteApplication(name);
        await loadApplications();
    } catch (error) {
    }
};

onMounted(loadApplications);
</script>
