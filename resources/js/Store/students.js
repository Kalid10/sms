import {defineStore} from "pinia";
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";

export const useStudentStore = defineStore("students", () => {

    const student = computed(() => usePage().props.student)
    const activeBatch = computed(() => usePage().props.active_batch)
    const schedule = computed(() => usePage().props.schedule)
    const periods = computed(() => usePage().props.periods)

    return {
        student,
        activeBatch,
        schedule,
        periods
    };
});
