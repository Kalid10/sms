<template>
    <div v-for="(item, index) in lessonPlans" :key="index">
        <div
            class="flex h-20 w-full items-center justify-between rounded-r-lg bg-white shadow-sm hover:scale-105 2xl:h-24"
        >
            <div
                class="flex h-full w-3/12 flex-col items-center justify-evenly rounded-l-lg bg-black text-white"
                :class="
                    isSidebarOpenOnXlDevice ? 'lg:w-3/12 lg:px-2' : 'lg:w-2/12'
                "
            >
                <div class="font-bold lg:text-xl 2xl:text-2xl">
                    {{ moment(item.date).format("ddd") }}
                </div>
                <div class="flex flex-col justify-evenly text-center text-xs">
                    <span
                        class="hidden lg:inline-block 2xl:text-xs"
                        :class="
                            isSidebarOpenOnXlDevice
                                ? 'text-xs'
                                : 'text-[0.65rem]'
                        "
                        >{{ moment(item.date).format("MMMM d YYYY") }}</span
                    >
                    <span class="lg:hidden">{{
                        moment(item.date).format("MMMM d")
                    }}</span>
                    <span class="lg:hidden">{{
                        moment(item.date).format("YYYY")
                    }}</span>
                </div>
            </div>

            <div
                class="flex h-5/6 w-9/12 flex-col items-center justify-between space-y-2 text-center"
                :class="
                    isSidebarOpenOnXlDevice
                        ? 'lg:w-9/12 lg:px-2 lg:justify-center'
                        : 'lg:w-8/12 lg:px-1 lg:justify-between'
                "
            >
                <div
                    class="pl-2 text-[0.65rem] font-medium lg:pl-0 2xl:text-sm"
                >
                    {{ item.lesson_plan.topic }}
                </div>
                <div
                    class="flex w-full justify-between pl-2 text-start text-xs lg:pl-0"
                >
                    <div
                        class="text-center text-[0.55rem] font-light lg:pl-3 2xl:text-[0.65rem]"
                    >
                        <span class="hidden lg:inline-block">Updated</span>
                        {{ moment(item.lesson_plan.created_at).fromNow() }}
                    </div>

                    <div
                        class="pr-2 text-[0.55rem] font-light"
                        :class="
                            isSidebarOpenOnXlDevice
                                ? 'inline-flex'
                                : 'lg:hidden'
                        "
                    >
                        <div>
                            {{
                                item.batch_schedule.batch.level.name +
                                item.batch_schedule.batch.section
                            }}
                            (
                            {{
                                item.batch_schedule.batch_subject.subject
                                    .short_name
                            }}
                            )
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="hidden h-full w-2/12 flex-col items-end justify-center space-y-1 text-center"
                :class="
                    isSidebarOpenOnXlDevice
                        ? 'hidden'
                        : 'lg:flex lg:items-center'
                "
            >
                <div class="w-fit pr-1 lg:pr-0">
                    <div
                        class="cursor-pointer text-[0.6rem] underline-offset-2 hover:font-bold hover:underline 2xl:text-xs"
                    >
                        {{
                            item.batch_schedule.batch.level.name +
                            item.batch_schedule.batch.section
                        }}
                    </div>
                    <div
                        class="cursor-pointer text-[0.6rem] font-light underline-offset-2 hover:font-medium hover:underline lg:mt-1 2xl:text-xs"
                    >
                        {{
                            item.batch_schedule.batch_subject.subject.full_name
                        }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import moment from "moment/moment";
import { isSidebarOpenOnXlDevice } from "@/utils";

const props = defineProps({
    lessonPlans: {
        type: Array,
        default: null,
    },
});
const lessonPlans = props.lessonPlans;
</script>
<style scoped></style>
