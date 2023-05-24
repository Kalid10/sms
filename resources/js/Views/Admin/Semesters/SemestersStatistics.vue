<template>
    <div
        class="flex flex-col gap-2 md:grid md:grid-cols-2 md:grid-rows-2 lg:flex lg:flex-row"
    >
        <StatisticsCard
            :data="semestersCount"
            subtitle="Semesters tracked on the system"
            title="Semesters"
        >
            <template #icon>
                <StarIcon />
            </template>
        </StatisticsCard>
        <StatisticsCard
            :data="schoolYearsCount"
            subtitle="School Years registered on the system"
            title="School Years"
        >
            <template #icon>
                <div class="relative">
                    <StarIcon class="absolute translate-x-2 fill-white" />
                    <StarIcon class="absolute -translate-x-2 fill-white" />
                    <StarIcon
                        class="absolute translate-y-[0.1rem] fill-white"
                    />
                </div>
            </template>
        </StatisticsCard>
        <StatisticsCard
            :data="activeSchoolYearSemestersCount"
            subtitle="Semesters currently in progress"
            title="Current(Active) School year Semesters"
        >
            <template #icon>
                <StarIconSolid class="fill-yellow-400 stroke-yellow-800" />
            </template>
        </StatisticsCard>
        <StatisticsCard
            data="15"
            subtitle="Yet another statistics about semesters"
            title="Additional"
        />
    </div>
</template>

<script setup>
import StatisticsCard from "@/Views/Admin/StatisticsCard.vue";
import { StarIcon } from "@heroicons/vue/24/outline";
import { StarIcon as StarIconSolid } from "@heroicons/vue/24/solid";
import { useSemesterStore } from "@/Store/semesters";

const semestersCount = useSemesterStore().semestersCount;
const schoolYearsCount = useSemesterStore().schoolYearsCount;
const activeSchoolYearSemestersCount =
    useSemesterStore().activeSchoolYearSemestersCount;
</script>

<style scoped></style>
