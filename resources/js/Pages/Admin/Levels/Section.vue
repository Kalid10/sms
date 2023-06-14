<template>
    <div
        class="flex h-full w-full flex-col space-y-6 border-t border-gray-100 py-4"
    >
        <div class="flex w-full justify-between">
            <div class="text-2xl font-semibold uppercase text-gray-700">
                Section {{ batch.section }}
            </div>
            <TextInput class="w-5/12" placeholder="Search Students" />
        </div>
        <div class="flex w-full justify-between space-x-5 p-2">
            <div class="w-7/12">
                <CurrentDaySchedule
                    :schedule="batch?.schedule"
                    :active-batch-schedule-id="
                        batch?.active_session[0]?.batch_schedule_id
                    "
                />
            </div>
            <div class="flex w-2/12">
                <ActiveSession :batch="batch" />
            </div>
            <div class="flex h-full w-2/12">
                <AbsentStudents
                    :value="
                        batch.active_session[0]
                            ? batch.active_session[0].absentees.length
                            : 0
                    "
                    :batch="batch"
                    title="Absent Students"
                    class-style="bg-red-600"
                />
            </div>
        </div>
        <div class="flex w-full justify-between space-x-10 divide-x">
            <div
                class="flex h-full w-5/12 flex-col space-y-4 divide-y px-2 text-center text-lg font-light"
            >
                <Homeroom :batch="batch" />
                <Assessments class="pt-3" :assessments="batch?.assessments" />
            </div>
            <div class="flex w-7/12 flex-col space-y-4 rounded-lg p-4">
                <Performance :batch="batch" />
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import CurrentDaySchedule from "@/Views/CurrentDaySchedule.vue";
import ActiveSession from "@/Views/Admin/Levels/Section/ActiveSession.vue";
import AbsentStudents from "@/Views/Admin/Absentee.vue";
import Performance from "@/Views/Admin/Levels/Section/Performance.vue";
import TextInput from "@/Components/TextInput.vue";
import Assessments from "@/Views/Teacher/Views/Home/Assessments.vue";
import Homeroom from "@/Views/Admin/Levels/Section/Homeroom.vue";

const props = defineProps({
    batch: {
        type: Object,
        required: true,
    },
});

const level = computed(() => {
    return usePage().props.level;
});

const activeSession = computed(() => {
    if (props.batch.active_session) {
        return {
            session: props.batch.active_session.map((s) => {
                return {
                    id: s.id,
                    name: s.name,
                    status: s.status,
                    subject: s.batch_subject.subject.full_name,
                    teacher: s.teacher.user.name,
                    period: s.school_period.name,
                };
            }),
        };
    }
    return null;
});
</script>
<style scoped></style>
