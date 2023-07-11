<template>
    <Card
        :class="
            !!session['lesson_plan_id']
                ? '!bg-gray-50/60 border-gray-400 !shadow-sm '
                : '!bg-transparent !border-gray-300  !border-dashed border-2 !shadow-none'
        "
        class="!w-full cursor-pointer"
    >
        <div class="group flex flex-col gap-4">
            <div
                v-if="!!session['lesson_plan_id']"
                class="flex flex-col gap-0.5"
            >
                <h3 class="text-light text-xs">
                    {{ `Lesson Plan #${Math.floor(Math.random() * 100)}` }}
                </h3>

                <h3
                    class="cursor-pointer text-sm font-medium underline-offset-2 hover:underline"
                >
                    {{ session["lesson_plan"]["topic"] }}
                </h3>
            </div>

            <div v-else class="w-full py-2">
                <div
                    class="flex w-full flex-col items-center justify-center gap-1"
                >
                    <PlusCircleIcon class="h-6 w-6 text-gray-500" />
                    <h3 class="text-xs font-semibold text-black">
                       {{ $t('lessonPlanCard.addLessonPlan')}}
                    </h3>
                    <h3
                        class="max-w-[18rem] text-center text-xs font-medium text-gray-500"
                    >
                        {{ $t('lessonPlanCard.lessonPlanHas')}}
                    </h3>
                </div>
            </div>

            <div class="flex items-end justify-between">
                <div
                    class="flex origin-bottom-left scale-[.8] items-center gap-2"
                >
                    <span
                        class="bg-white py-0.5 text-xs font-semibold uppercase text-gray-700"
                    >
                        {{
                            session["batch_schedule"]["day_of_week"]
                                .substring(0, 3)
                                .toUpperCase()
                        }}
                        {{ moment(session["date"]).format("MMMM Do") }}
                    </span>
                </div>

                <div
                    class="flex origin-bottom-right scale-90 items-center gap-1.5"
                >
                    <div class="flex flex-col gap-1">
                        <div class="flex items-end gap-1.5">
                            <span class="text-xs font-medium">{{ $t('common.period')}}</span>
                            <div
                                class="flex origin-left scale-95 items-center gap-1 font-light"
                            >
                                <span
                                    class="grid h-5 w-5 place-items-center rounded-full border border-black bg-zinc-500 text-xs font-semibold leading-none text-white"
                                >
                                    {{
                                        session["batch_schedule"][
                                            "school_period"
                                        ]["name"]
                                    }}
                                </span>
                            </div>
                            <h3 class="text-xs">
                                {{
                                    moment(
                                        session["batch_schedule"][
                                            "school_period"
                                        ]["start_time"],
                                        "HH:mm:ss"
                                    ).format("HH:mm A")
                                }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Card>
</template>

<script setup>
import moment from "moment";
import Card from "@/Components/Card.vue";
import { PlusCircleIcon } from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
    session: {
        type: Object,
        required: true,
    },
});
</script>

<style scoped></style>
