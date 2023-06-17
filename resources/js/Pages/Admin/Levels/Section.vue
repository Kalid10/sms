<template>
    <div class="flex w-full justify-between space-x-5 divide-x px-2">
        <div class="flex w-4/12 flex-col space-y-5">
            <div class="text-2xl font-semibold uppercase text-gray-700">
                Section {{ batch.section }}
                <span v-if="batch?.home_room_teacher" class="font-light">
                    ( {{ batch.home_room_teacher.teacher.user.name }} )
                </span>
            </div>
            <div class="flex w-full justify-between space-x-5">
                <div class="h-32 w-6/12">
                    <ActiveSession :batch="batch" />
                </div>
                <div class="h-32 w-6/12">
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
            <Assessments class="pt-3" :assessments="batch?.assessments" />
        </div>
        <div class="w-8/12 px-8">
            <Performance :batch="batch" />
        </div>
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import ActiveSession from "@/Views/Admin/Levels/Section/ActiveSession.vue";
import AbsentStudents from "@/Views/Admin/Absentee.vue";
import Performance from "@/Views/Admin/Levels/Section/Performance.vue";
import Assessments from "@/Views/Teacher/Views/Home/Assessments.vue";

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
