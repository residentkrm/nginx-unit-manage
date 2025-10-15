<template>
    <div 
        v-if="message"
        class="mb-4 px-4 py-3 rounded-lg shadow-sm"
        :class="type === 'success' 
            ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200'
            : 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200'"
    >
        <div class="flex">
            <div class="flex-shrink-0">
                <SuccessIcon v-if="type === 'success'" />
                <ErrorIcon v-else />
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium">{{ message }}</p>
            </div>
            <div class="ml-3 flex-shrink-0">
                <button @click="$emit('close')" class="inline-flex rounded-md focus:outline-none">
                    <CloseIcon />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import SuccessIcon from './icons/SuccessIcon.vue';
import ErrorIcon from './icons/ErrorIcon.vue';
import CloseIcon from './icons/CloseIcon.vue';

defineProps({
    message: String,
    type: {
        type: String,
        default: 'success'
    }
});

defineEmits(['close']);
</script>
