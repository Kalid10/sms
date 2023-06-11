<template>
    <!--    <div class="my-5 w-10/12">-->
    <!--        <div class="my-5 mb-8 flex flex-row items-center gap-2">-->
    <!--            <QueueListIcon class="h-6 w-6"/>-->
    <!--            <h1 class="text-2xl font-semibold text-gray-900">-->
    <!--                Home-->
    <!--            </h1>-->
    <!--            <h3 class="text-sm text-gray-500">({{ schoolYear.name }})</h3>-->
    <!--        </div>-->

    <!--        <div class="flex w-full flex-col gap-2 md:flex-row">-->
    <!--            <div class="mr-2 w-full rounded border shadow-sm lg:w-2/3">-->
    <!--                <h1 class="p-2">Overview</h1>-->
    <!--                <Graph/>-->

    <!--                <Statistics-->
    <!--                    :teachers-count="teachersCount"-->
    <!--                    :students-count="studentsCount"-->
    <!--                    :subjects-count="subjectCount"-->
    <!--                    :admins-count="adminsCount"-->
    <!--                />-->
    <!--            </div>-->

    <!--            <div class="rounded border shadow-sm lg:w-2/3">-->
    <!--                <LevelIndex/>-->
    <!--            </div>-->
    <!--        </div>-->

    <!--        <Management :user-roles="userRoles"/>-->

    <!--        <AdminsTable-->
    <!--            :admins="admins"-->
    <!--            :config-admin="configAdmin"-->
    <!--            class="border"-->
    <!--        />-->
    <!--    </div>-->
    <div
        class="flex min-h-screen w-full flex-col gap-3 space-y-6 bg-gray-50 py-8 px-5 lg:space-y-5 lg:px-1 2xl:px-10"
    >
        <div class="flex w-full justify-between space-x-5">
            <WelcomeHeader />
            <div class="w-5/12">
                <TextInput
                    placeholder="Search Student"
                    class-style="h-8 bg-white border-gray-300 text-black placeholder:text-gray-500 placeholder:text-xs focus:border-none focus:ring-zinc-500"
                />
            </div>
        </div>

        <div class="flex w-full justify-between">
            <div class="flex w-6/12 flex-col space-y-5">
                <Announcements />

                <StudentsTable />
            </div>
            <div class="flex w-5/12 flex-col space-y-5">
                <div class="flex w-full space-x-5">
                    <AbsentTeachers />
                    <div class="w-9/12">
                        <SchoolSchedule />
                    </div>
                </div>
                <Levels />
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed } from "vue";
import moment from "moment/moment";
import { usePage } from "@inertiajs/vue3";
import WelcomeHeader from "@/Views/WelcomeHeader.vue";
import Levels from "@/Pages/Admin/Levels/Index.vue";
import Announcements from "@/Pages/Admin/Announcements/Index.vue";
import SchoolSchedule from "@/Views/Admin/SchoolSchedule/Index.vue";
import AbsentTeachers from "@/Views/Admin/AbsentTeachers.vue";
import TextInput from "@/Components/TextInput.vue";

import StudentsTable from "@/Pages/Admin/Students/StudentsTable.vue";

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

const schoolYear = computed(() => usePage().props.school_year);

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
