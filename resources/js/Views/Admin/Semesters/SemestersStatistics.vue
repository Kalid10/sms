<template>
    <div
        class="flex flex-col gap-2 md:grid md:grid-cols-2 md:grid-rows-2 lg:flex lg:flex-row"
    >
        <StatisticsCard
            :data="semestersCount"
            :title="$t('semestersStatistics.semestersCountTitle')"
            :subtitle="$t('semestersStatistics.semestersCountSubtitle')"
        >
            <template #icon>
                <StarIcon />
            </template>
        </StatisticsCard>
        <StatisticsCard
            :data="schoolYearsCount"
            :title="$t('semestersStatistics.schoolYearsCountTitle')"
            :subtitle="$t('semestersStatistics.schoolYearsCountSubtitle')"
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
            :title="$t('semestersStatistics.activeSchoolYearSemestersCountTitle')"
            :subtitle="$t('semestersStatistics.activeSchoolYearSemestersCountSubtitle')"
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
import PrimaryButton from "@/Components/PrimaryButton.vue";

const semestersCount = useSemesterStore().semestersCount;
const schoolYearsCount = useSemesterStore().schoolYearsCount;
const activeSchoolYearSemestersCount =
    useSemesterStore().activeSchoolYearSemestersCount;
</script>

<style scoped></style>
