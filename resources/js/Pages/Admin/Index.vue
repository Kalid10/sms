<template>
    <div
        class="flex min-h-screen w-full flex-col space-y-5 bg-gray-50 p-5 lg:px-1 2xl:px-10"
    >
        <div class="flex h-fit w-full justify-between">
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

            <div class="flex w-4/12 flex-col items-center space-y-8">
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

                <Summary />
            </div>
        </div>
        <SchoolSchedule />
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
import SchoolSchedule from "@/Views/Admin/SchoolSchedule/Home.vue";
import Summary from "@/Views/Admin/Summary.vue";

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
