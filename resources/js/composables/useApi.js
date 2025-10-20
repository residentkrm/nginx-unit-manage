import axios from "axios";

const api = axios.create({
    baseURL: "/api",
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
    },
});

const extractErrorMessage = (error) => {
    if (!error.response) {
        return error.message || "Network error";
    }

    const { data, status } = error.response;

    // Laravel validation errors
    if (data?.errors && typeof data.errors === "object") {
        const firstError = Object.values(data.errors)[0];
        if (Array.isArray(firstError) && firstError.length > 0) {
            return firstError[0];
        }
    }

    // Laravel error messages
    if (data?.message) {
        return data.message;
    }

    // Laravel error field
    if (data?.error) {
        return data.error;
    }

    // Generic HTTP error
    if (status === 400) {
        return "Bad Request";
    }
    if (status === 404) {
        return "Not Found";
    }
    if (status === 422) {
        return "Validation Error";
    }
    if (status === 500) {
        return "Server Error";
    }

    return `Request failed with status code ${status}`;
};

export const useApi = () => {
    const get = async (url) => {
        try {
            const response = await api.get(url);
            return { success: true, data: response.data };
        } catch (error) {
            return {
                success: false,
                error: extractErrorMessage(error),
                status: error.response?.status,
            };
        }
    };

    const post = async (url, data) => {
        try {
            const response = await api.post(url, data);
            return { success: true, data: response.data };
        } catch (error) {
            return {
                success: false,
                error: extractErrorMessage(error),
                status: error.response?.status,
            };
        }
    };

    const put = async (url, data) => {
        try {
            const response = await api.put(url, data);
            return { success: true, data: response.data };
        } catch (error) {
            return {
                success: false,
                error: extractErrorMessage(error),
                status: error.response?.status,
            };
        }
    };

    const del = async (url) => {
        try {
            const response = await api.delete(url);
            return { success: true, data: response.data };
        } catch (error) {
            return {
                success: false,
                error: extractErrorMessage(error),
                status: error.response?.status,
            };
        }
    };

    return { get, post, put, delete: del };
};
