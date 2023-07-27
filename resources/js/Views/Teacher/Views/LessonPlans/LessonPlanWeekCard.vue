<template>
    <Card
        title="true"
        class="!bg-white!p-3 h-full !max-w-[26rem] !rounded-b-none !border-b-0 !border-gray-200 !font-light text-brand-500 !shadow-sm drop-shadow-sm"
    >
        <template #title>
            <div class="pb-1 text-sm font-medium">
                {{ $t("lessonPlanWeekCardNew.week") }} {{ index }}
            </div>
        </template>
        <template #subtitle>
            <span v-if="batchSessions.length > 1" class="text-brand-500">
                {{ $t("lessonPlanWeekCardNew.classSessionsWeekStarting") }}
                <span class="inline-block font-semibold">
                    {{
                        moment(batchSessions[0]["date"])
                            .startOf("week")
                            .format("MMMM Do")
                    }}
                </span>
                {{ $t("lessonPlanWeekCardNew.andEndingOn") }}
                <span class="inline-block font-semibold">
                    {{
                        moment(batchSessions[0]["date"])
                            .endOf("week")
                            .format("MMMM Do")
                    }}
                </span>
            </span>
        </template>

        <div
            class="scrollbar-hide mt-2 flex h-full max-h-full w-full flex-col gap-4 overflow-auto"
        >
            <div
                v-if="batchSessions.length < 1"
                class="flex h-full flex-col items-center"
            >
                <EmptyView
                    class="mt-20"
                    :title="
                        $t('lessonPlanWeekCardNew.noClassSessions') +
                        index +
                        '!'
                    "
                />
            </div>

            <template v-else>
                <slot />
            </template>
        </div>
    </Card>
</template>

<script setup>
import moment from "moment";
import Card from "@/Components/Card.vue";
import EmptyView from "@/Views/EmptyView.vue";

const props = defineProps({
    index: {
        type: Number,
        required: true,
    },
    batchSessions: {
        type: Array,
        required: true,
    },
});
</script>

<style scoped></style>
