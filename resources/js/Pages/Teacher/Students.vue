<template>
    <div
        class="flex min-h-screen w-full justify-between space-x-6 divide-x divide-gray-200 bg-gray-50"
    >
        <!--        Left Side-->
        <div class="flex w-8/12 flex-col space-y-5 py-5 pl-6 pr-5">
            <div
                class="flex w-full items-center justify-between space-x-4 rounded-lg bg-gradient-to-bl from-neutral-700 to-zinc-800 py-6 pl-4 text-gray-200 shadow-sm"
            >
                <Header title="My Students" class="w-4/12" />

                <div
                    class="flex h-full items-center justify-between divide-x divide-white lg:w-8/12"
                >
                    <div class="flex w-8/12 justify-center">
                        <SelectInput
                            v-model="selectedBatchSubject"
                            class="w-10/12 text-black"
                            :options="batchSubjectOptions"
                            rounded="rounded-full"
                        />
                    </div>
                    <div
                        class="flex w-4/12 flex-col items-center justify-center space-y-2 px-1"
                    >
                        <div
                            class="flex flex-col justify-center space-y-4 text-center text-4xl font-bold shadow-sm"
                        >
                            <div>{{ numberWithOrdinal(3) }}</div>
                            <span class="text-xs font-light">
                                Class Rank From Total 5
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div
                v-if="students"
                class="flex w-full justify-between rounded-lg bg-white shadow-sm"
            >
                <!--        Table-->
                <TableElement
                    :data="filteredStudents"
                    :title="studentListTitle"
                    :selectable="false"
                    :columns="config"
                    class="!!text-[0.5rem] border-none bg-red-400"
                    :footer="true"
                    header-style="!bg-black text-white !text-[0.65rem]"
                >
                    <template #filter>
                        <div
                            class="flex h-full w-full justify-between text-center"
                        >
                            <TextInput
                                v-model="searchText"
                                placeholder="Search"
                                class="w-5/12"
                            />

                            <div>
                                <div class="mb-1 text-[0.55rem] font-light">
                                    Homeroom Teacher
                                </div>
                                <div
                                    class="cursor-pointer text-xs font-semibold underline-offset-2 hover:underline"
                                >
                                    Mr.Bereket Gobeze
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #footer>
                        <Pagination :links="students.links" position="center" />
                    </template>
                </TableElement>
            </div>
        </div>

        <div class="flex w-4/12 flex-col space-y-10 bg-gray-50 py-5 pl-5">
            <div class="flex w-full justify-evenly">
                <div
                    class="flex w-5/12 flex-col justify-center space-y-4 rounded-lg bg-positive-100 py-5 text-center text-5xl font-bold text-white shadow-sm"
                >
                    <div>99%</div>
                    <span class="text-xs font-light">CLASS ATTENDANCE </span>
                </div>
                <div
                    class="flex w-5/12 flex-col justify-center space-y-4 rounded-lg bg-red-600 py-5 text-center text-5xl font-bold text-white shadow-sm"
                >
                    <div>D</div>
                    <span class="text-xs font-light">CLASS CONDUCT </span>
                </div>
            </div>
            <div class="w-11/12 rounded-lg bg-white py-2 shadow-sm">
                <StudentsList
                    progress-type="up"
                    title="Best Progressing Students"
                    :icon="ArrowTrendingUpIcon"
                    :progressing-students="progressingStudents"
                />
            </div>

            <div class="w-11/12 rounded-lg bg-white py-2 shadow-sm">
                <StudentsList
                    progress-type="down"
                    title="Students Falling Behind"
                    :icon="ArrowTrendingDownIcon"
                    :progressing-students="progressingStudents"
                />
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import TextInput from "@/Components/TextInput.vue";
import debounce from "lodash/debounce";
import SelectInput from "@/Components/SelectInput.vue";
import Header from "@/Views/Teacher/Header.vue";
import {
    ArrowTrendingDownIcon,
    ArrowTrendingUpIcon,
} from "@heroicons/vue/24/outline";
import StudentsList from "@/Views/Teacher/Batches/PerformanceHighlights/StudentsList.vue";
import Pagination from "@/Components/Pagination.vue";
import { numberWithOrdinal } from "../../utils";

const students = computed(() => usePage().props.students);

const batchSubjects = usePage().props.batch_subjects;
const searchText = ref(usePage().props.search);

const batchSubject = computed(() => {
    return usePage().props.batch_subject;
});
const filteredStudents = computed(() => {
    return students.value.data.map((student) => {
        return {
            name: student.student.user.name,
            attendance: student.attendance_percentage + "%",
            grade: "50/60",
            rank: student.student_id,
            id: student.student_id,
            conduct: "C",
        };
    });
});

const selectedBatchSubject = ref(batchSubject.value.id);

const batchSubjectOptions = computed(() => {
    return batchSubjects.map((batchSubject) => {
        return {
            value: batchSubject.id,
            label:
                batchSubject.batch.level.name +
                " " +
                batchSubject.batch.section +
                " " +
                batchSubject.subject.full_name,
        };
    });
});

const studentListTitle = computed(() => {
    return `${batchSubject.value.batch.level.name} ${batchSubject.value.batch.section} - ${batchSubject.value.subject.full_name} Students List`;
});

const config = [
    {
        key: "name",
        name: "Name",
        align: "center",
        class: "h-12  !text-[0.6rem]",
        link: "/teacher/students/{id}",
    },
    {
        key: "attendance",
        name: "Attendance%",
        align: "center",
        class: "h-12  !text-[0.65rem]",
    },
    {
        key: "grade",
        name: "Grade",
        align: "center",
        class: "h-12  !text-[0.65rem]",
    },
    {
        key: "rank",
        name: "Rank",
        align: "center",
        class: "h-12  !text-[0.65rem]",
    },
    {
        key: "conduct",
        name: "Conduct",
        align: "center",
        class: "h-12  !text-[0.65rem]",
    },
];

const updateBatchInfo = () => {
    router.visit(
        "/teacher/students?batch_subject_id=" +
            selectedBatchSubject.value +
            "&search=" +
            searchText.value,
        {
            preserveState: true,
        }
    );
};

const debouncedUpdate = debounce(updateBatchInfo, 300);

watch(searchText, () => {
    debouncedUpdate();
});

watch(selectedBatchSubject, () => {
    searchText.value = "";
    debouncedUpdate();
});

const progressingStudents = [
    {
        name: "Kalid Abdu",
        progress: 80,
        attendance: 99,
    },
    {
        name: "Biniyam Lemma",
        progress: 85,
        attendance: 100,
    },
    {
        name: "Yoseph Seboka",
        progress: 85,
        attendance: 98,
    },
];
</script>
<style scoped></style>
