<template>
    <div class="flex min-h-screen w-full justify-between px-5 pb-4 pt-8">
        <div
            class="flex flex-col space-y-6 pl-2 pr-6"
            :class="assessmentDetails ? 'w-7/12' : 'w-11/12'"
        >
            <div class="font-medium lg:text-4xl">Assessments</div>

            <div
                class="w-fit cursor-pointer px-2 text-xs underline underline-offset-2 hover:font-medium"
                @click="showModal = true"
            >
                Create Assessment
            </div>
            <Modal v-model:view="showModal">
                <Form @success="showModal = false" />
            </Modal>

            <div class="flex h-fit justify-between">
                <Table class="w-full" @click="loadDetail" />
            </div>
        </div>
        <div
            class="h-full w-[0.01rem] bg-gray-200"
            :class="assessmentDetails ? '' : 'hidden'"
        ></div>

        <div :class="assessmentDetails ? 'w-5/12 pl-5' : 'hidden'">
            <Detail :assessment="assessmentDetails" />
        </div>
    </div>
</template>
<script setup>
import Table from "@/Views/Teacher/Assessments/Table/Index.vue";
import Modal from "@/Components/Modal.vue";
import Form from "@/Views/Teacher/Assessments/Form.vue";
import { ref } from "vue";
import Detail from "@/Views/Teacher/Assessments/Details/Index.vue";

const showModal = ref(false);
const assessmentDetails = ref();

function loadDetail(assessment) {
    assessmentDetails.value = assessment;
}
</script>

<style scoped></style>
