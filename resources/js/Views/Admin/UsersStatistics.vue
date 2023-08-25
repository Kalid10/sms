<template>
    <div
        class="my-2 flex w-full flex-col gap-5 md:grid md:grid-cols-2 md:grid-rows-2 lg:flex lg:flex-row"
    >
        <StatisticsCard
            :data="adminsCount"
            :subtitle="$t('userStatistics.adminStatSubtitle')"
            :title="$t('userStatistics.adminsTitle')"
        >
            <template #icon>
                <UserCircleIcon />
            </template>
        </StatisticsCard>
        <StatisticsCard
            :data="studentsCount"
            :subtitle="$t('userStatistics.studentsStatSubtitle')"
            :title="$t('userStatistics.studentsStatTitle')"
        >
            <template #icon>
                <UserIcon />
            </template>
        </StatisticsCard>

        <StatisticsCard
            :data="guardiansCount"
            :subtitle="$t('userStatistics.guardianStatSubtitle')"
            :title="$t('userStatistics.guardianStatTitle')"
        >
            <template #icon>
                <UserIcon />
            </template>
        </StatisticsCard>
        <StatisticsCard
            :data="teachersCount"
            :subtitle="$t('userStatistics.teacherStatSubtitle')"
            :title="$t('userStatistics.teacherStatTitle')"
        >
            <template #icon>
                <UsersIcon />
            </template>
        </StatisticsCard>
        <StatisticsCard
            :data="newUsersCount"
            :subtitle="$t('userStatistics.newUserStatSubtitle')"
            :title="$t('userStatistics.newUsersStatTitle')"
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
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const studentsCount = computed(() => usePage().props.students_count);

const teachersCount = computed(() => usePage().props.teachers_count);

const adminsCount = computed(() => usePage().props.admins_count);

const newUsersCount = computed(() => usePage().props.new_users_count);

const guardiansCount = computed(() => usePage().props.guardians_count);
</script>

<style scoped></style>
