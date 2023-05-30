<template>
    <div class="flex min-h-screen w-full flex-col space-y-2 py-2 px-5">
        <div class="flex w-full justify-between">
            <!--            Left Side-->
            <div class="just flex w-6/12 flex-col items-center">
                <MarkHeader />
                <div class="w-11/12">
                    <MarkItem
                        class="mt-5"
                        @click="getStudentDetail"
                        @update-points="updatePoints"
                    />
                </div>
            </div>

            <!--            Divider -->
            <div class="min-h-screen w-[0.01rem] bg-gray-100"></div>

            <!--            Right Side-->
            <div class="flex w-5/12 flex-col items-center space-y-10 pt-5">
                <div
                    class="-skew-x-3 bg-zinc-800 px-3 py-1 text-3xl font-bold italic text-white"
                >
                    {{ assessment.title }} Info
                </div>
                <MarkStat :points="points" @update-student="getStudentDetail" />
                <div
                    v-if="assessment.status === 'completed'"
                    class="flex w-full flex-col space-y-2"
                >
                    <ResultStatistics :assessment="assessment" />

                    <StudentScoreList
                        :assessment="assessment"
                        @student-clicked="getStudentDetail"
                    />
                </div>

                <div class="h-[0.01rem] w-full bg-gray-100"></div>

                <MarkStudentInfo ref="studentInfo" />

                <div
                    v-if="showFinishMarkingButton"
                    class="w-full py-3 text-center font-semibold"
                >
                    <PrimaryButton
                        title="Finish Marking"
                        class="rounded-2xl"
                        @click="insertStudentsAssessment"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { computed, reactive, ref } from "vue";
import MarkItem from "@/Views/Teacher/Assessments/Mark/MarkItem.vue";
import MarkStat from "@/Views/Teacher/Assessments/Mark/MarkStat.vue";
import MarkHeader from "@/Views/Teacher/Assessments/Mark/MarkHeader.vue";
import MarkStudentInfo from "@/Views/Teacher/Assessments/Mark/MarkStudentInfo.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import StudentScoreList from "@/Views/Teacher/Assessments/Details/Views/StudentScoreList.vue";
import ResultStatistics from "@/Views/Teacher/Assessments/Details/Views/ResultStatistics.vue";

Echo.private("mark-assessment").listen(".mark-assessment", (e) => {
    if (e.type === "success")
        router.get(
            "/teacher/assessments/mark/" + assessment.value.id,
            {},
            {
                only: ["assessment"],
                preserveState: true,
            }
        );
});

const assessment = computed(() => usePage().props.assessment);
const points = reactive([]);

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
    return points.length && points.every((item) => item.point !== null);
});

const studentInfo = ref(null);
const scrollToStudentInfo = () => {
    studentInfo.value.$el.scrollIntoView({ behavior: "smooth" });
    // TODO:: This is sticking the student info to the top of the screen, fix it
};
</script>

<style scoped></style>
