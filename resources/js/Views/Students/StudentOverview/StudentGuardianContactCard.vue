<template>

    <Card icon title="Guardian Contact" class="!max-w-sm">

        <div class="flex w-full flex-col gap-6">

            <div class="flex flex-col items-center gap-3">

                <div class="flex w-full items-center justify-between">
                    <div class="flex flex-col">
                        <Heading class="truncate whitespace-nowrap" size="sm">
                            {{ guardian?.name }}
                        </Heading>
                        <Heading
                            :class="{ 'text-gray-500': !!!student['guardian_relation'] }"
                            class="whitespace-nowrap !font-normal" size="sm"
                        >
                            {{ student['guardian_relation'] || 'Guardian' }}
                        </Heading>
                    </div>
                    <img
                        :src="`https://xsgames.co/randomusers/avatar.php?g=${getOppositeGender(student.user.gender)}`"
                        alt="avatar"
                        class="aspect-square w-12 rounded-full"
                    />
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
import Card from "@/Components/Card.vue";
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import {getOppositeGender} from "@/fake.js";
import TertiaryButton from "@/Components/TertiaryButton.vue";
import Heading from "@/Components/Heading.vue";

const student = computed(() => usePage().props.student)
const guardian = computed(() => usePage().props['student']['guardian']['user'])
</script>

<style scoped>

</style>
