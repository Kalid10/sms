<template>
    <div
        class="flex h-full w-full flex-col items-center justify-evenly space-y-4 py-3"
        :class="[
            view === 'student'
                ? 'w-full space-y-2'
                : isSidebarOpenOnXlDevice
                ? 'lg:w-full lg:space-y-4 lg:py-3'
                : 'lg:w-full lg:space-y-6 lg:py-0',
        ]"
    >
        <div
            class="w-full text-center font-light"
            :class="[
                !isTeacherView && isNextClassSubjectTeacher
                    ? 'text-2xl opacity-90'
                    : fontSize,
            ]"
        >
            <span
                v-if="isNextClassSubjectTeacher"
                class="break-words text-sm font-light"
                >{{ $t("nextClass.nextUp") }}</span
            >
            <span v-else> {{ $t("nextClass.nextClass") }}</span>
        </div>

        <!--         Views View-->
        <div
            v-if="isTeacherView"
            class="flex h-fit flex-col items-center justify-evenly space-y-6"
        >
            <!--            Class text-->
            <span
                class="text-center text-4xl font-semibold 2xl:text-5xl"
                :class="fontSizeLarge"
            >
                {{ nextClass.batch_subject.batch.level.name
                }}{{ nextClass.batch_subject.batch.section }}</span
            >

            <!--            Period and subject name section-->
            <span class="text-center text-sm font-medium" :class="fontSize"
                >{{ nextClass.batch_subject.subject.full_name }}
                <span class="font-semibold">{{
                    nextClass.school_period.name
                }}</span>
                {{ $t("common.period") }}
                <br />
                {{ moment(nextClass.date).fromNow() }}</span
            >
        </div>

        <!--        Student View-->
        <div v-else class="flex flex-col justify-evenly space-y-4">
            <!--            Class text-->
            <span
                class="text-center text-4xl font-bold 2xl:text-5xl"
                :class="fontSizeLarge"
            >
                {{ nextClass.batch_subject.subject.full_name }}</span
            >
            <span class="space-y-2 text-center text-sm font-light"
                >Period {{ nextClass.school_period.name }}

                {{ moment(nextClass.date).fromNow() }}</span
            >
        </div>
        <!--            LessonPlan section-->
        <span
            v-if="
                (!isTeacherView && isNextClassSubjectTeacher) ||
                (isTeacherView && !isSidebarOpenOnXlDevice)
            "
            class="text-xs font-light hover:cursor-pointer hover:font-medium hover:underline"
            :class="fontSizeSmall"
        >
            <span v-if="nextClass.lesson_plan">
                {{ $t("nextClass.lessonPlan") }} #{{
                    nextClass.lesson_plan_id
                }}</span
            >
            <span v-else>{{ $t("nextClass.addLessonPlan") }}</span>
        </span>
        <div
            v-else-if="!isTeacherView"
            class="flex w-full flex-col items-center break-words text-[0.65rem] font-light"
        >
            <div class="font-normal">{{ $t("nextClass.teacher") }}</div>
            <div>
                {{ nextClass.batch_subject.teacher.user.name }}
            </div>
        </div>
        <PrimaryButton
            class="w-8/12 !border-none bg-brand-400 !text-xs lg:w-10/12 2xl:w-10/12"
            :class="buttonWidth"
            @click="$emit('view')"
            >{{ $t("nextClass.viewFullSchedule") }}
        </PrimaryButton>
    </div>
</template>

<script setup>
import moment from "moment/moment";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { usePage } from "@inertiajs/vue3";
import { isSidebarOpenOnXlDevice } from "@/utils";
import { computed } from "vue";

defineEmits(["view"]);
const props = defineProps({
    nextClass: {
        type: Object,
        default: null,
    },
    view: {
        type: String,
        default: "teacher",
    },
});
const nextClass = props.nextClass ?? usePage().props.teacher.next_batch_session;
const teacher = usePage().props.auth.user.teacher;

// Computed properties for conditional styling
const fontSize = computed(() =>
    isSidebarOpenOnXlDevice.value
        ? "lg:text-xs opacity-70"
        : "lg:text-sm opacity-70"
);
const fontSizeLarge = computed(() =>
    isSidebarOpenOnXlDevice.value ? "lg:text-3xl" : "lg:text-4xl"
);
const fontSizeSmall = computed(() =>
    isSidebarOpenOnXlDevice.value ? "lg:text-[0.65rem]" : "lg:text-xs"
);
const buttonWidth = computed(() =>
    isSidebarOpenOnXlDevice.value ? "lg:w-8/12" : "lg:w-10/12"
);

const isTeacherView = computed(() => props.view === "teacher");
const isNextClassSubjectTeacher = computed(() =>
    !isTeacherView.value
        ? nextClass.batch_subject.teacher.id === teacher.id
        : false
);
</script>

<style scoped></style>
