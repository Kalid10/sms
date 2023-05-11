<template>

    <Modal
        v-model:view="welcomeModal"
        center
        background-color="black"
        :close-on-outside-click="false"
    >
        <Card class="mx-auto mt-12 !min-w-full">
            <div class="flex w-full flex-col items-center gap-6">
                <div>
                    <Heading class="text-center" size="md"
                    >Class Schedules
                    </Heading>
                </div>

                <div class="flex flex-col gap-3">
                    <p class="max-w-2xl text-center text-sm text-gray-500">
                        Navigating the complexities of educational administration can be
                        challenging. That's why we've designed this School Management System with a suite of
                        powerful tools to
                        make
                        your job easier.
                    </p>

                    <p class="max-w-2xl text-center text-sm text-gray-500">
                        One of the key features of our system is the ability to generate
                        comprehensive class schedules. Based on a variety of parameters - including the school year,
                        semesters,
                        subjects, sections, and grades - our system generates schedules that suit your institution's
                        specific
                        needs.
                    </p>

                    <p class="max-w-2xl text-center text-sm text-gray-500">
                        Please note, due to the complexity of this task, generating a class schedule
                        may take some time. However, you don't need to wait around. Simply click the button below to
                        start the
                        process, and we'll notify you as soon as your schedule is ready.
                    </p>
                </div>

                <div class="m-4 flex flex-col">
                    <PrimaryButton
                        @click="generateSchedule">
                        Generate Class Schedule
                    </PrimaryButton>

                </div>
            </div>
        </Card>
    </Modal>
</template>

<script setup>

import {router} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";
import Modal from "@/Components/Modal.vue";
import Heading from "@/Components/Heading.vue";
import {ref} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const welcomeModal = ref(true);
const generateSchedule = () => {
    router.post('/batch-schedules/create', {}, {
        onSuccess: () => {
            welcomeModal.value = false;
            router.get("/getting-started/school-schedule");

        },
    })
}
</script>
