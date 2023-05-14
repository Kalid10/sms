<template>
    <div
        class="flex min-h-screen w-full flex-col space-y-4 bg-red-400 px-5 pb-4 pt-8"
    >
        <div class="font-medium lg:text-4xl">Assessments</div>

        <PrimaryButton
            class="w-full lg:w-1/4"
            value="Create Assessment"
            @click="showModal = true"
            >Create Assessment
        </PrimaryButton>
        <Modal v-model:view="showModal">
            <Form @success="showModal = false" />
        </Modal>
        <Table />
    </div>
</template>
<script setup>
import Table from "@/Views/Teachers/Assessments/Table/Index.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import Form from "@/Views/Assessments/Form.vue";
import { ref } from "vue";

const showModal = ref(false);
</script>

<style scoped></style>
