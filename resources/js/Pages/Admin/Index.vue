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
                <Combobox v-model="selectedStudents">
                    <ComboboxInput
                        class="w-full rounded-3xl placeholder:text-sm"
                        placeholder="Search Student"
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
import { computed, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import WelcomeHeader from "@/Views/WelcomeHeader.vue";
import Announcements from "@/Views/Announcements/Index.vue";
import {
    Combobox,
    ComboboxInput,
    ComboboxOption,
    ComboboxOptions,
} from "@headlessui/vue";
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
</script>
