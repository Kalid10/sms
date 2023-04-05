import { defineStore } from "pinia";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

export const useSubjectStore = defineStore("subjects", () => {
    const subjects = computed(() => {
        return usePage().props.subjects.data;
    });

    return {
        subjects,
    };
});
