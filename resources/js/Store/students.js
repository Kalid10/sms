import {defineStore} from "pinia";
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";

export const useStudentStore = defineStore("students", () => {

    const student = computed(() => usePage().props.student)
    const activeBatch = computed(() => usePage().props.active_batch)

    return {
        student,
        activeBatch
    };
});
