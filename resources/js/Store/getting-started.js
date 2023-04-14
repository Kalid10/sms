import { defineStore } from "pinia";
import { computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";

export const useGettingStartedStore = defineStore("gettingStarted", () => {
    const levels = computed(() => {
        return usePage().props.levels;
    });

    function fetchSchoolSchedule() {
        router.reload({
            only: ["school_schedule"],
        });
    }

    return {
        levels,
        fetchSchoolSchedule,
    };
});
