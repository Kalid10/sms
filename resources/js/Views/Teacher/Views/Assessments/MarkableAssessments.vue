<template>
    <div class="flex h-full w-full flex-col items-center space-y-4 py-5">
        <Header
            title="Recent Markable Assessments"
            class="w-full text-center"
            formatted-date=""
        />
        <div
            v-for="(item, index) in markableAssessments"
            :key="index"
            class="flex w-11/12 items-center justify-evenly rounded-lg px-3 py-4 font-medium"
            :class="index % 2 === 0 ? 'bg-brand-50' : ''"
        >
            <div
                class="flex flex-col space-y-2 bg-pink-600 px-2 py-1 text-sm italic text-white"
            >
                <span>
                    {{ item.batch_subject.batch.level.name }}-{{
                        item.batch_subject.batch.section
                    }}
                    {{ item.batch_subject.subject.full_name }}
                </span>
            </div>

            <div class="flex flex-col space-y-2">
                <span>
                    {{ item.title }}
                </span>
                <span class="text-xs font-light">
                    {{ item.description }}
                </span>
            </div>

            <div class="flex flex-col space-y-3">
                <span class="text-xs font-light">
                    Due Date:
                    {{ moment(item.due_date).format("ddd MMM DD, YYYY") }}
                </span>
                <PrimaryButton
                    title="Start Marking"
                    @click="startMarking(item.id)"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import Header from "@/Views/Teacher/Views/Assessments/Details/Views/Header.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import moment from "moment";

const markableAssessments = computed(() => {
    return usePage().props?.markable_assessments;
});

function startMarking(assessmentId) {
    router.get("/teacher/assessments/mark/" + assessmentId, {
        student_id: null,
    });
}
</script>

<style scoped></style>
