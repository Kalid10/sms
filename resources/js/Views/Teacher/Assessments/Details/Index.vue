<template>
    <div v-if="assessment" class="flex h-full w-full flex-col space-y-8">
        <Header
            :title="title"
            :formatted-date="
                String(moment(assessment.due_date).format('dddd MMMM DD YYYY'))
            "
        />
        <GeneralInfo
            v-if="
                assessment.status === 'completed' ||
                assessment.status === 'published'
            "
            teacher-name="Bereket Gobeze"
            :count="assessment.total_students"
            :status="capitalize(assessment.status)"
        />

        <div v-if="assessment.status === 'completed'">
            <ResultStatistics />

            <StudentScoreList :assessment="assessment" />
        </div>

        <transition name="fade" mode="out-in">
            <div
                v-if="assessment.status === 'draft'"
                class="flex h-full flex-col items-center space-y-4"
            >
                <div class="text-center text-sm font-light">
                    The assessment is not yet published(active), you can change
                    the status changing the switch below
                </div>
                <div
                    class="flex w-2/5 items-center justify-center space-x-2 rounded-md py-2"
                    :class="
                        assessment.status === 'draft'
                            ? 'bg-gray-100'
                            : 'bg-positive-50'
                    "
                >
                    <Toggle v-model="isPublished" />
                    <DialogBox
                        v-model:open="isPublished"
                        type="update"
                        title="Publish Assessment"
                        @confirm="updateAssessment('published')"
                    >
                        <template #description>
                            By changing the status of the assessment to
                            published, parents, principals, and other
                            responsible parties will receive notifications. Do
                            you wish to proceed?
                        </template>
                    </DialogBox>
                    <div>{{ capitalize(assessment.status) }}</div>
                </div>
            </div>
        </transition>
        <div
            v-if="assessment.status === 'published'"
            class="flex w-full items-center justify-center"
        >
            <SecondaryButton
                title="Start Marking"
                class="w-44 rounded-xl bg-blue-500 font-bold text-white"
                @click="startMarking"
            />
        </div>
    </div>
    <div
        v-else
        class="flex h-72 w-full items-center justify-center text-xs font-light"
    >
        Click on a ny assessment to check details
    </div>
</template>
<script setup>
import moment from "moment";
import ResultStatistics from "@/Views/Teacher/Assessments/Details/ResultStatistics.vue";
import StudentScoreList from "@/Views/Teacher/Assessments/Details/StudentScoreList.vue";
import Header from "@/Views/Teacher/Assessments/Details/Header.vue";
import GeneralInfo from "@/Views/Teacher/Assessments/Details/Info.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { capitalize, computed, ref } from "vue";
import { router } from "@inertiajs/vue3";
import Toggle from "@/Components/Toggle.vue";
import DialogBox from "@/Components/DialogBox.vue";

const props = defineProps({
    assessment: {
        type: Array,
        required: true,
    },
});

const title = computed(
    () =>
        props.assessment.title +
        " " +
        props.assessment.batch_subject.batch.level.name +
        props.assessment.batch_subject.batch.section
);

const isPublished = ref(false);

function updateAssessment(status) {
    router.post(
        "/teacher/assessments/update/",
        {
            status: status,
            assessment_id: props.assessment.id,
        },
        {
            preserveState: true,
            onSuccess: () => {
                props.assessment.status = status;
            },
        }
    );
}

function startMarking() {
    router.get("/teacher/assessments/mark/" + props.assessment.id, {
        student_id: props.assessment.id,
    });
}
</script>
<style scoped>
.slide-fade-enter-active {
    transition: all 0.3s ease;
}

.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter,
.slide-fade-leave-to {
    transform: translateX(10%);
    opacity: 0;
}

.slide-fade-leave,
.slide-fade-enter-to {
    transform: translateX(0);
    opacity: 1;
}
</style>
