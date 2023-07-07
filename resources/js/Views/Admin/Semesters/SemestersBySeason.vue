<template>
    <div class="container mx-auto flex w-full flex-col items-center gap-3">
        <Heading>{{ seasons[season] }} {{ $t('semestersBySeason.semesters')}}</Heading>
        <SemestersList
            :semesters="
                semesters
                    .filter((semester) =>
                        semester.name
                            .toLowerCase()
                            .includes(season.toLowerCase())
                    )
                    .slice()
                    .reverse()
            "
        />
    </div>
</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import SemestersList from "@/Views/Admin/Semesters/SemestersList.vue";
import { useSemesterStore } from "@/Store/semesters";
import {useI18n} from "vue-i18n";
const {t} = useI18n()

defineProps({
    season: {
        type: String,
        required: true,
    },
});

const semesters = useSemesterStore().semesters;

const seasons = {
    1: t('semestersBySeason.first'),
    2: t('semestersBySeason.second'),
    3:t('semestersBySeason.third'),
};
</script>

<style scoped></style>
