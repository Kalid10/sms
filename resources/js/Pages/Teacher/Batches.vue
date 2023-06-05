<template>
    <div class="flex w-full flex-col space-y-2">
        <div class="flex w-full justify-between space-x-6 bg-gray-50">
            <!--        Left Side-->
            <div class="flex w-8/12 flex-col space-y-2 py-5 pl-5">
                <div
                    class="flex w-full items-center justify-between space-x-4 rounded-lg bg-gradient-to-bl from-neutral-700 to-zinc-800 py-6 pl-4 text-gray-200 shadow-sm"
                >
                    <Header title="My Classes" class="w-4/12" />

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
                        <div class="w-4/12 px-1">
                            <CurrentClass />
                        </div>
                    </div>
                </div>
                <div class="pt-4">
                    <PerformanceHighlight />
                </div>
                <div class="flex w-full justify-between rounded-lg shadow-sm">
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
                            <LinkCell
                                href="/teacher/students"
                                value="View All Students"
                                class="w-full text-end"
                            />
                        </template>
                    </TableElement>
                </div>
            </div>

            <!--        Right side-->
            <div
                class="flex w-4/12 flex-col items-center space-y-6 border-l bg-gray-50 px-3 py-5 pl-5"
            >
                <!--                <div class="w-11/12">-->
                <!--                    <SchoolSchedule />-->
                <!--                </div>-->
                <div class="w-full rounded-lg bg-white p-2 shadow-sm">
                    <Assessment
                        class=""
                        title="Recent Assessments"
                        :assessments="assessments"
                        view="class"
                    />
                </div>
                <div class="rounded-lg bg-white px-3 pt-2 shadow-sm">
                    <LessonPlan
                        title="Recent LessonPlans"
                        :props-lesson-plans="lessonPlans"
                        view="class"
                    />
                </div>
            </div>
        </div>
        <!--        <div class="flex h-screen justify-center">-->
        <!--            <div-->
        <!--                class="h-5/6 w-11/12 rounded-md bg-gradient-to-bl from-zinc-700 to-neutral-500 p-8 text-white"-->
        <!--            >-->
        <!--                <StudentSemesterSchedule class="h-full" />-->
        <!--            </div>-->
        <!--        </div>-->
    </div>
</template>

<script setup>
import PerformanceHighlight from "@/Views/Teacher/Batches/PerformanceHighlights/Index.vue";
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import TableElement from "@/Components/TableElement.vue";
import TextInput from "@/Components/TextInput.vue";
import debounce from "lodash/debounce";
import LessonPlan from "@/Views/Teacher/Home/LessonPlans.vue";
import Assessment from "@/Views/Teacher/Home/Assessments.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Header from "@/Views/Teacher/Header.vue";
import CurrentClass from "@/Views/Teacher/Batches/CurrentClass.vue";
import LinkCell from "@/Components/LinkCell.vue";

const schedule = usePage().props.schedule;
const batchSubjects = usePage().props.batch_subjects;
const searchText = ref(usePage().props.search);

const students = computed(() => {
    return usePage().props.students;
});

const lessonPlans = computed(() => {
    return usePage().props.lesson_plans;
});

const assessments = computed(() => {
    return usePage().props.assessments;
});
const batchSubject = computed(() => {
    return usePage().props.batch_subject;
});
const filteredStudents = computed(() => {
    return students.value.map((student) => {
        return {
            name: student.user.name,
            attendance: student.attendance_percentage + "%",
            grade: student.quarterly_grade
                ? student.quarterly_grade.score.toFixed(1)
                : "-",
            rank: student.batch_subject_rank ?? "-",
            id: student.id,
            conduct: student.conduct ?? "-",
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
        link:
            "/teacher/students/{id}" +
            "?batch_subject_id=" +
            selectedBatchSubject.value,
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
        "/teacher/class?batch_subject_id=" +
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
</script>

<style scoped></style>
