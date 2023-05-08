<template>
    <div>
        <div
            v-if="teacher.feedbacks.length > 0"
            class="space-y-2 rounded-md p-2 xl:grid xl:grid-cols-1 xl:gap-3 xl:space-y-0"
        >
            <div
                v-for="(feedback, index) in teacher.feedbacks"
                :key="index"
                class="my-1 rounded-md py-1.5"
            >
                <div>
                    <p class="text-xs">{{ feedback.feedback }}</p>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <span class="text-xs">{{ feedback.author.name }}</span>
                        <span class="ml-auto text-xs">{{
                            moment(feedback.updated_at).fromNow()
                        }}</span>
                    </div>
                </div>
            </div>
            <div
                class="w-full cursor-pointer text-end text-sm font-light underline decoration-neutral-500 underline-offset-2 hover:font-medium"
                @click="$inertia.get('/teacher/feedbacks')"
            >
                View All Feedbacks
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
import { usePage } from "@inertiajs/vue3";
import moment from "moment";

const teacher = usePage().props.teacher;
</script>

<style scoped></style>
