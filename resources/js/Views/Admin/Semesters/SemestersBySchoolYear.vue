<template>
    <div class="container mx-auto flex w-full flex-col items-center gap-3">
        <Heading v-if="selectedSemesters.length === 0">
            {{ selectedSemesters.length }} {{ $t('semestersBySchoolYear.semesters')}}</Heading
        >
        <Heading v-else
            >{{
                new Date(
                    selectedSemesters[0].school_year.start_date
                ).getFullYear()
            }}
            {{ $t('semestersBySchoolYear.semesters')}}
            </Heading
        >
        <SemestersList :semesters="selectedSemesters.slice().reverse()" />
    </div>
</template>

<script setup>
import { computed } from "vue";
import Heading from "@/Components/Heading.vue";
import SemestersList from "@/Views/Admin/Semesters/SemestersList.vue";
import { useSemesterStore } from "@/Store/semesters";


const props = defineProps({
    schoolYearId: {
        type: Number,
        required: true,
    },
});

const semesters = useSemesterStore().semesters;
const selectedSemesters = computed(() =>
    semesters.filter(
        (semester) => semester.school_year_id === props.schoolYearId
    )
);
</script>

<style scoped></style>
