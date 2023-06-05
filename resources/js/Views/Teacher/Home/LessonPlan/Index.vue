<template>
    <div>
        <div
            class="5 flex w-full items-center justify-between hover:bg-zinc-800 hover:text-white hover:shadow-sm"
            :class="
                view === 'class'
                    ? 'rounded-sm bg-gray-50 hover:rounded-lg py-2 border-l'
                    : 'rounded-r-lg hover:rounded-lg h-full'
            "
        >
            <div
                v-if="view === 'class'"
                class="mr-1 min-h-full w-0.5 bg-gray-600 hover:bg-gray-100"
            ></div>
            <div
                v-else
                class="flex h-full w-3/12 flex-col items-center justify-evenly rounded-l-lg bg-black py-6 text-white"
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
                class="flex h-5/6 w-9/12 flex-col items-center space-y-2 text-center"
                :class="
                    view === 'class'
                        ? 'lg:w-full px-1.5'
                        : isSidebarOpenOnXlDevice
                        ? 'lg:w-9/12 lg:px-2 lg:justify-center'
                        : 'lg:w-8/12 lg:px-1 lg:justify-between'
                "
            >
                <div
                    class="pl-2 lg:pl-0"
                    :class="
                        view === 'class'
                            ? 'text-xs'
                            : '2xl:text-sm text-[0.65rem] font-medium'
                    "
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
                        v-if="view === 'class'"
                        class="pr-2 text-[0.55rem] font-light"
                    >
                        Scheduled For
                        {{ moment(item.date).format("MMMM d YYYY") }}
                    </div>
                    <div
                        v-else
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
                    view === 'class'
                        ? 'hidden'
                        : isSidebarOpenOnXlDevice
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
    item: {
        type: Array,
        default: null,
    },
    view: {
        type: String,
        default: null,
    },
});
const lessonPlans = props.lessonPlans;
</script>
<style scoped></style>
