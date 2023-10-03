<template>
    <div
        v-if="teacher"
        class="scrollbar-hide flex flex-col space-y-2 overflow-y-auto text-center"
    >
        <div
            v-if="teacher?.active_batch_subjects?.length"
            class="flex flex-col space-y-3"
        >
            <div
                v-for="(subjects, index) in teacher?.active_batch_subjects"
                :key="index"
                class="py-1.5"
                :class="index % 2 === 0 ? 'bg-brand-50' : ''"
            >
                <div>{{ subjects.weekly_frequency }}</div>
            </div>

            <div class="text-center font-semibold">
                Total Weekly Sessions
                {{ sumWeeklyFrequency(teacher?.active_batch_subjects) }}
            </div>
        </div>
        <div v-else class="text-center font-semibold">
            <EmptyView
                title="No Active Subjects"
                description="This teacher has no active subjects assigned for this teacher"
            />
        </div>
    </div>
    <div v-else>
        <EmptyView
            title="No Teacher Selected"
            sub-title="Please select a teacher to view their assigned subjects"
        />
    </div>
</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import EmptyView from "@/Views/EmptyView.vue";

const teacher = computed(() => usePage().props.teacher);

function sumWeeklyFrequency(batchSubjects) {
    return batchSubjects.reduce(
        (total, subject) => total + subject.weekly_frequency,
        0
    );
}
</script>
<style scoped></style>
