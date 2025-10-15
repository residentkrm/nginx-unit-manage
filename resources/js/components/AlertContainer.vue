<template>
    <Alert 
        v-if="alertMessage" 
        :message="alertMessage" 
        :type="alertType" 
        @close="hideAlert" 
    />
</template>

<script setup>
import { useAlert } from '../composables/useAlert';
import Alert from './Alert.vue';

const { alertMessage, alertType, hideAlert } = useAlert();
</script>

