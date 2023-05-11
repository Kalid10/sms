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
                    >Almost there!
                    </Heading>
                </div>

                <div class="flex flex-col gap-3">
                    <p class="max-w-2xl text-center text-sm text-gray-500">
                        You have successfully registered and assigned your school's year, semesters, and
                        subjects, and classes. Next, you will need to set up your
                        school's class schedule. We will guide you through the process.
                    </p>

                    <p class="max-w-2xl text-center text-sm text-gray-500">
                        Create a custom class schedule to fit your school's needs. You can define the number of periods
                        in
                        a day, duration of each period, starting time, and even add custom periods such as lunch or
                        recess. You can
                        also assign these schedules to different level categories. Let's get started!"
                    </p>

                    <p class="max-w-2xl text-center text-sm text-gray-500">
                        Don't worry if you don't have all the details yet,
                        you can always update class times and other schedule information later.
                    </p>
                </div>

                <div class="flex flex-col">


                    <PrimaryButton
title="Now Lets Add School Periods"
                                   @click="welcomeModal = false"/>
                    <!--                            <div-->
                    <!--                                class="flex flex-col items-center justify-between gap-2"-->
                    <!--                            >-->
                    <!--                                <div-->
                    <!--                                    class="flex items-center justify-center"-->
                    <!--                                >-->
                    <!--                                    <h3 class="text-xs font-semibold">-->
                    <!--                                        Register School Periods-->
                    <!--                                    </h3>-->
                    <!--                                    <div-->
                    <!--                                        class="flex max-w-0 grow justify-end opacity-0 transition-all duration-150 group-hover:max-w-full group-hover:opacity-100"-->
                    <!--                                    >-->
                    <!--                                        <ArrowRightIcon-->
                    <!--                                            class="h-3 w-3 stroke-2"-->
                    <!--                                        />-->
                    <!--                                    </div>-->
                    <!--                                </div>-->

                    <!--                                <h3 class="text-center text-xs">-->
                    <!--                                    Add the start and end times for each class period.-->
                    <!--                                    This will create a clear timetable for everyone involved.-->
                    <!--                                </h3>-->
                    <!--                            </div>-->

                </div>
            </div>
        </Card>
    </Modal>
</template>
<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Card from "@/Components/Card.vue";
import Modal from "@/Components/Modal.vue";
import Heading from "@/Components/Heading.vue";
import {ref} from "vue";

const welcomeModal = ref(true);
</script>
