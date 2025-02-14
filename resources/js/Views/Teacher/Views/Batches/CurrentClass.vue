<template>
    <div v-if="view === 'default'">
        <div
            v-if="inProgressSession"
            class="flex w-full flex-col items-center justify-evenly space-y-1 rounded-md px-3 py-2 text-center text-white"
        >
            <div class="text-[0.6rem] font-light">
                {{ $t("currentClass.currentClass") }}
            </div>
            <div v-if="inProgressSession.batch_schedule?.batch_subject">
                <div class="text-xl font-bold">
                    {{
                        inProgressSession.batch_schedule?.batch_subject.subject
                            .full_name
                    }}
                </div>
                <div class="text-xs font-light">
                    With
                    {{
                        inProgressSession.batch_schedule.batch_subject.teacher
                            .user.name
                    }}
                </div>
            </div>
            <div v-else-if="inProgressSession.batch_schedule.school_period">
                {{ inProgressSession.batch_schedule.school_period.name }}
            </div>
        </div>
        <div v-else class="w-full text-center font-light text-white">
            {{ $t("currentClass.noActiveClass") }}
        </div>
    </div>
    <div v-else-if="view === 'absentee'">
        <div
            v-if="yourInProgressSession"
            class="flex h-full w-full items-center justify-between space-y-2 divide-x divide-fuchsia-50 rounded-lg border-2 border-black bg-gradient-to-tl from-brand-300 to-brand-350 px-5 py-3 text-white shadow-sm"
        >
            <div
                class="flex h-full w-6/12 flex-col items-center justify-between space-y-4"
            >
                <div class="w-full text-center font-light">
                    You are now having
                    <span class="px-1 font-medium">{{
                        yourInProgressSession.batch_schedule.batch_subject
                            .subject.full_name
                    }}</span>
                    class with
                    <span>{{
                        yourInProgressSession.batch_schedule.batch_subject.batch
                            .level.name +
                        "-" +
                        yourInProgressSession.batch_schedule.batch_subject.batch
                            .section
                    }}</span>
                    students!
                </div>
                <SecondaryButton
                    class="w-fit !rounded-2xl bg-brand-450 !px-5 font-bold uppercase text-white"
                    :title="$t('currentClass.buttonTitle')"
                    @click="showModal = true"
                />
            </div>
            <div
                class="flex w-5/12 flex-col items-center justify-center space-y-4"
            >
                <div class="text-6xl font-bold">
                    {{ yourInProgressSession.absentees?.length }}
                </div>
                <div class="text-sm font-light uppercase">
                    {{ $t("currentClass.absentStudents") }}
                </div>
            </div>

            <Modal v-model:view="showModal">
                <AddAbsentees @close="showModal = false" />
            </Modal>
        </div>
    </div>
</template>
<script setup>
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import AddAbsentees from "@/Views/Teacher/Views/Batches/AddAbsentees.vue";

const inProgressSession = computed(() => usePage().props.in_progress_session);
const yourInProgressSession = computed(
    () => usePage().props.your_in_progress_session
);
const showModal = ref(false);
defineProps({
    view: {
        type: String,
        default: "default",
    },
});
</script>

<style scoped></style>
