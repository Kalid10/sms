<template>
    <div class="flex h-fit w-full flex-col items-center space-y-3 py-2">
        <div
            class="flex w-11/12 -skew-x-3 items-center justify-center space-x-1.5 py-1.5 font-semibold"
        >
            <component
                :is="icon"
                class="w-5"
                :class="
                    progressType === 'up'
                        ? 'text-positive-100'
                        : 'text-negative-100'
                "
            />
            <span class="uppercase">{{ title }}</span>
        </div>
        <div v-if="students.length" class="w-full">
            <div
                v-for="(item, index) in students"
                :key="index"
                class="flex h-fit w-full cursor-pointer items-center justify-center space-x-2 py-1.5 hover:bg-brand-400 hover:text-white"
                :class="index % 2 === 0 ? 'bg-brand-50/70' : 'bg-white'"
            >
                <Item
                    :progress-type="progressType"
                    :student="item.student"
                    :rank="numberWithOrdinal(item.rank)"
                    :attendance="item.attendance_percentage"
                    :progress="80"
                    :grade="item?.score?.toFixed(1)"
                    :total-score="item?.total_score"
                    @click="
                        () =>
                            router.visit(
                                '/teacher/students/' +
                                    item.student.id +
                                    '?batch_subject_id=' +
                                    batchSubject.id
                            )
                    "
                />
            </div>
        </div>
        <div v-else class="w-full px-3 py-4 text-center text-sm font-light">
            <div class="mb-4 w-full">
                {{ $t("studentsList.noList") }}
            </div>
            <!--            <LinkCell-->
            <!--                v-if="showLink"-->
            <!--                href="/teacher/assessments"-->
            <!--                value="Go To Assessments"-->
            <!--            />-->
        </div>
    </div>
</template>
<script setup>
import Item from "@/Views/Teacher/Views/Batches/PerformanceHighlights/Item.vue";
import { numberWithOrdinal } from "@/utils";
import { router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const batchSubject = computed(() => {
    return usePage().props.batch_subject;
});
defineProps({
    students: {
        type: Array,
        required: true,
    },
    icon: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    progressType: {
        type: String,
        required: true,
    },
    showLink: {
        type: Boolean,
        default: true,
    },
});
</script>

<style scoped></style>
