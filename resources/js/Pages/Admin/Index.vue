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
            <div class="relative w-4/12">
                <Combobox v-model="selectedStudents">
                    <ComboboxInput
                        class="w-full rounded-lg"
                        @change="handleSearch"
                    />
                    <transition
                        enter-active-class="transition duration-100 ease-out"
                        enter-from-class="transform scale-95 opacity-0"
                        enter-to-class="transform scale-100 opacity-100"
                        leave-active-class="transition duration-75 ease-out"
                        leave-from-class="transform scale-100 opacity-100"
                        leave-to-class="transform scale-95 opacity-0"
                    >
                        <ComboboxOptions
                            class="absolute z-50 w-full cursor-pointer border-2"
                        >
                            <ComboboxOption
                                v-for="(student, index) in filteredStudents"
                                :key="student.id"
                                :value="student"
                                class="py-2"
                                :class="
                                    index % 2 === 0
                                        ? 'bg-gray-100 px-10 hover:bg-gray-300'
                                        : 'bg-white px-10 hover:bg-gray-300'
                                "
                            >
                                {{ student.name }}
                            </ComboboxOption>
                        </ComboboxOptions>
                    </transition>
                </Combobox>
            </div>
        </div>

        <div class="flex w-full justify-between">
            <div class="flex w-6/12 flex-col space-y-5">
                <Announcements
                    url="/admin/announcements"
                    class-style="h-fit w-full space-y-2 rounded-lg bg-white py-2 px-2 shadow-sm"
                />
            </div>
            <div class="flex w-5/12 flex-col space-y-5">
                <div class="flex w-full space-x-5">
                    <div
                        class="flex h-full w-4/12 flex-col justify-evenly space-y-4"
                    >
                        <AbsentTeachers value="4" title="Absent Teachers" />
                        <AbsentTeachers value="15" title="Absent Students" />
                    </div>
                    <div class="w-9/12">
                        <SchoolSchedule />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed, ref } from "vue";
import moment from "moment/moment";
import { router, usePage } from "@inertiajs/vue3";
import WelcomeHeader from "@/Views/WelcomeHeader.vue";
import Announcements from "@/Views/Announcements/Index.vue";
import SchoolSchedule from "@/Views/Admin/SchoolSchedule/Index.vue";
import AbsentTeachers from "@/Views/Admin/Absentee.vue";
import {
    Combobox,
    ComboboxInput,
    ComboboxOption,
    ComboboxOptions,
} from "@headlessui/vue";
import { debounce } from "lodash";

const students = computed(() => usePage().props.students);

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

const selectedStudents = ref([]);

const query = ref("");

async function handleSearch(event) {
    query.value = event.target.value;
    if (query.value && query.value.trim() !== "") {
        // Fetch data from the backend.
        const response = await fetchStudent();
        if (response) {
            // Update selectedStudents.
            selectedStudents.value = await response.json();
        }
    } else {
        // If the search query is empty, clear the selectedStudents.
        selectedStudents.value = [];
    }
}

const fetchStudent = debounce(async function () {
    router.get(
        "/admin/",
        {
            search: query.value,
        },
        {
            only: ["students"],
            preserveState: true,
            replace: true,
        }
    );
}, 500);

const filteredStudents = computed(() => {
    if (query.value === "") {
        return students.value || [];
    } else {
        return students.value
            ? students.value.filter((student) =>
                  student.name.toLowerCase().includes(query.value.toLowerCase())
              )
            : [];
    }
});
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
