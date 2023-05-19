<template>
    <div class="grid w-full grid-cols-12 grid-rows-6 p-5">
        <div class="col-span-12 row-span-1 h-fit">
            <div class="flex h-full w-full justify-between">
                <!--        Header-->
                <div
                    class="flex h-fit flex-col items-start justify-between space-y-1 rounded-lg 2xl:space-y-1.5"
                >
                    <span class="text-xl font-semibold lg:text-4xl">
                        My Classes</span
                    >
                    <span class="text-xs font-light">{{
                        moment().format(" dddd MMMM D YYYY")
                    }}</span>
                </div>

                <div
                    class="flex h-16 w-fit flex-col items-center justify-evenly rounded-sm bg-emerald-500 px-3 text-center text-xs text-white shadow-md shadow-emerald-100"
                >
                    <div class="text-[0.65rem] font-light">Current Class</div>
                    <div class="break-words font-medium">
                        Currently (
                        <span
                            class="cursor-pointer font-bold underline-offset-2 hover:underline"
                            >Period 4</span
                        >
                        ) attending
                        <span
                            class="cursor-pointer underline-offset-2 hover:underline"
                            >Biology</span
                        >
                        with
                        <div
                            class="cursor-pointer font-bold underline-offset-2 hover:underline"
                        >
                            Mr.Todd Boehly
                        </div>
                    </div>
                </div>
                <div class="w-3/12">
                    <SelectInput
                        v-model="selectedBatchSubject"
                        :options="batchSubjectOptions"
                        rounded="rounded-full"
                    />
                </div>
            </div>
        </div>
        <div
            class="col-start-1 row-start-2 row-end-7 h-fit"
            :class="
                isSidebarOpenOnXlDevice ? 'col-span-8 pr-4' : 'col-span-9 pr-10'
            "
        >
            <PerformanceHighlight />
        </div>
        <div
            class="row-start-2 row-end-7 h-fit"
            :class="
                isSidebarOpenOnXlDevice
                    ? 'col-span-4 col-start-9 pl-5'
                    : 'col-span-3 col-start-10 mr-3'
            "
        >
            <LessonPlan
                title="Recent LessonPlans"
                :props-lesson-plans="lessonPlans"
                view="class"
            />
        </div>
        <div class="col-span-6 row-start-7 -mt-4 pr-5">
            <!--        Table-->
            <TableElement
                :data="filteredStudents"
                :title="studentListTitle"
                :selectable="false"
                :columns="config"
                class="!!text-[0.5rem] border-none bg-red-400"
                :footer="false"
                header-style="!bg-black text-white !text-[0.65rem]"
            >
                <template #filter>
                    <div class="flex h-full w-full justify-between text-center">
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
            </TableElement>
        </div>

        <div
            class="mt-12 flex flex-col items-center"
            :class="
                isSidebarOpenOnXlDevice
                    ? 'col-span-6 col-start-7  ml-10'
                    : 'col-span-5 col-start-8'
            "
        >
            <!--            <div-->
            <!--                class="flex h-2/6 items-center justify-center rounded-sm bg-black text-white shadow-md"-->
            <!--            ></div>-->
            <Assessment
                title="Recent Assessments"
                :assessments="assessments"
                view="class"
            />
        </div>
        <div class="col-span-12 mt-7 min-h-screen">
            <div class="h-4/6">
                <StudentSemesterSchedule class="h-full" />
            </div>
        </div>
    </div>
</template>

<script setup>
import moment from "moment/moment";
import PerformanceHighlight from "@/Views/Teacher/Batches/PerformanceHighlights/Index.vue";
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import TableElement from "@/Components/TableElement.vue";
import TextInput from "@/Components/TextInput.vue";
import debounce from "lodash/debounce";
import LessonPlan from "@/Views/Teacher/Home/LessonPlans.vue";
import Assessment from "@/Views/Teacher/Home/Assessments.vue";
import StudentSemesterSchedule from "@/Views/Students/StudentSemesterSchedule.vue";
import { isSidebarOpenOnXlDevice } from "@/utils";
import SelectInput from "@/Components/SelectInput.vue";

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
            grade: "50/60",
            rank: student.id,
            id: student.id,
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
