<template>
    <div>
        <Heading class="my-2">Feedbacks</Heading>
        <div
v-if="teacher.feedbacks.length > 0"
             class="space-y-4 rounded-md p-2 xl:grid xl:grid-cols-3 xl:gap-3 xl:space-y-0">
            <div
                v-for="(feedback, index) in teacher.feedbacks"
                :key="feedback.id"
                class="rounded-md p-4 shadow-md"
            >
                <div
                    class="border-l-4 pl-4"
                    :class="{
          'border-blue-500': index % 2 === 0,
          'border-green-500': index % 2 === 1,
        }"
                >
                    <p class="text-sm text-gray-600">{{ feedback.feedback }}</p>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <span class="text-xs">Submitted by: {{ feedback.author.name }}</span>
                        <span class="ml-auto text-xs">{{ moment(feedback.updated_at).fromNow() }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="flex h-24 items-center justify-center">
                No feedbacks yet!
            </div>
        </div>
    </div>
</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import {usePage} from "@inertiajs/vue3";
import moment from "moment";

const teacher = usePage().props.teacher;
</script>

<style scoped>

</style>
