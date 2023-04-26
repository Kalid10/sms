<template>

    <Card icon title="Guardian Contact" class="!max-w-xs">

        <div class="flex w-full flex-col gap-6">

            <div class="flex flex-col items-center gap-3">

                <div class="flex w-full items-center justify-between gap-3">
                    <Heading class="truncate whitespace-nowrap" size="sm">
                        {{ guardian?.name }}
                    </Heading>
                    <Heading
                        :class="{ 'text-gray-500': !!!student['student']['guardian_relation'] }"
                        class="whitespace-nowrap !font-normal" size="sm"
                    >
                        {{ student['student']['guardian_relation'] || 'Guardian' }}
                    </Heading>
                </div>

                <div class="flex w-full gap-3">

                    <TertiaryButton class="w-1/2">Contact</TertiaryButton>
                    <TertiaryButton class="w-1/2">Details</TertiaryButton>

                </div>

            </div>

        </div>

    </Card>

</template>

<script setup>
import {computed} from "vue";
import {useStudentStore} from "@/Store/students.js";
import Card from "@/Components/Card.vue";
import Heading from "@/Components/Heading.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";

const student = useStudentStore()

const guardian = computed(() => student['student']['guardian']['user'])

</script>

<style scoped>

</style>
