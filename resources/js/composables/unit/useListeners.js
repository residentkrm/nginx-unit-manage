import { ref } from "vue";
import { useApi } from "../useApi";
import { useAlert } from "../useAlert";

export const useListeners = () => {
    const { get, post, put, delete: del } = useApi();
    const { showAlert } = useAlert();
    const loading = ref(false);
    const listeners = ref([]);

    const loadListeners = async () => {
        loading.value = true;
        const result = await get("/unit/listeners");
        if (result.success) {
            listeners.value = result.data.data || [];
        } else {
            showAlert(result.error || "Failed to load listeners", "error");
        }
        loading.value = false;
    };

    const createListener = async (data) => {
        const result = await post("/unit/listeners", data);
        if (result.success) {
            showAlert("Listener created successfully", "success");
            return result.data;
        } else {
            showAlert(result.error || "Failed to create listener", "error");
            throw new Error(result.error);
        }
    };

    const updateListener = async (address, data) => {
        const encodedAddress = encodeURIComponent(address);
        const result = await put(`/unit/listeners/${encodedAddress}`, data);
        if (result.success) {
            showAlert("Listener updated successfully", "success");
            return result.data;
        } else {
            showAlert(result.error || "Failed to update listener", "error");
            throw new Error(result.error);
        }
    };

    const deleteListener = async (address) => {
        const encodedAddress = encodeURIComponent(address);
        const result = await del(`/unit/listeners/${encodedAddress}`);
        if (result.success) {
            showAlert("Listener deleted successfully", "success");
        } else {
            showAlert(result.error || "Failed to delete listener", "error");
            throw new Error(result.error);
        }
    };

    const toggleListener = async (address) => {
        const encodedAddress = encodeURIComponent(address);
        const result = await post(
            `/unit/listeners/${encodedAddress}/toggle`,
            {},
        );
        if (result.success) {
            showAlert("Listener toggled successfully", "success");
            await loadListeners();
        } else {
            showAlert(result.error || "Failed to toggle listener", "error");
        }
    };

    const getListener = async (address) => {
        const encodedAddress = encodeURIComponent(address);
        const result = await get(`/unit/listeners/${encodedAddress}`);
        if (result.success) {
            return result.data.data || result.data;
        } else {
            showAlert(result.error || "Failed to load listener", "error");
            throw new Error(result.error);
        }
    };

    return {
        listeners,
        loading,
        loadListeners,
        createListener,
        updateListener,
        deleteListener,
        toggleListener,
        getListener,
    };
};
