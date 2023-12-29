<template>
    <div
        class="flex min-h-screen w-full flex-col justify-center px-4 lg:flex-row"
    >
        <Loading v-if="isLoading" is-full-screen />

        <div
            class="bg flex flex-col space-y-3 py-4 lg:space-y-6 lg:py-8 lg:pl-2 lg:pr-6"
            :class="
                isSidebarOpenOnXlDevice
                    ? 'w-full'
                    : hasBatchSubjects
                    ? ' w-full lg:w-7/12 '
                    : 'w-11/12'
            "
        >
            <div v-if="hasBatchSubjects" class="flex w-full justify-between">
                <div class="text-xl font-semibold text-black lg:text-4xl">
                    {{ $t("teacherAssessmentsIndex.assessments") }}
                </div>
                <div
                    class="flex w-fit cursor-pointer items-center space-x-1 rounded-md px-2 text-[0.65rem] text-brand-text-450 underline decoration-zinc-800 underline-offset-2 hover:font-semibold lg:hidden"
                    @click="showModal = true"
                >
                    <div>
                        {{ $t("teacherAssessmentsIndex.createAssessment") }}
                    </div>
                </div>
            </div>

            <Table
                v-if="hasBatchSubjects"
                @create="showModal = true"
                @click="loadDetail"
            />

            <div
                v-else
                class="flex h-4/5 w-full flex-col items-center justify-center space-y-8 text-3xl font-semibold uppercase"
            >
                <ExclamationTriangleIcon class="w-10 text-red-600" />
                <span class="bg-red-600 px-3 py-1 text-white"
                    >No Subject Found! Cannot create assessment!</span
                >
                <span class="flex text-center text-xl"
                    >If you think the problem is on our side, please try
                    refreshing the page or contact the adminstrator
                </span>
                <SecondaryButton
                    class="bg-brand-400 text-white"
                    title="Refresh"
                    @click="location.reload()"
                />
            </div>
        </div>

        <div
            v-if="hasBatchSubjects"
            :class="
                isSidebarOpenOnXlDevice
                    ? 'hidden'
                    : 'h-full w-[0.01rem] bg-brand-200'
            "
        ></div>

        <div
            v-if="hasBatchSubjects"
            :class="
                isSidebarOpenOnXlDevice
                    ? 'hidden'
                    : 'w-full lg:w-5/12 bg-white/70'
            "
        >
            <Detail
                v-if="
                    !isLoading && selectedAssessment && !viewMarkableAssessments
                "
                ref="assessmentDetailsRef"
                class="pb-4 pt-8"
                :assessment="selectedAssessment"
                @close="viewMarkableAssessments = true"
            />

            <MarkableAssessmentsComponent
                v-else-if="!isLoading && markableAssessments.length"
                class="w-full"
            />
        </div>

        <Modal v-model:view="showModal">
            <Form class="border-none" @success="showModal = false" />
        </Modal>
    </div>
</template>
<script setup>
import Table from "@/Views/Teacher/Views/Assessments/Table/Index.vue";
import Form from "@/Views/Teacher/Views/Assessments/AssessmentForm.vue";
import { computed, onBeforeMount, ref } from "vue";
import Detail from "@/Views/Teacher/Views/Assessments/Details/Index.vue";
import Modal from "@/Components/Modal.vue";
import { isSidebarOpenOnXlDevice } from "@/utils";
import { router, usePage } from "@inertiajs/vue3";
import Loading from "@/Components/Loading.vue";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import MarkableAssessmentsComponent from "@/Views/Teacher/Views/Assessments/MarkableAssessments.vue";

const showModal = ref(false);
const selectedAssessment = computed(() => usePage().props.assessment);
const isLoading = ref(false);

const hasBatchSubjects = ref(!!usePage().props?.teacher?.batch_subjects);
const markableAssessments = computed(() => {
    return usePage().props?.markable_assessments;
});
const viewMarkableAssessments = ref(false);

const props = defineProps({
    teacherId: {
        type: Number,
        default: null,
    },
});
onBeforeMount(() => {
    // TODO:: Fix the double page bug
    let ass = computed(() => usePage().props.assessments);
    if (!ass.value) {
        isLoading.value = true;
        router.get(
            "/teacher/assessments",
            {
                teacher_id: props.teacherId ?? null,
            },
            {
                preserveScroll: true,
                preserveState: true,
                only: ["assessments", "filters", "teacher"],
                onFinish: () => {
                    isLoading.value = false;
                },
            }
        );
    }
});

function loadDetail(assessment) {
    isLoading.value = true;
    viewMarkableAssessments.value = false;
    router.get(
        "/teacher/assessments/" + assessment.id,
        {
            teacher_id: props.teacherId ?? null,
        },
        {
            preserveState: true,
            preserveScroll: true,
            onStart: () => {
                selectedAssessment.value = null;
            },
            onSuccess: () => {
                scrollToAssessmentDetails();
            },
            onFinish: () => {
                isLoading.value = false;
            },
            only: ["assessment"],
        }
    );
}

const assessmentDetailsRef = ref(null);

const scrollToAssessmentDetails = () => {
    if (assessmentDetailsRef.value)
        assessmentDetailsRef.value.$el.scrollIntoView({ behavior: "smooth" });
    // TODO:: This is sticking to the top of the screen, fix it
};
</script>

<style scoped></style>
