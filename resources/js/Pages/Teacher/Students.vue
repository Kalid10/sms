<template>
    <div class="grid w-full grid-cols-12 grid-rows-6 p-5">
        <div class="col-span-8 row-span-1 h-fit">
            <!--        Header-->
            <div
                class="flex h-fit flex-col items-start justify-between space-y-1 rounded-lg 2xl:space-y-1.5"
            >
                <span class="text-xl font-semibold lg:text-4xl">
                    My Students</span
                >
                <span class="text-xs font-light">{{
                    moment().format(" dddd MMMM D YYYY")
                }}</span>
            </div>
        </div>
        <div class="col-span-8 col-start-1 row-start-2 row-end-7 h-fit pr-4">
            <PerformanceHighlight />
        </div>
        <div class="col-span-4 col-start-9 row-start-1 row-end-7 h-fit px-6">
            <LessonPlan
                title="11A Recent LessonPlans"
                :props-lesson-plans="lessonPlans"
            />
        </div>
        <div class="col-span-6 row-start-7 -mt-6 pr-5">
            <!--        Table-->
            <TableElement
                :data="filteredStudents"
                :title="studentListTitle"
                :selectable="false"
                :columns="config"
                class="border-none"
                :footer="false"
                header-style="!bg-black text-white"
            >
                <template #filter>
                    <TextInput
                        v-model="searchText"
                        placeholder="Search"
                        class="w-5/12"
                    />
                </template>
            </TableElement>
        </div>

        <div class="col-span-5 col-start-8 mt-12 pr-5">
            <Assessment
                title="11A Recent Assessments"
                :assessments="assessments"
            />
        </div>
        <div class="col-span-12 row-span-4 mt-7 h-96">
            <StudentSemesterSchedule class="h-full" />
        </div>
    </div>
</template>

<script setup>
import moment from "moment/moment";
import PerformanceHighlight from "@/Views/Teacher/Students/PerformanceHighlights/Index.vue";
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import TableElement from "@/Components/TableElement.vue";
import TextInput from "@/Components/TextInput.vue";
import debounce from "lodash/debounce";
import LessonPlan from "@/Views/Teacher/Home/LessonPlans.vue";
import Assessment from "@/Views/Teacher/Home/Assessments.vue";
import StudentSemesterSchedule from "@/Views/Students/StudentSemesterSchedule.vue";

const students = computed(() => {
    return usePage().props.students;
});
const batchSubject = usePage().props.batch_subject;
const searchText = ref(usePage().props.search);
const lessonPlans = usePage().props.lesson_plans;
const assessments = usePage().props.assessments;
const schedule = usePage().props.schedule;

const filteredStudents = computed(() => {
    return students.value.map((student) => {
        return {
            name: student.user.name,
            attendance: student.attendance_percentage + "%",
            grade: "50/60",
            rank: student.id,
            id: student.id,
            conduct: "C",
        };
    });
});

const studentListTitle = computed(() => {
    return `${batchSubject.batch.level.name} ${batchSubject.batch.section} - ${batchSubject.subject.full_name} Students List`;
});

const config = [
    {
        key: "name",
        name: "Name",
        align: "center",
        class: "h-12 text-xs",
        link: "/teacher/students/{id}",
    },
    {
        key: "attendance",
        name: "Attendance%",
        align: "center",
        class: "h-12 text-xs",
    },
    {
        key: "grade",
        name: "Grade",
        align: "center",
        class: "h-12 text-xs",
    },
    {
        key: "rank",
        name: "Rank",
        align: "center",
        class: "font-medium text-[0.65rem]",
    },
    {
        key: "conduct",
        name: "Conduct",
        align: "center",
    },
];

const updateStudents = () => {
    router.visit(
        "/teacher/students?batch_subject_id=" +
            batchSubject.id +
            "&search=" +
            searchText.value,
        {
            only: ["students", "batch_subject"],
            preserveState: true,
        }
    );
};

const debouncedSearch = debounce(updateStudents, 300);

watch(searchText, () => {
    debouncedSearch();
});
</script>

<style scoped></style>
