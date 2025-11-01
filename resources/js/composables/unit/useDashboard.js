import { ref } from "vue";
import { useApi } from "../useApi";
import { useAlert } from "../useAlert";

export const useDashboard = () => {
    const { get } = useApi();
    const { showAlert } = useAlert();
    const loading = ref(true);
    const stats = ref({
        applicationsCount: 0,
        applicationsActive: 0,
        listenersCount: 0,
        listenersActive: 0,
        routesCount: 0,
        routesActive: 0,
    });
    const connected = ref(false);
    const status = ref(null);

    const loadDashboard = async () => {
        loading.value = true;
        const result = await get("/unit/dashboard");
        if (result.success) {
            stats.value = result.data;
            connected.value = result.data.connected || false;
            status.value = result.data.status || null;
        } else {
            showAlert(result.error || "Failed to load dashboard", "error");
        }
        loading.value = false;
    };

    return {
        stats,
        connected,
        status,
        loading,
        loadDashboard,
    };
};
