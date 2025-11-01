import { ref } from "vue";
import { useApi } from "../useApi";
import { useAlert } from "../useAlert";

export const useRoutes = () => {
    const { get, post, put, delete: del } = useApi();
    const { showAlert } = useAlert();
    const loading = ref(false);
    const routes = ref([]);

    const loadRoutes = async () => {
        loading.value = true;
        const result = await get("/unit/routes");
        if (result.success) {
            routes.value = result.data.data || [];
        } else {
            showAlert(result.error || "Failed to load routes", "error");
        }
        loading.value = false;
    };

    const createRoute = async (data) => {
        const result = await post("/unit/routes", data);
        if (result.success) {
            showAlert("Route created successfully", "success");
            return result.data;
        } else {
            showAlert(result.error || "Failed to create route", "error");
            throw new Error(result.error);
        }
    };

    const updateRoute = async (name, data) => {
        const result = await put(`/unit/routes/${name}`, data);
        if (result.success) {
            showAlert("Route updated successfully", "success");
            return result.data;
        } else {
            showAlert(result.error || "Failed to update route", "error");
            throw new Error(result.error);
        }
    };

    const deleteRoute = async (name) => {
        const result = await del(`/unit/routes/${name}`);
        if (result.success) {
            showAlert("Route deleted successfully", "success");
        } else {
            showAlert(result.error || "Failed to delete route", "error");
            throw new Error(result.error);
        }
    };

    const toggleRoute = async (name) => {
        const result = await post(`/unit/routes/${name}/toggle`, {});
        if (result.success) {
            showAlert("Route toggled successfully", "success");
            await loadRoutes();
        } else {
            showAlert(result.error || "Failed to toggle route", "error");
        }
    };

    const getRoute = async (name) => {
        const result = await get(`/unit/routes/${name}`);
        if (result.success) {
            return result.data.data || result.data;
        } else {
            showAlert(result.error || "Failed to load route", "error");
            throw new Error(result.error);
        }
    };

    return {
        routes,
        loading,
        loadRoutes,
        createRoute,
        updateRoute,
        deleteRoute,
        toggleRoute,
        getRoute,
    };
};
