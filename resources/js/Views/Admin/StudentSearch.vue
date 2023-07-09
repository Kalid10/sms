<template>
    <div ref="container" class="relative w-full">
        <div class="flex flex-col">
            <TextInput
                v-model="query"
                class-style="rounded-2xl w-full placeholder:text-xs focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:border-transparent"
                type="text"
                placeholder="Search For A Student"
            />
            <div
                v-if="filteredStudents.length > 0 && showStudents"
                class="absolute mt-10 w-full rounded-lg bg-white p-2 shadow-sm"
            >
                <div
                    v-for="(student, index) in filteredStudents"
                    v-show="query.length > 0"
                    :key="student"
                    class="cursor-pointer py-2 text-xs hover:rounded-lg hover:bg-zinc-700 hover:text-white"
                    :class="
                        index % 2 === 0
                            ? 'bg-gray-50 px-10 hover:bg-gray-300'
                            : 'bg-white px-10 hover:bg-gray-300'
                    "
                    @click="goToStudentPage(student)"
                >
                    <p class="px-3 py-2">
                        {{ student.user.name }} - Grade
                        {{ student.current_batch[0].level.name }}-{{
                            student.current_batch[0].section
                        }}
                    </p>
                </div>
            </div>
            <div
                v-else-if="showLoading"
                class="mt-2 rounded-lg bg-white py-8 shadow-sm"
            >
                <Loading color="secondary" />
            </div>
            <div
                v-else-if="query.length > 0 && showStudents"
                class="mt-1 flex flex-col items-center justify-center rounded-lg border border-zinc-100 bg-white py-8 shadow-sm"
            >
                <p v-if="!!query" class="text-sm text-zinc-800">
                    No students found with the name "{{ query }}"
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import TextInput from "@/Components/TextInput.vue";
import { computed, ref, watch } from "vue";
import { debounce } from "lodash";
import { router, usePage } from "@inertiajs/vue3";
import { onClickOutside } from "@vueuse/core";
import Loading from "@/Components/Loading.vue";

const students = computed(() => usePage().props.students);
const query = ref("");
const showStudents = ref(false);
const showLoading = ref(false);

const container = ref(null);

onClickOutside(container, () => {
    showStudents.value = false;
});

watch(query, () => {
    showStudents.value = true;
    fetchStudent();
});

const fetchStudent = debounce(async function () {
    showLoading.value = true;
    router.get(
        "/admin/",
        {
            search: query.value,
        },
        {
            only: ["students"],
            preserveState: true,
            replace: true,
            onFinish: () => {
                showLoading.value = false;
            },
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

<style scoped></style>
