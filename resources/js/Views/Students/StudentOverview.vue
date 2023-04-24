<template>

    <div class="flex w-full gap-3">

        <Card class="!max-w-fit">

            <Heading class="text-center !font-semibold" size="md">Current Class</Heading>

            <div class="flex h-full w-full items-center justify-center px-6 text-6xl font-semibold">
                {{ activeBatch.level.name }} {{ activeBatch.section }}
            </div>

        </Card>

        <Card icon :title="`Personal details`" class="!grow">

            <ul class="flex w-full flex-col gap-2">

                <li class="flex gap-2">
                    <IdentificationIcon class="h-5 w-5 stroke-gray-500"/>
                    <span class="flex gap-1 text-sm">
                    <span>GSN/2201/19</span>
                </span>
                </li>

                <li class="flex gap-2">
                    <CakeIcon class="h-5 w-5 stroke-gray-500"/>
                    <span class="flex gap-1 text-sm">
                    <span>{{ student.user.date_of_birth }}</span>
                </span>
                </li>

                <li class="flex gap-2">
                    <RectangleStackIcon class="h-5 w-5 stroke-gray-500"/>
                    <span class="flex gap-1 text-sm">
                    <span class="flex">{{ student.batches.length }}
                        <span v-if="student.batches.length === 1">st</span>
                        <span class="ml-1">School Year</span>
                        <span v-if="student.batches.length > 1">s</span>
                    </span>
                    <span class="text-gray-500">at Gibson Youth</span>
                </span>
                </li>

                <li v-if="student.user.email" class="flex gap-2">
                    <AtSymbolIcon class="h-5 w-5 stroke-gray-500"/>
                    <span class="flex gap-1 text-sm">
                    <a
                        :href="`mailto:${student.user.email}`"
                        class="text-blue-500 underline-offset-2 hover:underline">{{ student.user.email }}
                    </a>
                </span>
                </li>

                <li v-if="student.user.phone_number" class="flex gap-2">
                    <DevicePhoneMobileIcon class="h-5 w-5 stroke-gray-500"/>
                    <span class="flex gap-1 text-sm">
                    <a
                        class="text-blue-500 underline-offset-2 hover:underline"
                        href="#">{{ student.user.phone_number }}
                    </a>
                </span>
                </li>

            </ul>


        </Card>

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

    </div>

</template>

<script setup>
import {computed} from "vue";
import {getOppositeGender} from "@/fake.js"
import Card from "@/Components/Card.vue"
import Heading from "@/Components/Heading.vue";
import {
    IdentificationIcon,
    CakeIcon,
    RectangleStackIcon,
    AtSymbolIcon,
    DevicePhoneMobileIcon
} from "@heroicons/vue/24/outline";
import {useStudentStore} from "@/Store/students.js";
import TertiaryButton from "@/Components/TertiaryButton.vue";

const store = useStudentStore()

const student = computed(() => store['student'])
const activeBatch = computed(() => store['activeBatch'])
const guardian = computed(() => store['student']['guardian']['user'])
</script>

<style scoped>

</style>
