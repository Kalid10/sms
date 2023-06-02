<template>
    <div class="my-5 w-10/12">
        <div class="my-5 mb-8 flex flex-row items-center gap-2">
            <QueueListIcon class="h-6 w-6"/>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                Dashboard
            </h1>
        </div>
        <Statistics
            :teachers-count="teachersCount"
            :students-count="studentsCount"
            :subjects-count="subjectCount"
            :admins-count="adminsCount"
        />

        <div class="flex w-full flex-col gap-2 md:flex-row">
            <div class="mr-2 w-full rounded border shadow-sm lg:w-2/3">
                <h1 class="p-2">Overview</h1>
                <Graph/>
            </div>

            <div class="rounded border shadow-sm lg:w-1/3">
                <LevelsTable :config="configLevels" :levels="levels"/>
            </div>
        </div>

        <Management :user-roles="userRoles"/>

        <AdminsTable
            :admins="admins"
            :config-admin="configAdmin"
            class="border"
        />
    </div>
</template>
<script setup>
import {computed} from "vue";
import moment from "moment/moment";
import Statistics from "@/Views/Admin/Analytics/Statistics.vue";
import Management from "@/Views/Admin/Management.vue";
import Graph from "@/Views/Admin/Analytics/Graph.vue";
import {usePage} from "@inertiajs/vue3";
import LevelsTable from "@/Views/Admin/Levels/LevelsTable.vue";
import {QueueListIcon} from "@heroicons/vue/24/solid";
import AdminsTable from "@/Views/Admin/AdminsTable.vue";

const teachersCount = computed(() => usePage().props.teachers_count);

const studentsCount = computed(() => usePage().props.students_count);

const subjectCount = computed(() => usePage().props.subjects_count);

const adminsCount = computed(() => usePage().props.admins_count);

const userRoles = computed(() => usePage().props.user_roles);

const admins = computed(() => {
    return usePage().props.admins.map((admin) => {
        return {
            id: admin.id,
            name: admin.name,
            email: admin.email,
            type: admin.type,
            // Role is not yet implemented it should be looped through the roles array
            // role: admin.roles,
            updated_at: moment(admin.updated_at).fromNow(),
        };
    });
});

const levels = computed(() => {
    return usePage().props.levels.map((level) => {
        return {
            batches: level.batches,
            id: level.id,
            level: {
                id: level.id,
                name: level.name,
                category: level["level_category"],
            },
            updated_at: moment(level.updated_at).fromNow(),
            created_at: level.created_at,
        };
    });
});

const configLevels = [
    {
        name: "Level Category",
        key: "level",
        type: "custom",
    },
    {
        name: "Sections",
        key: "batches",
        type: "custom",
    },
    {
        name: "Updated at",
        key: "updated_at",
        type: "custom",
    },
];

const configAdmin = [
    {
        name: "Name",
        key: "name",
        type: "custom",
    },
    {
        name: "Email",
        key: "email",
        type: "custom",
    },
    {
        name: "Role",
        key: "role",
        type: "custom",
    },
    {
        name: "Type",
        key: "type",
        type: "custom",
    },
    {
        name: "Updated at",
        key: "updated_at",
        type: "custom",
    },
];

const config = [
    {
        key: "name",
        type: "custom",
    },
    {
        key: "statistics",
        type: "custom",
    },
    {
        key: "last_updated",
        type: "custom",
    },
];
</script>
