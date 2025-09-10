import { inject } from 'vue';

export function useAlert() {
    const showAlert = inject('showAlert');
    
    if (!showAlert) {
        console.warn('useAlert: showAlert not provided. Make sure Layout component is mounted.');
        return {
            showAlert: () => {}
        };
    }
    
    return { showAlert };
}

