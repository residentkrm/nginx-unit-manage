<template>
    <div>
        <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <router-link to="/unit/dashboard" class="text-xl font-bold text-gray-900 dark:text-white">
                                Nginx Unit Manager
                            </router-link>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <router-link 
                                to="/unit/dashboard" 
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors"
                                :class="$route.name === 'dashboard' ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200'"
                            >
                                Dashboard
                            </router-link>
                            <router-link 
                                to="/unit/applications" 
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors"
                                :class="$route.name?.startsWith('applications') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200'"
                            >
                                Applications
                            </router-link>
                            <router-link 
                                to="/unit/listeners" 
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors"
                                :class="$route.name?.startsWith('listeners') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200'"
                            >
                                Listeners
                            </router-link>
                            <router-link 
                                to="/unit/routes" 
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors"
                                :class="$route.name?.startsWith('routes') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200'"
                            >
                                Routes
                            </router-link>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <button 
                            @click="toggleTheme" 
                            class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" 
                            aria-label="Toggle theme"
                        >
                            <svg v-if="isDark" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                            </svg>
                            <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Alert v-if="alertMessage" :message="alertMessage" :type="alertType" @close="alertMessage = null" />
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, provide } from 'vue';
import Alert from './Alert.vue';

const isDark = ref(false);

const toggleTheme = () => {
    const html = document.getElementById('html-root');
    isDark.value = !isDark.value;
    
    if (isDark.value) {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
};

onMounted(() => {
    const html = document.getElementById('html-root');
    isDark.value = html.classList.contains('dark');
});

const alertMessage = ref(null);
const alertType = ref('success');

const showAlert = (message, type = 'success') => {
    alertMessage.value = message;
    alertType.value = type;
};

// Provide alert method for child components
provide('showAlert', showAlert);
</script>

