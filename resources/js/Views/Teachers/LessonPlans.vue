<template>
    <div class="flex h-full cursor-pointer flex-col space-y-2 text-center">
        <div v-if="!isLessonPlansEmpty" class="text-2xl font-medium">
            Recent Lesson Plans
        </div>
        <div class="flex h-full flex-col items-center justify-center space-y-4">
            <div
                v-if="!isLessonPlansEmpty"
                class="flex flex-col items-center space-y-4 rounded-md py-4"
            >
                <template v-for="(item, index) in lessonPlans" :key="index">
                    <div
                        class="flex h-24 w-full items-center justify-between rounded-r-lg bg-white shadow-sm hover:scale-105"
                    >
                        <div
                            class="flex h-full w-2/12 flex-col items-center justify-evenly rounded-l-lg bg-black text-white"
                        >
                            <div class="text-2xl font-bold">
                                {{ moment(item.date).format("ddd") }}
                            </div>
                            <div class="text-xs">
                                {{ moment(item.date).format("MMMM d YYYY") }}
                            </div>
                        </div>

                        <div
                            class="flex w-7/12 flex-col justify-between space-y-2"
                        >
                            <div class="text-sm font-medium">
                                {{ item.lesson_plan.topic }}
                            </div>
                            <div class="flex justify-between text-xs">
                                <div
                                    class="text-center text-[0.65rem] font-light"
                                >
                                    Updated
                                    {{
                                        moment(
                                            item.lesson_plan.created_at
                                        ).fromNow()
                                    }}
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex h-full w-2/12 flex-col items-center justify-center space-y-1 text-center"
                        >
                            <div
                                class="cursor-pointer text-xs underline-offset-2 hover:font-bold hover:underline"
                            >
                                {{
                                    item.batch_schedule.batch.level.name +
                                    item.batch_schedule.batch.section
                                }}
                            </div>
                            <div
                                class="cursor-pointer text-xs font-light underline-offset-2 hover:font-medium hover:underline"
                            >
                                {{
                                    item.batch_schedule.batch_subject.subject
                                        .full_name
                                }}
                            </div>
                        </div>
                    </div>
                </template>

                <div
                    class="w-full cursor-pointer text-end text-sm font-light underline decoration-neutral-500 underline-offset-2 hover:font-medium"
                    @click="$inertia.get('/teacher/lesson-plan')"
                >
                    View All Lesson Plans
                </div>
            </div>
            <div
                v-else
                class="flex h-full flex-col items-center justify-center space-y-6"
            >
                <ExclamationTriangleIcon class="h-6 w-6 text-gray-500" />
                <div>No Lesson Plan Found!</div>
                <div
                    class="w-fit cursor-pointer text-sm font-light underline decoration-neutral-500 underline-offset-2 hover:font-medium"
                    @click="$inertia.get('/teacher/lesson-plan')"
                >
                    View All Lesson Plans
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import moment from "moment";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import { computed } from "vue";

const lessonPlans = usePage().props.teacher.lesson_plans;

const isLessonPlansEmpty = computed(() => {
    return lessonPlans.length === 0;
});
</script>

<style scoped></style>
