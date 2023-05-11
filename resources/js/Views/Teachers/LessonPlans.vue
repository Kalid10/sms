<template>
    <div class="flex h-full cursor-pointer flex-col space-y-2 lg:text-center">
        <div v-if="!isLessonPlansEmpty" class="font-medium lg:text-2xl">
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
                            <div class="font-bold lg:text-2xl">
                                {{ moment(item.date).format("ddd") }}
                            </div>
                            <div
                                class="flex flex-col justify-evenly text-center text-xs"
                            >
                                <span class="hidden lg:inline-block">{{
                                    moment(item.date).format("MMMM d YYYY")
                                }}</span>
                                <span class="lg:hidden">{{
                                    moment(item.date).format("MMMM d")
                                }}</span>
                                <span class="lg:hidden">{{
                                    moment(item.date).format("YYYY")
                                }}</span>
                            </div>
                        </div>

                        <div
                            class="flex w-8/12 flex-col items-center justify-between space-y-2 text-center lg:w-7/12"
                        >
                            <div
                                class="pl-2 text-[0.65rem] font-medium lg:pl-0 lg:text-sm"
                            >
                                {{ item.lesson_plan.topic }}
                            </div>
                            <div
                                class="flex w-full justify-between pl-2 text-start text-xs lg:pl-0"
                            >
                                <div
                                    class="text-center text-[0.55rem] font-light lg:text-[0.65rem]"
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
                            class="flex h-full w-2/12 flex-col items-end justify-center space-y-1 text-center lg:items-center"
                        >
                            <div class="w-fit pr-1 lg:pr-0">
                                <div
                                    class="cursor-pointer text-xs underline-offset-2 hover:font-bold hover:underline"
                                >
                                    {{
                                        item.batch_schedule.batch.level.name +
                                        item.batch_schedule.batch.section
                                    }}
                                </div>
                                <div
                                    class="cursor-pointer text-[0.6rem] font-light underline-offset-2 hover:font-medium hover:underline lg:text-xs"
                                >
                                    <span class="hidden lg:inline-block"
                                        >{{
                                            item.batch_schedule.batch_subject
                                                .subject.full_name
                                        }}
                                    </span>
                                    <span class="lg:hidden">{{
                                        item.batch_schedule.batch_subject
                                            .subject.short_name
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <div
                    class="w-full cursor-pointer text-end text-xs font-light underline decoration-neutral-500 underline-offset-2 hover:font-medium lg:text-sm"
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
                    class="w-fit cursor-pointer text-xs font-light underline decoration-neutral-500 underline-offset-2 hover:font-medium lg:text-sm"
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
