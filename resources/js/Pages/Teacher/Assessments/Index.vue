<template>
    <div
        class="flex min-h-screen w-full justify-between bg-gray-100 px-2 lg:pl-5"
    >
        <div
            class="bg flex w-full flex-col space-y-6 py-8 lg:pl-2 lg:pr-6"
            :class="assessmentDetails ? 'w-6/12' : 'w-11/12'"
        >
            <div class="font-medium lg:text-4xl">Assessments</div>

            <div
                class="w-fit cursor-pointer px-2 text-xs underline underline-offset-2 hover:font-medium"
                @click="showModal = true"
            >
                Create Assessment
            </div>

            <div class="flex h-fit w-full">
                <Table
                    :class="{
                        'w-full lg:w-10/12 xl:w-11/12 2xl:w-10/12':
                            !assessmentDetails,
                        'w-full': assessmentDetails,
                    }"
                    @click="loadDetail"
                />
            </div>
        </div>
        <div
            class="h-full w-[0.01rem] bg-gray-200"
            :class="assessmentDetails ? '' : 'hidden'"
        ></div>

        <div :class="assessmentDetails ? 'w-5/12 bg-white/70' : 'hidden '">
            <Detail class="px-4 pt-8 pb-4" :assessment="assessmentDetails" />
        </div>

        <div v-if="showModal" class="w-5/12 bg-white">
            <Form class="border-none" @success="showModal = false" />
        </div>
    </div>
</template>
<script setup>
import Table from "@/Views/Teacher/Assessments/Table/Index.vue";
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
