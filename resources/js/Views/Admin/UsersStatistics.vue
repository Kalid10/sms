<template>
    <div
        class="my-2 flex w-full flex-col gap-5 md:grid md:grid-cols-2 md:grid-rows-2 lg:flex lg:flex-row"
    >
        <StatisticsCard
            :data="adminsCount"
            subtitle="Admins registered in the system"
            title="Admins"
        >
            <template #icon>
                <UserCircleIcon />
            </template>
        </StatisticsCard>
        <StatisticsCard
            :data="studentsCount"
            subtitle="Students registered into the school (all-time)"
            title="Students"
        >
            <template #icon>
                <UserIcon />
            </template>
        </StatisticsCard>
        <StatisticsCard
            :data="teachersCount"
            subtitle="Teachers registered into the school (all-time)"
            title="Teachers"
        >
            <template #icon>
                <UsersIcon />
            </template>
        </StatisticsCard>
        <StatisticsCard
            :data="newUsersCount"
            subtitle="New Users (last 30 days)"
            title="New Users"
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
</script>

<style scoped></style>
