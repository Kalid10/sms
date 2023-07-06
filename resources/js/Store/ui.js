import { defineStore } from "pinia";

export const useUIStore = defineStore("ui", {
    state: () => ({
        isQuestionGenerationLoading: false,
        questionGenerationStatus: null,
    }),
    actions: {
        setQuestionGenerationLoading(value) {
            this.isQuestionGenerationLoading = value;
        },
        setQuestionGenerationStatus(value) {
            this.questionGenerationStatus = value;
        },
    },
});
