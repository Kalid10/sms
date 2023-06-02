<template>
    <div
        class="scrollbar-hide flex min-h-screen w-full flex-col space-y-2 py-2 px-5"
    >
        <div class="flex w-full justify-between">
            <!--Left Side-->
            <div
                class="just scrollbar-hide sticky top-0 flex h-screen w-6/12 flex-col items-center overflow-auto"
            >
                <div class="flex w-full justify-center">
                    <BackButton
                        link="/teacher/assessments"
                        class="absolute left-0 mt-3"
                    />

                    <MarkHeader />
                </div>
                <div class="w-11/12">
                    <MarkItem
                        class="mt-5"
                        @click="getStudentDetail"
                        @update-points="updatePoints"
                    />
                </div>
            </div>

            <!--Divider -->
            <div class="min-h-screen w-[0.01rem] bg-gray-100"></div>

            <!--Loading-->
            <Loading v-if="isLoading" color="primary" is-full-screen />

            <!--Right Side-->
            <div
                class="scrollbar-hide sticky top-0 flex h-screen w-5/12 flex-col items-center space-y-6 overflow-auto py-5"
            >
                <div
                    class="-skew-x-3 bg-zinc-800 px-3 py-1 text-3xl font-bold italic text-white"
                >
                    {{ assessment.title }} Info
                </div>

                <MarkStat
                    :points="points"
                    class="animate-scale-up"
                    @update-student="getStudentDetail"
                />

                <div
                    v-if="assessment.status === 'completed'"
                    class="flex w-full animate-scale-up flex-col space-y-2"
                >
                    <ResultStatistics :assessment="assessment" />
                    <StudentScoreList
                        :assessment="assessment"
                        @student-clicked="getStudentDetail"
                    />
                </div>

                <div class="h-[0.01rem] w-full bg-gray-100"></div>

                <MarkStudentInfo ref="studentInfo" class="animate-scale-up" />

                <div
                    v-if="showFinishMarkingButton"
                    class="flex h-fit w-full animate-scale-up flex-col items-center space-y-4 rounded-md border-2 border-black px-2 py-3 text-center font-semibold lg:w-10/12 2xl:w-9/12"
                >
                    <div
                        class="flex w-full items-center justify-center space-x-1 text-xs font-light"
                    >
                        <ExclamationTriangleIcon class="w-4 text-red-600" />
                        <span>CAUTION</span>
                    </div>
                    <div class="text-xs font-light">
                        Please note, upon clicking 'Finish Marking',
                        notifications, emails and SMS will be sent to parents
                        and relevant authorities regarding the students'
                        performance. However, rest assured, you will retain the
                        ability to make necessary adjustments to these
                        assessments until the end of the current quarter or
                        semester.
                    </div>
                    <SecondaryButton
                        :is-disabled="isLoading"
                        title="Finish Marking"
                        class="w-3/5 rounded-2xl bg-zinc-800 text-white"
                        @click.="insertStudentsAssessment"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { computed, inject, reactive, ref } from "vue";
import MarkItem from "@/Views/Teacher/Assessments/Mark/MarkItem.vue";
import MarkStat from "@/Views/Teacher/Assessments/Mark/MarkStat.vue";
import MarkHeader from "@/Views/Teacher/Assessments/Mark/MarkHeader.vue";
import MarkStudentInfo from "@/Views/Teacher/Assessments/Mark/MarkStudentInfo.vue";
import StudentScoreList from "@/Views/Teacher/Assessments/Details/Views/StudentScoreList.vue";
import ResultStatistics from "@/Views/Teacher/Assessments/Details/Views/ResultStatistics.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import Loading from "@/Components/Loading.vue";
import BackButton from "@/Components/BackButton.vue";

const showNotification = inject("showNotification");
const isLoading = ref(false);
const assessment = computed(() => usePage().props.assessment);
const points = reactive([]);

Echo.private("mark-assessment").listen(".mark-assessment", (e) => {
    if (e.type === "success") isLoading.value = true;
    router.get(
        "/teacher/assessments/mark/" + assessment.value.id,
        {},
        {
            only: ["assessment"],
            preserveState: true,
            onFinish: () => {
                isLoading.value = false;
            },
            onSuccess: () => {
                showNotification({
                    type: "success",
                    message: e.message,
                    position: "top-center",
                });
            },
        }
    );
});

function updatePoints(point) {
    points.splice(0, points.length, ...point);
}

function getStudentDetail(studentId) {
    router.get(
        "/teacher/assessments/mark/" + assessment.value.id,
        {
            student_id: studentId,
        },
        {
            only: ["student"],
            preserveState: true,
            onSuccess: () => {
                scrollToStudentInfo();
            },
        }
    );
}

function insertStudentsAssessment() {
    router.post("/student-assessments/" + assessment.value.id + "/insert", {
        points: points,
    });
}

const showFinishMarkingButton = computed(() => {
    return (
        points.length &&
        points.every((item) => item.point !== null || item.status !== null)
    );
});

const studentInfo = ref(null);
const scrollToStudentInfo = () => {
    studentInfo.value.$el.scrollIntoView({ behavior: "smooth" });
    // TODO:: This is sticking the student info to the top of the screen, fix it
};
</script>

<style scoped></style>
