<template>
    <div class="flex h-full w-full flex-col space-y-2">
        <div
            class="flex h-full w-full justify-between space-x-6 divide-x divide-gray-100"
        >
            <!--        Left Side-->
            <div
                class="flex w-8/12 flex-col space-y-5"
                :class="isTeacher() ? 'py-5 pl-5' : 'py-3 pl-3 pr-10'"
            >
                <Header
                    :title="isTeacher() ? 'My Classes' : 'Classes'"
                    :select-input-options="batchSubjectOptions"
                    :selected-input="batchSubject.id"
                    @change="updateBatchInfo"
                />

                <div class="bg w-full px-3 py-4">
                    <div class="w-full pr-5">
                        <BatchPerformance />
                    </div>
                </div>

                <div class="flex w-full justify-between space-x-10">
                    <div class="w-6/12">
                        <StudentsTable
                            :title="tableTitle"
                            :table-model-value="batchSubject.id"
                            @search="updateBatchInfo"
                            @click="fetchStudent"
                        />
                    </div>
                    <div class="flex h-full w-5/12 flex-col space-y-6">
                        <SummaryItem
                            class-style="bg-orange-100 text-black"
                            icon-style="bg-orange-500/20 text-white"
                            :title="'Assessments'"
                            value="10 /10 Completed"
                            :icon="ClipboardIcon"
                        />
                        <SummaryItem
                            class-style="bg-fuchsia-100 text-black"
                            icon-style="bg-fuchsia-500/20 text-white"
                            :title="'LessonPlans'"
                            value="10 /10 Completed"
                            :icon="CalendarIcon"
                        />
                        <SummaryItem
                            class-style="bg-red-50 text-black"
                            icon-style="bg-red-500/20 text-white"
                            :title="'Announcements'"
                            value="10 Announcements Today"
                            :icon="ChatBubbleBottomCenterIcon"
                        />

                        <div
                            v-if="assessments?.length"
                            class="mt-5 w-full rounded-lg bg-white pt-2 shadow-sm"
                        >
                            <Assessment
                                class=""
                                title="Recent Assessments"
                                :assessments="assessments"
                                view="class"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!--        Right side-->
            <div
                class="flex w-4/12 flex-col px-3 pl-5"
                :class="isTeacher() ? 'py-5' : ''"
            >
                <CurrentClass view="absentee" />

                <div
                    class="flex h-full w-full flex-col items-center space-y-6 px-3 py-5"
                >
                    <div class="w-full">
                        <StudentsList
                            progress-type="up"
                            title="Top Students"
                            :icon="ArrowTrendingUpIcon"
                            :students="topStudents"
                        />
                    </div>

                    <div class="w-full">
                        <StudentsList
                            progress-type="down"
                            title="Students Falling Behind"
                            :icon="ArrowTrendingDownIcon"
                            :students="bottomStudents"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import Header from "@/Views/Teacher/Views/Header.vue";
import { isTeacher } from "@/utils";
import CurrentClass from "@/Views/Teacher/Views/Batches/CurrentClass.vue";
import SummaryItem from "@/Views/Teacher/Views/SummaryItem.vue";
import {
    CalendarIcon,
    ChatBubbleBottomCenterIcon,
    ClipboardIcon,
} from "@heroicons/vue/24/solid";
import {
    ArrowTrendingDownIcon,
    ArrowTrendingUpIcon,
} from "@heroicons/vue/24/outline";
import StudentsList from "@/Views/Teacher/Views/Batches/PerformanceHighlights/StudentsList.vue";
import BatchPerformance from "@/Views/Teacher/Views/Batches/BatchPerformance/Index.vue";
import StudentsTable from "@/Views/Teacher/Views/StudentsTable.vue";
import Assessment from "@/Views/Teacher/Views/Home/Assessments.vue";

const schedule = usePage().props.schedule;
const batchSubjects = usePage().props.batch_subjects;

const students = computed(() => {
    return usePage().props.students;
});

const assessments = computed(() => {
    return usePage().props.assessments;
});
const batchSubject = computed(() => {
    return usePage().props.batch_subject;
});

const topStudents = computed(() => usePage().props.top_students);
const bottomStudents = computed(() => usePage().props.bottom_students);

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

const selectedBatchSubject = ref(batchSubject.value.id);
const updateBatchInfo = (batchSubjectId, search) => {
    if (batchSubjectId !== null) selectedBatchSubject.value = batchSubjectId;
    router.visit(
        "/teacher/class?batch_subject_id=" +
            selectedBatchSubject.value +
            "&search=" +
            search,
        {
            preserveState: true,
        }
    );
};

const tableTitle = computed(() => {
    return `${batchSubject.value.batch.level.name} ${batchSubject.value.batch.section} - ${batchSubject.value.subject.full_name}`;
});

function fetchStudent(studentId) {
    router.get(
        "/teacher/students/" +
            studentId +
            "?batch_subject_id=" +
            selectedBatchSubject.value
    );
}
</script>

<style scoped></style>
