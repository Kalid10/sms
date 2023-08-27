import { defineStore } from "pinia";

export const useUIStore = defineStore("ui", {
    state: () => ({
        isLoading: false,
        loadingMessage: null,
        responseStatus: null,
        responseMessage: null,
    }),
    actions: {
        setLoading(value) {
            this.isLoading = value;
        },
        setResponseStatus(value) {
            this.responseStatus = value;
        },
        setResponseMessage(value) {
            this.responseMessage = value;
        },
        setLoadingMessage(value) {
            this.loadingMessage = value;
        },
    },
});
