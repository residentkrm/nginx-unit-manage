import { ref } from "vue";
import { useApi } from "../useApi";
import { useAlert } from "../useAlert";

export const useApplications = () => {
    const { get, post, put, delete: del } = useApi();
    const { showAlert } = useAlert();
    const loading = ref(false);
    const applications = ref([]);

    const loadApplications = async () => {
        loading.value = true;
        const result = await get("/unit/applications");
        if (result.success) {
            applications.value = result.data.data || [];
        } else {
            showAlert(result.error || "Failed to load applications", "error");
        }
        loading.value = false;
    };

    const createApplication = async (data) => {
        const result = await post("/unit/applications", data);
        if (result.success) {
            showAlert("Application created successfully", "success");
            return result.data;
        } else {
            showAlert(result.error || "Failed to create application", "error");
            throw new Error(result.error);
        }
    };

    const updateApplication = async (name, data) => {
        const result = await put(`/unit/applications/${name}`, data);
        if (result.success) {
            showAlert("Application updated successfully", "success");
            return result.data;
        } else {
            showAlert(result.error || "Failed to update application", "error");
            throw new Error(result.error);
        }
    };

    const deleteApplication = async (name) => {
        const result = await del(`/unit/applications/${name}`);
        if (result.success) {
            showAlert("Application deleted successfully", "success");
        } else {
            showAlert(result.error || "Failed to delete application", "error");
            throw new Error(result.error);
        }
    };

    const toggleApplication = async (name) => {
        const result = await post(`/unit/applications/${name}/toggle`, {});
        if (result.success) {
            showAlert("Application toggled successfully", "success");
            await loadApplications();
        } else {
            showAlert(result.error || "Failed to toggle application", "error");
        }
    };

    const getApplication = async (name) => {
        const result = await get(`/unit/applications/${name}`);
        if (result.success) {
            return result.data.data || result.data;
        } else {
            showAlert(result.error || "Failed to load application", "error");
            throw new Error(result.error);
        }
    };

    return {
        applications,
        loading,
        loadApplications,
        createApplication,
        updateApplication,
        deleteApplication,
        toggleApplication,
        getApplication,
    };
};
