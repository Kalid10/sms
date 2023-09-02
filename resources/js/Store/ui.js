import { defineStore } from "pinia";

export const useUIStore = defineStore("ui", {
    state: () => ({
        isLoading: false,
        loadingMessage: null,
        responseStatus: null,
        responseMessage: null,
    }),
    actions: {
        setLoading(value, message = null) {
            this.isLoading = value;
            this.loadingMessage = message;
        },
        setResponse(status, message = null) {
            this.responseStatus = status;
            this.responseMessage = message;
        },
    },
});
