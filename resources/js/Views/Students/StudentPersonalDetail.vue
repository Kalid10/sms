<template>

    <div class="flex w-full gap-3">

        <Card class="!max-w-fit">

            <Heading class="text-center !font-semibold" size="md">Current Class</Heading>

            <div class="flex h-full w-full items-center justify-center px-6 text-6xl font-semibold">
                12B
            </div>

        </Card>
        <Card icon :title="`Personal details`" class="!max-w-md">

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
                    <span>12th Semester</span>
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

    </div>

</template>

<script setup>
import {computed} from "vue";
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

const store = useStudentStore()

const student = computed(() => store['student'])
const activeBatch = computed(() => store['activeBatch'])
</script>

<style scoped>

</style>
