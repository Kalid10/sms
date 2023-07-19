<template>
    <div
        class="flex flex-col gap-2 md:grid md:grid-cols-2 md:grid-rows-2 lg:flex lg:flex-row"
    >
        <StatisticsCard
            subtitle="students with best performance"
            title="Over achievers"
            class="cursor-pointer hover:bg-brand-100 md:col-span-1 md:row-span-1 md:w-full"
            icon
        >
            <h3 class="flex flex-col">
                <slot>
                    <div class="flex w-full">
                        <div class="w-10/12">
                            <div class="flex items-center gap-5">
                                <h1 class="text-xs text-brand-text-400">
                                    Nahom Legesse
                                </h1>
                                <transition name="fade" mode="out-in">
                                    <span
                                        :key="currentText"
                                        class="text-sm font-semibold"
                                    >
                                        {{ currentText }} 🔥
                                    </span>
                                </transition>
                            </div>
                            <div class="flex items-center gap-5">
                                <h1 class="text-xs text-brand-text-400">
                                    Selam Derash
                                </h1>
                                <transition name="fade" mode="out-in">
                                    <span
                                        :key="secondBest"
                                        class="text-sm font-semibold"
                                    >
                                        {{ secondBest }} 💪
                                    </span>
                                </transition>
                            </div>
                        </div>
                        <div class="w-2/12">
                            <ArrowTrendingUpIcon
                                class="h-14 w-24 fill-green-50 stroke-green-500"
                            />
                        </div>
                    </div>
                </slot>
            </h3>
            <template #icon>
                <ArrowUpCircleIcon />
            </template>
        </StatisticsCard>
        <StatisticsCard
            subtitle="students who are not performing well"
            title="Under achievers"
            class="cursor-pointer hover:bg-brand-100 md:col-span-1 md:row-span-1 md:w-full"
            icon
        >
            <h3 class="flex flex-col">
                <slot>
                    <div class="flex w-full">
                        <div class="w-10/12">
                            <div class="flex items-center gap-5">
                                <h1 class="text-xs text-brand-text-400">
                                    Fesha Tosksh
                                </h1>
                                <span class="text-sm font-semibold">
                                    Rank: 21/22
                                </span>
                            </div>
                            <div class="flex items-center gap-5">
                                <h1 class="text-xs text-brand-text-400">
                                    Dawit lalo
                                </h1>
                                <span class="text-sm font-semibold">
                                    Rank: 22/22
                                </span>
                            </div>
                        </div>
                        <div class="w-2/12">
                            <ArrowTrendingDownIcon
                                class="h-14 w-24 fill-red-50 stroke-red-500"
                            />
                        </div>
                    </div>
                </slot>
            </h3>
            <template #icon>
                <ArrowDownCircleIcon />
            </template>
        </StatisticsCard>
    </div>
    <div
        class="flex flex-col gap-2 md:grid md:grid-cols-2 md:grid-rows-2 lg:flex lg:flex-row"
    >
        <StatisticsCard
            :data="level['batches_count'].toString()"
            :subtitle="`Sections in ${activeSchoolYear}`"
            title="Sections"
        >
            <template #icon>
                <UserCircleIcon />
            </template>
        </StatisticsCard>
        <StatisticsCard
            :data="studentsCount.toString()"
            :subtitle="`Students in ${parseLevel(
                level.name
            )} for ${activeSchoolYear.split(' ').slice(2)}`"
            title="Students"
        >
            <template #icon>
                <UserIcon />
            </template>
        </StatisticsCard>
        <StatisticsCard
            :data="teachers.size.toString()"
            :subtitle="`Teachers in ${parseLevel(
                level.name
            )} for ${activeSchoolYear.split(' ').slice(2)}`"
            title="Teachers"
        >
            <template #icon>
                <UsersIcon />
            </template>
        </StatisticsCard>
        <StatisticsCard
            :data="subjects.size.toString()"
            :subtitle="`Subjects given for ${parseLevel(level.name)} students`"
            title="Subjects"
        >
            <template #icon>
                <UserPlusIcon />
            </template>
        </StatisticsCard>
    </div>
</template>

<script setup>
import StatisticsCard from "@/Views/Admin/StatisticsCard.vue";
import {
    UserCircleIcon,
    UserIcon,
    UserPlusIcon,
    UsersIcon,
} from "@heroicons/vue/24/outline";
import { computed, onMounted, onUnmounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import { parseLevel } from "@/utils.js";
import {
    ArrowDownCircleIcon,
    ArrowTrendingDownIcon,
    ArrowTrendingUpIcon,
    ArrowUpCircleIcon,
} from "@heroicons/vue/24/solid";

const texts = ["Rank: 3/4", "4th grade best", "clean attendance", "Conduct: A"];
const secondText = [
    "Rank: 2/4",
    "4th grade second best",
    "clean attendance",
    "Conduct: B",
];
const currentText = ref(texts[0]);
const secondBest = ref(secondText[0]);

onMounted(() => {
    let i = 0;
    setInterval(() => {
        i = (i + 1) % texts.length;
        currentText.value = texts[i];
    }, 3000);

    onUnmounted(() => {
        clearInterval(i);
    });
    let j = 0;
    setInterval(() => {
        j = (j + 1) % secondText.length;
        secondBest.value = secondText[j];
    }, 4000);
    onUnmounted(() => {
        clearInterval(j);
    });
});

const level = computed(() => usePage().props.level);
const activeSchoolYear = computed(
    () => usePage().props.level.batches[0]?.school_year.name
);
const studentsCount = computed(() =>
    usePage().props.level.batches.reduce((acc, batch) => {
        return acc + batch["students_count"];
    }, 0)
);
const teachers = computed(() =>
    usePage().props.level.batches.reduce((acc, batch) => {
        batch.subjects
            .map((subject) => subject.teacher_id)
            .forEach(acc.add, acc);
        return acc;
    }, new Set())
);
const subjects = computed(() =>
    usePage().props.level.batches.reduce((acc, batch) => {
        batch.subjects
            .map((subject) => subject.subject_id)
            .forEach(acc.add, acc);
        return acc;
    }, new Set())
);
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0.5;
}
</style>
