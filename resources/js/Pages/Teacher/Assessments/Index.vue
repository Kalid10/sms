<template>
    <div
        class="flex min-h-screen w-full flex-col justify-center px-4 lg:flex-row"
    >
        <div
            class="bg flex flex-col space-y-6 py-8 lg:pl-2 lg:pr-6"
            :class="isSidebarOpenOnXlDevice ? 'w-full' : ' w-full lg:w-7/12 '"
        >
            <div class="font-medium lg:text-4xl">Assessments</div>

            <div
                class="w-fit cursor-pointer px-2 text-xs underline underline-offset-2 hover:font-medium"
                @click="showModal = true"
            >
                Create Assessment
            </div>
            <Table @click="loadDetail" />
        </div>
        <div
            :class="
                isSidebarOpenOnXlDevice
                    ? 'hidden'
                    : 'h-full w-[0.01rem] bg-gray-300'
            "
        ></div>

        <div
            :class="
                isSidebarOpenOnXlDevice
                    ? 'hidden'
                    : 'w-full lg:w-5/12 bg-white/70'
            "
        >
            <Detail
                ref="assessmentDetailsRef"
                class="pt-8 pb-4"
                :assessment="assessmentDetails"
            />
        </div>

        <Modal v-model:view="showModal">
            <Form class="border-none" @success="showModal = false" />
        </Modal>
    </div>
</template>
<script setup>
import Table from "@/Views/Teacher/Assessments/Table/Index.vue";
import Form from "@/Views/Teacher/Assessments/AssessmentForm.vue";
import { ref } from "vue";
import Detail from "@/Views/Teacher/Assessments/Details/Index.vue";
import Modal from "@/Components/Modal.vue";
import { isSidebarOpenOnXlDevice } from "@/utils";

const showModal = ref(false);
const assessmentDetails = ref();

function loadDetail(assessment) {
    assessmentDetails.value = assessment;
    scrollToAssessmentDetails();
}

const assessmentDetailsRef = ref(null);

const scrollToAssessmentDetails = () => {
    assessmentDetailsRef.value.$el.scrollIntoView({ behavior: "smooth" });
    // TODO:: This is sticking to the top of the screen, fix it
};
</script>

<style scoped></style>
