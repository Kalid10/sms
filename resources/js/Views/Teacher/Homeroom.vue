<template>
    <div class="flex h-screen w-full flex-col space-y-2 bg-gray-50">
        <div
            v-if="!selectedHomeroom"
            class="flex h-96 flex-col items-center justify-center space-y-5"
        >
            <div class="">404</div>
            <div class="w-6/12 text-center text-3xl font-semibold text-black">
                No homeroom classes found!
            </div>

            <SecondaryButton
                v-if="isAdmin()"
                title="Assign Homeroom"
                class="!rounded-2xl bg-zinc-800 text-white"
                @click="showAssignModal = true"
            />
        </div>

        <div
            v-else
            class="flex min-h-screen w-full justify-between space-x-6 divide-x divide-gray-200 bg-gray-50"
        >
            <!--        Left Side-->
            <div class="flex w-8/12 flex-col space-y-4 py-5 pl-5">
                <Header
                    title="Homeroom Classes"
                    :select-input-options="homeroomOptions"
                    :selected-input="selectedHomeroom"
                    @change="updateBatchInfo"
                />

                <Statistics class="!bg-white" />

                <StudentsTable
                    :show-homeroom-detail="false"
                    @search="updateBatchInfo"
                    @click="getStudentDetails"
                />
            </div>

            <!--        Right side-->
            <div class="flex w-4/12 flex-col space-y-6 bg-gray-50 py-5 pl-5">
                <div class="flex w-full justify-evenly">
                    <div
                        class="flex w-5/12 flex-col justify-center space-y-4 rounded-lg bg-positive-100 py-5 text-center text-5xl font-bold text-white shadow-sm"
                    >
                        <div v-if="grade">{{ grade?.attendance ?? "-" }}%</div>
                        <div>-</div>
                        <span class="text-xs font-light"
                            >CLASS ATTENDANCE
                        </span>
                    </div>
                    <div
                        :class="{
                            'bg-positive-100 text-white':
                                grade?.conduct === 'A',
                            'bg-yellow-400': grade?.conduct === 'B',
                            'bg-amber-300': grade?.conduct === 'C',
                            'bg-red-500 text-white': grade?.conduct === 'D',
                            'bg-negative-100 text-white':
                                grade?.conduct === 'F',
                            'bg-zinc-700 text-white': !grade?.conduct,
                        }"
                        class="flex w-5/12 flex-col justify-center space-y-4 rounded-lg py-5 text-center text-5xl font-bold shadow-sm"
                    >
                        <div>{{ grade?.conduct ?? "-" }}</div>
                        <span class="text-xs font-light">CLASS CONDUCT </span>
                    </div>
                </div>
                <div class="w-11/12 rounded-lg bg-white py-2 shadow-sm">
                    <StudentsList
                        progress-type="up"
                        title="Top Students"
                        :icon="ArrowTrendingUpIcon"
                        :students="topStudents"
                    />
                </div>

                <div class="w-11/12 rounded-lg bg-white shadow-sm">
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
    <Modal v-model:view="showModal">
        <StudentGradeDetail
            :student-grade="studentDetail.quarterly_grade"
            :student-name="studentDetail.student"
        />
    </Modal>

    <Modal v-model:view="showAssignModal">
        <AssignHomeroom />
    </Modal>
</template>
<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import Header from "@/Views/Teacher/Views/Header.vue";
import StudentsTable from "@/Views/Teacher/Views/StudentsTable.vue";
import {
    ArrowTrendingDownIcon,
    ArrowTrendingUpIcon,
} from "@heroicons/vue/24/outline";
import StudentsList from "@/Views/Teacher/Views/Batches/PerformanceHighlights/StudentsList.vue";
import Statistics from "@/Views/Teacher/Views/Batches/BatchPerformance/Index.vue";
import Modal from "@/Components/Modal.vue";
import StudentGradeDetail from "@/Views/Teacher/Views/Homeroom/StudentGradeDetail.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import AssignHomeroom from "@/Views/Teacher/Views/Homeroom/AssignHomeroom.vue";
import { isAdmin } from "@/utils";

const homeroomClasses = computed(() => usePage().props.homeroom_classes);
const selectedHomeroom = ref(usePage().props.filters.batch_id);
const students = computed(() => usePage().props.students);
const topStudents = computed(() => usePage().props.top_students);
const bottomStudents = computed(() => usePage().props.bottom_students);
const grade = computed(() => usePage().props.grade);
const showModal = ref(false);
const showAssignModal = ref(false);
const studentDetail = ref();
const homeroomOptions = computed(() => {
    return homeroomClasses.value.map((homeroom) => {
        return {
            value: homeroom.batch.id,
            label: homeroom.batch.level.name + "-" + homeroom.batch.section,
        };
    });
});
const updateBatchInfo = (batchId, search) => {
    if (batchId !== null) selectedHomeroom.value = batchId;
    router.get(
        "/teacher/homeroom",
        {
            batch_id: selectedHomeroom.value,
            search: search,
        },
        {
            preserveState: true,
        }
    );
};

function getStudentDetails(studentId) {
    showModal.value = true;

    studentDetail.value = students.value.data.find((student) => {
        return student.student_id === studentId;
    });

    router.get(
        "/teacher/homeroom/",
        {
            student_id: studentId,
        },
        {
            preserveState: true,
            only: ["student"],
        }
    );
}
</script>
<style scoped></style>
