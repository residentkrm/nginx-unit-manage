<template>
    <div>
        <nav
            class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 transition-colors duration-200"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <router-link
                                to="/unit/dashboard"
                                class="text-xl font-bold text-gray-900 dark:text-white"
                            >
                                Nginx Unit Manager
                            </router-link>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <router-link
                                to="/unit/dashboard"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors"
                                :class="
                                    $route.name === 'dashboard'
                                        ? 'border-blue-500 text-gray-900 dark:text-white'
                                        : 'border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200'
                                "
                            >
                                Dashboard
                            </router-link>
                            <router-link
                                to="/unit/applications"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors"
                                :class="
                                    $route.name?.startsWith('applications')
                                        ? 'border-blue-500 text-gray-900 dark:text-white'
                                        : 'border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200'
                                "
                            >
                                Applications
                            </router-link>
                            <router-link
                                to="/unit/listeners"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors"
                                :class="
                                    $route.name?.startsWith('listeners')
                                        ? 'border-blue-500 text-gray-900 dark:text-white'
                                        : 'border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200'
                                "
                            >
                                Listeners
                            </router-link>
                            <router-link
                                to="/unit/routes"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors"
                                :class="
                                    $route.name?.startsWith('routes')
                                        ? 'border-blue-500 text-gray-900 dark:text-white'
                                        : 'border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200'
                                "
                            >
                                Routes
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Alert
                    v-if="alertMessage"
                    :message="alertMessage"
                    :type="alertType"
                    @close="hideAlert"
                />
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
import { useAlert } from "../composables/useAlert";
import Alert from "./Alert.vue";

const { alertMessage, alertType, hideAlert } = useAlert();
</script>
