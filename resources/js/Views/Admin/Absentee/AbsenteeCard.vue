<template>
    <div
        :class="
            classStyle
                ? classStyle
                : 'bg-gradient-to-bl from-zinc-600 to-neutral-600 '
        "
        class="flex h-full max-h-full w-full cursor-pointer flex-col justify-evenly rounded-lg py-3 px-2 text-center text-white shadow-sm"
        @click="
            batch?.active_session[0]?.absentees.length > 0
                ? (showModal = true)
                : (showModal = false)
        "
    >
        <div class="text-7xl font-bold">{{ value }}</div>

        <div
            class="cursor-pointer text-base font-light hover:scale-105 hover:font-medium hover:underline hover:underline-offset-2"
        >
            {{ title }}
        </div>
    </div>
    <Modal v-model:view="showModal">
        <div class="flex h-full flex-col space-y-5 rounded-lg bg-white p-4">
            <div class="text-center text-3xl font-bold">
                {{ batch.active_session[0].batch_subject.subject.full_name }}
                {{ $t("usersAbsentee.classAbsentStudents") }}
            </div>
            <div
                v-for="(item, index) in batch.active_session[0].absentees"
                :key="index"
                class="cursor-pointer rounded-lg py-2 pl-3 hover:font-medium hover:underline hover:underline-offset-2"
                :class="index % 2 === 1 ? 'bg-brand-100' : ''"
                @click="handleClick(item.user.student.id)"
            >
                {{ index + 1 }}. {{ item.user.name }}
            </div>
            <div class="flex w-full justify-between p-3">
                <div class="text-xs font-light">
                    {{ $t("usersAbsentee.teacher") }}
                    <span class="font-medium uppercase">
                        {{ batch.active_session[0].teacher.user.name }}
                    </span>
                </div>
                <div class="text-xs font-light">
                    {{ moment().format("ddd DD MMM YYYY") }}
                </div>
            </div>
        </div>
    </Modal>
</template>
<script setup>
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import moment from "moment";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
defineProps({
    value: {
        type: String,
        default: "0",
    },
    title: {
        type: String,
        default: "Absent Teachers",
    },
    classStyle: {
        type: String,
        default: "",
    },
    batch: {
        type: Array,
        default: null,
    },
});
const showModal = ref(false);

function handleClick(studentId) {
    router.get(
        "/students/" + studentId,
        {},
        {
            preserveState: true,
        }
    );
}
</script>
<style scoped></style>
