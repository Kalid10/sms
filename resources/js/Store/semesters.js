import { defineStore } from "pinia";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

export const useSemesterStore = defineStore("semesters", () => {
    const semesters = computed(() => {
        return usePage().props.semesters;
    });
    // Semesters count
    const semestersCount = computed(() => {
        return usePage().props.semestersCount;
    });
    // School years count
    const schoolYearsCount = computed(() => {
        return usePage().props.schoolYearsCount;
    });
    // Active School year semesters count
    const activeSchoolYearSemestersCount = computed(() => {
        return usePage().props.activeSchoolYearSemestersCount;
    });

    return {
        semesters,
        semestersCount,
        schoolYearsCount,
        activeSchoolYearSemestersCount,
    };
});
