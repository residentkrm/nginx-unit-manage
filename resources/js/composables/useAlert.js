import { ref } from 'vue';

const alertMessage = ref(null);
const alertType = ref('success');

export const useAlert = () => {
    const showAlert = (message, type = 'success') => {
        alertMessage.value = message;
        alertType.value = type;
    };

    const hideAlert = () => {
        alertMessage.value = null;
    };

    return {
        alertMessage,
        alertType,
        showAlert,
        hideAlert,
    };
};
