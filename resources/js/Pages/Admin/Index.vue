<template>
    <div
        class="flex min-h-screen w-full justify-between bg-gray-50 p-5 lg:px-1 2xl:px-10"
    >
        <div class="flex w-7/12 flex-col space-y-8">
            <WelcomeHeader />

            <TabElement v-model:active="activeTab" :tabs="tabs">
                <template #announcements>
                    <Announcements
                        url="/admin/announcements"
                        class-style="h-fit w-full space-y-2 rounded-lg bg-white py-2 px-2 shadow-sm"
                    />
                </template>

                <template #flags>
                    <Flags view="admin" title="Recent Flags" />
                </template>
            </TabElement>
        </div>

        <div class="flex min-h-screen w-4/12 flex-col items-center space-y-8">
            <div class="relative w-11/12">
                <div class="flex flex-col">
                    <input
                        v-model="query"
                        class="mb-2 h-12 rounded-md border border-gray-300 px-3 py-2"
                        type="text"
                        placeholder="Search For A Student"
                    />
                    <div
                        v-if="filteredStudents.length > 0"
                        class="absolute mt-14 w-full"
                    >
                        <div
                            v-for="(student, index) in filteredStudents"
                            v-show="query.length > 0"
                            :key="student"
                            class="cursor-pointer py-2"
                            :class="
                                index % 2 === 0
                                    ? 'bg-gray-100 px-10 hover:bg-gray-300'
                                    : 'bg-white px-10 hover:bg-gray-300'
                            "
                            @click="goToStudentPage(student)"
                        >
                            <p class="px-3 py-2">
                                {{ student.user.name }}
                            </p>
                        </div>
                    </div>
                    <div
                        v-else
                        class="flex flex-col items-center justify-center"
                    >
                        <p v-if="!!query" class="text-sm text-zinc-800">
                            No students found
                        </p>
                    </div>
                </div>
            </div>
            <AbsenteeStats class="w-11/12" />

            <div class="flex w-11/12 flex-col space-y-6">
                <SummaryItem
                    class-style="bg-sky-100 text-black"
                    icon-style="bg-sky-500/20 text-white"
                    title="Chats"
                    value="5 Unread Messages"
                    :icon="PaperAirplaneIcon"
                    url="/admin/chat"
                />
                <SummaryItem
                    class-style="bg-fuchsia-100 text-black"
                    icon-style="bg-fuchsia-500/20 text-white"
                    title="Students"
                    value="Register, Review, explore and More"
                    :icon="UserGroupIcon"
                    url="/admin/students"
                />
                <SummaryItem
                    class-style="bg-orange-100 text-black"
                    icon-style="bg-orange-500/20 text-white"
                    title="Teachers"
                    value="Register, evaluate, explore and more"
                    :icon="UserCircleIcon"
                    url="/admin/teachers"
                />

                <SummaryItem
                    class-style="bg-zinc-100 text-black"
                    icon-style="bg-zinc-500/20 text-white"
                    title="Admins"
                    value="Register, Review, explore and More"
                    :icon="UsersIcon"
                    url="/admin/admins "
                />
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import WelcomeHeader from "@/Views/WelcomeHeader.vue";
import Announcements from "@/Views/Announcements/Index.vue";
import { debounce } from "lodash";
import AbsenteeStats from "@/Views/Admin/Absentee/AbsenteeStats.vue";
import Flags from "@/Views/Flag/Index.vue";
import TabElement from "@/Components/TabElement.vue";
import SummaryItem from "@/Views/Teacher/Views/SummaryItem.vue";
import {
    PaperAirplaneIcon,
    UserCircleIcon,
    UserGroupIcon,
    UsersIcon,
} from "@heroicons/vue/24/solid";

const tabs = ["Announcements", "Flags"];
const activeTab = ref("Announcements");

const students = computed(() => usePage().props.students);

const schoolYear = computed(() => usePage().props.school_year);

const selectedStudent = ref([]);

const selectStudent = (student) => {
    selectedStudent.value = student;
};

const query = ref("");

watch(query, () => {
    fetchStudent();
});

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
                  student.user.name
                      .toLowerCase()
                      .includes(query.value.toLowerCase())
              )
            : [];
    }
});

const goToStudentPage = (student) => {
    router.get(
        `/admin/teachers/students/${student.id}`,
        {},
        {
            only: ["student"],
            preserveState: true,
            replace: true,
        }
    );
};
</script>
