<template>
    <div class="flex h-screen w-full flex-col space-y-2 bg-brand-50">
        <div
            v-if="!selectedHomeroom"
            class="flex h-96 flex-col items-center justify-center space-y-5"
        >
            <div class="">404</div>
            <div class="w-6/12 text-center text-3xl font-semibold text-black">
                {{ $t("homeRooms.noHomeroomClasses") }}
            </div>

            <SecondaryButton
                v-if="isAdmin()"
                :title="$t('homeRooms.assignHomeroom')"
                class="!rounded-2xl bg-brand-450 text-white"
                @click="showAssignModal = true"
            />
        </div>

        <div
            v-else
            class="flex min-h-screen w-full flex-col justify-between divide-x divide-gray-200 bg-brand-50 lg:flex-row lg:space-x-6"
        >
            <!--        Left Side-->
            <div
                class="flex w-full flex-col space-y-4 py-5 px-3 lg:w-9/12 lg:pl-5"
            >
                <Header
                    :title="$t('homeRooms.homeroomClasses')"
                    :select-input-options="homeroomOptions"
                    :selected-input="selectedHomeroom"
                    @change="updateBatchInfo"
                />

                <Statistics class="!bg-white" />

                <div class="flex w-full flex-col justify-between lg:flex-row">
                    <div class="w-full lg:!w-6/12">
                        <StudentsTable
                            :show-homeroom-detail="false"
                            @search="updateBatchInfo"
                            @click="getStudentDetails"
                        />
                    </div>
                    <div class="h-fit w-full lg:w-5/12">
                        <Flag
                            view="homeroom"
                            :title="
                                selectedHomeroomName.label + ' Flagged Students'
                            "
                            :batch-subject-options="homeroomOptions"
                        />
                    </div>
                </div>
            </div>

            <!--        Right side-->
            <div
                class="flex w-full flex-col space-y-6 bg-brand-50 py-5 lg:w-3/12 lg:pl-5"
            >
                <div
                    class="flex w-full flex-col space-y-3 px-3 lg:flex-row lg:justify-evenly lg:space-y-0 lg:px-0"
                >
                    <div
                        class="flex w-full flex-col justify-evenly space-y-4 rounded-lg bg-positive-100 py-5 text-center text-5xl font-bold text-white shadow-sm lg:w-5/12"
                    >
                        <div v-if="grade">{{ grade?.attendance ?? "-" }}%</div>
                        <div>-</div>
                        <span class="text-xs font-light"
                            >{{ $t("homeRooms.classAttendance") }}
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
                            'bg-brand-400 text-white': !grade?.conduct,
                        }"
                        class="flex w-full flex-col justify-center space-y-4 rounded-lg py-5 text-center text-5xl font-bold shadow-sm lg:w-5/12"
                    >
                        <div>{{ grade?.conduct ?? "-" }}</div>
                        <span class="text-xs font-light"
                            >{{ $t("homeRooms.classConduct") }}
                        </span>
                    </div>
                </div>
                <div class="w-11/12 rounded-lg bg-white py-2 shadow-sm">
                    <StudentsList
                        progress-type="up"
                        :title="$t('homeRooms.topStudents')"
                        :icon="ArrowTrendingUpIcon"
                        :students="topStudents"
                    />
                </div>

                <div class="w-11/12 rounded-lg bg-white shadow-sm">
                    <StudentsList
                        progress-type="down"
                        :title="$t('homeRooms.studentsFalling')"
                        :icon="ArrowTrendingDownIcon"
                        :students="bottomStudents"
                    />
                </div>
            </div>
        </div>
    </div>
    <Modal v-model:view="showAddFlagModal">
        <AddFlag
            :flaggable="studentDetail.student"
            view="homeroom"
            @done="showAddFlagModal = false"
        />
    </Modal>
    <Modal v-model:view="showModal">
        <StudentGradeDetail
            :student-grade="studentDetail.quarterly_grade"
            :student-name="studentDetail.student"
            @flag="
                showModal = false;
                showAddFlagModal = true;
            "
        />
    </Modal>

    <Modal v-model:view="showAssignModal">
        <AssignHomeroom @close="showAssignModal = false" />
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
import StudentGradeDetail from "@/Views/Teacher/Views/Grade/StudentGradeDetail.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import AssignHomeroom from "@/Views/Teacher/Views/Homeroom/AssignHomeroom.vue";
import { isAdmin } from "@/utils";
import Flag from "@/Views/Flag/Index.vue";
import AddFlag from "@/Views/Flag/AddFlag.vue";

const homeroomClasses = computed(() => usePage().props.homeroom_classes);
const selectedHomeroom = ref(usePage().props.filters.batch_id);
const students = computed(() => usePage().props.students);
const topStudents = computed(() => usePage().props.top_students);
const bottomStudents = computed(() => usePage().props.bottom_students);
const grade = computed(() => usePage().props.grade);
const showModal = ref(false);
const showAssignModal = ref(false);
const studentDetail = ref();
const showAddFlagModal = ref(false);

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
        isAdmin()
            ? "/admin/teachers/homeroom?teacher_id=" +
                  usePage().props.filters.teacher_id +
                  "&batch_id="
            : "/teacher/homeroom?batch_id=",
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

const selectedHomeroomName = computed(() => {
    return homeroomOptions.value.find((item) => {
        return (item.id = selectedHomeroom);
    });
});
</script>
<style scoped></style>
