<template>
    <div class="flex w-full flex-col border-t border-gray-100 py-4">
        <div class="text-2xl font-semibold uppercase text-gray-700">
            Section {{ batch.section }}
        </div>
        <div class="flex w-full justify-between">
            <div class="flex w-4/12 flex-col items-start space-y-4 py-3 px-1">
                <div class="flex w-full justify-between">
                    <div class="w-7/12 text-center text-lg font-light">
                        <div
                            class="flex h-full w-full items-center justify-center rounded-lg bg-white"
                        >
                            <div
                                v-if="batch.home_room_teacher"
                                class="flex h-full w-full flex-col items-center justify-center space-y-2"
                            >
                                <div
                                    class="cursor-pointer font-semibold hover:scale-105 hover:underline hover:underline-offset-2"
                                >
                                    {{
                                        batch.home_room_teacher.teacher.user
                                            .name
                                    }}
                                </div>
                                <div class="text-sm font-light">
                                    Homeroom teacher
                                </div>
                            </div>
                            <div v-else class="h-full">
                                <EmptyView
                                    title="No homeroom teacher assigned"
                                    link-title="Assign Homeroom"
                                    link-url="/homeroom/assign"
                                    class="!h-full"
                                />
                            </div>
                        </div>
                    </div>
                    <div
                        class="h-fit w-4/12 items-center justify-center space-y-2 rounded-lg bg-gradient-to-bl from-violet-500 to-purple-500 py-3 text-center uppercase text-white shadow-sm"
                    >
                        <div class="text-[0.65rem] font-light">Attending</div>

                        <div class="text-3xl font-semibold uppercase">
                            {{
                                batch.active_session[0].batch_subject.subject
                                    .full_name
                            }}
                        </div>
                        <div class="text-[0.65rem] font-light">with</div>

                        <div
                            class="cursor-pointer text-xs font-semibold uppercase underline-offset-2 hover:scale-105 hover:underline"
                        >
                            {{ batch.active_session[0].teacher.user.name }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-7/12">
                <CurrentDaySchedule :batch="batch" />
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import EmptyView from "@/Views/EmptyView.vue";
import CurrentDaySchedule from "@/Views/CurrentDaySchedule.vue";

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
