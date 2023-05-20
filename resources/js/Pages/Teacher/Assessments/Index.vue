<template>
    <div class="flex min-h-screen w-full justify-between px-5 pb-4 pt-8">
        <div class="flex w-7/12 flex-col space-y-6 pl-2 pr-6">
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
                <Table class="w-full" />
            </div>
        </div>
        <div class="h-full w-[0.01rem] bg-gray-200"></div>

        <div class="flex w-5/12 flex-col space-y-8 pl-5">
            <Header
                title="Test 1 Biology Grade 11A"
                :formatted-date="
                    String(moment('05/10/2023').format('dddd MMMM DD YYYY'))
                "
            />

            <GeneralInfo
                teacher-name="Bereket Gobeze"
                count="32"
                status="Marking"
            />

            <ResultStatistics />

            <StudentScoreList />

            <div class="flex w-full items-center justify-center">
                <SecondaryButton
                    title="Start Marking"
                    class="w-3/12 rounded-xl bg-emerald-500 text-xs text-white"
                />
            </div>
        </div>
    </div>
</template>
<script setup>
import Table from "@/Views/Teacher/Assessments/Table/Index.vue";
import Modal from "@/Components/Modal.vue";
import Form from "@/Views/Teacher/Assessments/Form.vue";
import { ref } from "vue";
import moment from "moment/moment";
import Header from "@/Views/Teacher/Assessments/Details/Header.vue";
import GeneralInfo from "@/Views/Teacher/Assessments/Details/Info.vue";
import ResultStatistics from "@/Views/Teacher/Assessments/Details/ResultStatistics.vue";
import StudentScoreList from "@/Views/Teacher/Assessments/Details/StudentScoreList.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const showModal = ref(false);
</script>

<style scoped></style>
