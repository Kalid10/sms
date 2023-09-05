<template>
    <div
        class="relative col-span-3 flex h-full flex-col items-center justify-between overflow-auto border-r"
    >
        <form
            v-if="isFormRendered"
            class="flex w-full flex-col justify-start gap-4 p-4"
        >
            <div class="flex w-full items-center justify-between">
                <div
                    class="text-brand-text-600 flex grow items-center justify-center gap-1 text-xs font-semibold"
                >
                    {{ $t("schoolPeriodFormWrapper.step") }}

                    <div
                        class="grid h-6 w-6 place-items-center rounded-full border bg-brand-400 text-xs font-semibold text-white"
                    >
                        {{ formStep }}
                    </div>
                    {{ $t("schoolPeriodFormWrapper.of4") }}
                </div>
            </div>

            <fieldset
                :class="{ 'opacity-50': formStep !== 1 }"
                class="flex h-fit w-full flex-col items-center gap-2 rounded-md border border-gray-400 p-4 shadow-sm"
            >
                <div class="flex w-full items-start justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="text-sm font-semibold">
                            {{ $t("schoolPeriodFormWrapper.step") }}
                        </span>

                        <span
                            class="grid h-5 w-5 place-items-center rounded-full border border-black text-xs font-semibold"
                        >
                            {{ $t("schoolPeriodFormWrapper.1") }}
                        </span>
                    </div>

                    <div class="flex flex-col items-end">
                        <h3 class="text-sm font-semibold">
                            {{
                                $t("schoolPeriodFormWrapper.startTimeOfTheDay")
                            }}
                        </h3>
                        <h3 v-if="formStep === 1" class="text-right text-sm">
                            {{
                                $t(
                                    "schoolPeriodFormWrapper.whatTimeFirstPeriod"
                                )
                            }}
                        </h3>
                    </div>
                </div>

                <input
                    v-if="formStep === 1"
                    id="startTime"
                    v-model="form.start_time"
                    :disabled="formStep !== 1"
                    type="time"
                    class="w-full appearance-none rounded-md border border-gray-200 font-semibold focus:border-gray-500 focus:ring-0"
                />

                <div
                    v-if="formStep === 1"
                    class="flex w-full items-center justify-end gap-3"
                >
                    <PrimaryButton
                        :disabled="formStep !== 1 || !!!form.start_time"
                        @click="updateStep"
                    >
                        {{ $t("schoolPeriodFormWrapper.next") }}
                    </PrimaryButton>
                </div>
            </fieldset>

            <fieldset
                :class="{ 'opacity-50': formStep !== 2 }"
                class="flex h-fit w-full flex-col items-center gap-4 rounded-md border border-gray-400 p-4 shadow-sm"
            >
                <div class="flex w-full items-start justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="text-sm font-semibold">
                            {{ $t("schoolPeriodFormWrapper.step") }}
                        </span>

                        <span
                            class="grid h-5 w-5 place-items-center rounded-full border border-black text-xs font-semibold"
                        >
                            {{ $t("schoolPeriodFormWrapper.2") }}
                        </span>
                    </div>

                    <div class="flex flex-col items-end">
                        <h3 class="text-sm font-semibold">
                            {{
                                $t(
                                    "schoolPeriodFormWrapper.durationSinglePeriod"
                                )
                            }}
                        </h3>
                        <h3
                            v-if="formStep === 2"
                            class="text-brand-text-600 text-sm"
                        >
                            {{
                                $t(
                                    "schoolPeriodFormWrapper.howManyMinutesSinglePeriod"
                                )
                            }}
                        </h3>
                    </div>
                </div>

                <div
                    v-if="formStep === 2"
                    class="flex w-full items-center justify-center gap-3"
                >
                    <button
                        v-for="(minute, m) in durations"
                        :key="m"
                        :disabled="formStep !== 2"
                        type="button"
                        :class="
                            form.minutes_per_period === minute
                                ? 'bg-brand-400 text-white'
                                : 'bg-white hover:bg-brand-400 hover:text-white text-black'
                        "
                        class="text-brand-text-600 grid h-10 w-10 place-items-center rounded-full border border-gray-500 font-semibold"
                        @click="setDuration(minute)"
                    >
                        {{ minute }}
                    </button>

                    <button
                        type="button"
                        class="text-brand-text-600 grid h-10 w-10 place-items-center rounded-full border border-gray-500 font-semibold"
                        @click="openOtherDurationForm"
                    >
                        <span class="mb-0.5 text-xl">+</span>
                    </button>
                </div>

                <div
                    v-if="
                        isOtherDuration &&
                        formStep === 2 &&
                        !!!form.minutes_per_period
                    "
                    class="relative flex w-full items-center justify-center gap-3"
                >
                    <button
                        type="button"
                        class="absolute right-0 mr-[0.2rem] h-[calc(2.25rem-0.4rem)] rounded-[calc(0.375rem-0.1rem)] border bg-brand-400 px-2 text-xs font-semibold text-white"
                        @click="setOtherDuration"
                    >
                        {{ $t("schoolPeriodFormWrapper.setMinute") }}
                    </button>
                    <TextInput
                        v-model="otherDuration"
                        placeholder="50"
                        type="number"
                        class="w-full"
                    />
                </div>

                <div
                    v-if="formStep === 2"
                    class="flex w-full items-center justify-end gap-3"
                >
                    <TertiaryButton
                        :disabled="formStep !== 2"
                        @click="revertStep"
                        >{{ $t("schoolPeriodFormWrapper.back") }}
                    </TertiaryButton>
                    <PrimaryButton
                        :disabled="formStep !== 2 || !!!form.minutes_per_period"
                        @click="updateStep"
                        >{{ $t("schoolPeriodFormWrapper.next") }}
                    </PrimaryButton>
                </div>

                <div
                    v-if="formStep === 2"
                    class="text-brand-text-600 -mb-2 flex w-full gap-1.5 text-xs"
                >
                    <InformationCircleIcon
                        class="inline-block h-4 w-4 stroke-2"
                    />
                    {{ $t("schoolPeriodFormWrapper.hintCustomDuration") }}
                </div>
            </fieldset>

            <fieldset
                :class="{ 'opacity-50': formStep !== 3 }"
                class="flex h-fit w-full flex-col items-center gap-4 rounded-md border border-gray-400 p-4 shadow-sm"
            >
                <div class="flex w-full items-start justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                        <span class="text-sm font-semibold">
                            {{ $t("schoolPeriodFormWrapper.step") }}
                        </span>

                        <span
                            class="grid h-5 w-5 place-items-center rounded-full border border-black text-xs font-semibold"
                        >
                            {{ $t("schoolPeriodFormWrapper.3") }}
                        </span>
                    </div>

                    <div class="flex flex-col items-end">
                        <h3 class="text-sm font-semibold">
                            {{ $t("schoolPeriodFormWrapper.periodsPerDay") }}
                        </h3>
                        <h3
                            v-if="formStep === 3"
                            class="text-brand-text-600 text-right text-sm"
                        >
                            {{ $t("schoolPeriodFormWrapper.howManyPeriods") }}
                        </h3>
                    </div>
                </div>

                <div
                    v-if="formStep === 3"
                    class="flex w-full items-center justify-center gap-3"
                >
                    <button
                        v-for="(session, s) in periods"
                        :key="s"
                        :disabled="formStep !== 3"
                        type="button"
                        :class="
                            form.no_of_periods === session
                                ? 'bg-brand-400 text-white'
                                : 'bg-white hover:bg-brand-400 hover:text-white text-black'
                        "
                        class="text-brand-text-600 grid h-10 w-10 place-items-center rounded-full border border-gray-500 font-semibold"
                        @click="setPeriod(session)"
                    >
                        {{ session }}
                    </button>

                    <button
                        type="button"
                        class="text-brand-text-600 grid h-10 w-10 place-items-center rounded-full border border-gray-500 font-semibold"
                        @click="isOtherPeriod = true"
                    >
                        <span class="mb-0.5 text-xl">+</span>
                    </button>
                </div>

                <div
                    v-if="isOtherPeriod && formStep === 3"
                    class="relative flex w-full items-center justify-center gap-3"
                >
                    <button
                        type="button"
                        class="absolute right-0 mr-[0.2rem] h-[calc(2.25rem-0.4rem)] rounded-[calc(0.375rem-0.1rem)] border bg-brand-400 px-2 text-xs font-semibold text-white"
                        @click="setOtherPeriod"
                    >
                        {{ $t("schoolPeriodFormWrapper.setPeriods") }}
                    </button>
                    <TextInput
                        v-model="otherPeriod"
                        placeholder="50"
                        type="number"
                        class="w-full"
                    />
                </div>

                <div
                    v-if="formStep === 3"
                    class="flex w-full items-center justify-end gap-3"
                >
                    <TertiaryButton
                        :disabled="formStep !== 3"
                        @click="revertStep"
                        >{{ $t("schoolPeriodFormWrapper.back") }}
                    </TertiaryButton>
                    <PrimaryButton
                        :disabled="formStep !== 3 || !!!form.no_of_periods"
                        @click="updateStep"
                        >{{ $t("schoolPeriodFormWrapper.next") }}
                    </PrimaryButton>
                </div>

                <div
                    v-if="formStep === 3"
                    class="text-brand-text-600 -mb-2 flex w-full gap-1.5 text-xs"
                >
                    <InformationCircleIcon
                        class="inline-block h-4 w-4 stroke-2"
                    />
                    {{ $t("schoolPeriodFormWrapper.next") }}

                    {{ $t("schoolPeriodFormWrapper.hint") }}
                </div>
            </fieldset>

            <fieldset
                :class="{ 'opacity-50': formStep !== 4 }"
                class="flex h-fit w-full flex-col items-center gap-4 rounded-md border border-gray-400 p-4 shadow-sm"
            >
                <div class="flex w-full items-start justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                        <span class="text-sm font-semibold">{{
                            $t("schoolPeriodFormWrapper.step")
                        }}</span>

                        <span
                            class="grid h-5 w-5 place-items-center rounded-full border border-black text-xs font-semibold"
                        >
                            4
                        </span>
                    </div>

                    <div class="flex flex-col items-end">
                        <h3 class="text-sm font-semibold">
                            {{ $t("schoolPeriodFormWrapper.customPeriods") }}
                        </h3>
                        <h3
                            v-if="formStep === 4"
                            class="text-brand-text-600 text-right text-sm"
                        >
                            {{ $t("schoolPeriodFormWrapper.addCustomPeriods") }}
                            <span class="inline-block">{{
                                $t("schoolPeriodFormWrapper.breakTime")
                            }}</span
                            >,
                            <span class="inline-block">{{
                                $t("schoolPeriodFormWrapper.lunchBreak")
                            }}</span
                            >,
                            <span class="inline-block">{{
                                $t("schoolPeriodFormWrapper.recess")
                            }}</span
                            >,
                            <span class="inline-block">{{
                                $t("schoolPeriodFormWrapper.homeroom")
                            }}</span>
                        </h3>
                    </div>
                </div>

                <div v-if="formStep === 4" class="flex w-full flex-col gap-3">
                    <template
                        v-for="(customPeriod, c) in form.custom_periods"
                        :key="c"
                    >
                        <div
                            v-if="c !== 0"
                            class="relative mt-2 h-1 w-full border-t border-gray-300"
                        >
                            <XCircleIcon
                                class="absolute right-3 top-3 h-4 w-4 cursor-pointer stroke-gray-500 stroke-2 hover:stroke-negative-100"
                                @click="removeCustomPeriod(c)"
                            />
                        </div>

                        <div
                            class="flex w-full flex-col items-center justify-center gap-2"
                        >
                            <TextInput
                                v-model="customPeriod.name"
                                :placeholder="
                                    $t(
                                        'schoolPeriodFormWrapper.customPeriodPlaceHolder'
                                    )
                                "
                                class="w-full"
                                :label="
                                    $t(
                                        'schoolPeriodFormWrapper.customPeriodLabel'
                                    )
                                "
                                required
                            />

                            <div
                                class="flex w-full items-center justify-start gap-3 py-1"
                            >
                                <span
                                    class="text-brand-text-600 min-w-[80.64px] pl-0.5 text-xs font-semibold"
                                    >{{
                                        $t(
                                            "schoolPeriodFormWrapper.durationMin"
                                        )
                                    }}
                                </span>

                                <button
                                    v-for="(minute, m) in [
                                        15, 20, 30, 40, 45, 60,
                                    ]"
                                    :key="m"
                                    type="button"
                                    :class="{
                                        ' bg-brand-400 text-white':
                                            customPeriod.duration === minute,
                                    }"
                                    class="text-brand-text-600 grid h-8 w-8 place-items-center rounded-full border border-gray-500 text-xs font-semibold"
                                    @click="setCustomPeriodDuration(c, minute)"
                                >
                                    {{ minute }}
                                </button>
                            </div>

                            <div
                                class="flex w-full items-start justify-start gap-3 py-1"
                            >
                                <span
                                    class="text-brand-text-600 flex h-8 min-w-[85.5px] items-center pl-0.5 text-xs font-semibold"
                                >
                                    {{
                                        $t(
                                            "schoolPeriodFormWrapper.beforePeriod"
                                        )
                                    }}
                                </span>

                                <div class="flex flex-wrap items-center gap-3">
                                    <button
                                        v-for="(period, p) in Array.from(
                                            Array(form.no_of_periods).keys()
                                        ).map((i) => i + 1)"
                                        :key="p"
                                        type="button"
                                        :class="{
                                            ' bg-brand-400 text-white':
                                                customPeriod.before_period ===
                                                period,
                                        }"
                                        class="text-brand-text-600 flex h-8 w-8 items-center justify-center rounded-full border border-gray-500 text-xs font-semibold"
                                        @click="
                                            setCustomPeriodBefore(c, period)
                                        "
                                    >
                                        <span>{{ period }}</span>
                                        <sup>{{
                                            addSuffix(period).slice(
                                                addSuffix(period).length - 2,
                                                addSuffix(period).length
                                            )
                                        }}</sup>
                                    </button>
                                </div>
                            </div>

                            <div
                                class="flex w-full items-center justify-start gap-3 py-1"
                            >
                                <span
                                    class="text-brand-text-600 min-w-[85.5px] pl-0.5 text-xs font-semibold"
                                >
                                    {{ $t("schoolPeriodFormWrapper.day") }}
                                </span>

                                <button
                                    v-for="(day, d) in [
                                        t('schoolPeriodFormWrapper.days[0]'),
                                        t('schoolPeriodFormWrapper.days[1]'),
                                        t('schoolPeriodFormWrapper.days[2]'),
                                        t('schoolPeriodFormWrapper.days[3]'),
                                        t('schoolPeriodFormWrapper.days[4]'),
                                    ]"
                                    :key="d"
                                    :class="{
                                        ' bg-brand-400 text-white':
                                            customPeriod.days.includes(day),
                                    }"
                                    type="button"
                                    class="text-brand-text-600 grid h-8 w-8 place-items-center rounded-full border border-gray-500 text-xs font-semibold"
                                    @click="updateCustomPeriodDays(c, day)"
                                >
                                    <span class="capitalize">
                                        {{ day.slice(0, 2) }}
                                    </span>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <div
                    v-if="formStep === 4"
                    class="text-brand-text-600 -mb-2 flex w-full gap-1.5 text-xs"
                >
                    <InformationCircleIcon
                        class="inline-block h-4 w-4 stroke-2"
                    />
                    {{ $t("schoolPeriodFormWrapper.finishHint") }}
                </div>

                <div
                    v-if="formStep === 4"
                    class="flex w-full items-center justify-end gap-3"
                >
                    <button
                        type="button"
                        class="text-brand-text-600 flex w-fit items-center gap-2 rounded-md border bg-brand-100 px-4 py-2 text-xs font-semibold"
                        @click="addCustomPeriod"
                    >
                        <PlusCircleIcon class="h-4 w-4 stroke-2" />
                        <span>
                            {{ $t("schoolPeriodFormWrapper.addNewPeriod") }}
                        </span>
                    </button>
                    <TertiaryButton
                        :disabled="formStep !== 4 || !!!form.start_time"
                        @click="revertStep"
                        >{{ $t("schoolPeriodFormWrapper.back") }}
                    </TertiaryButton>
                </div>
            </fieldset>
        </form>

        <FormIntroduction v-else @submit="renderForm" />

        <div v-if="formStep === 4" class="sticky bottom-0 h-fit w-full p-4">
            <button
                class="grid h-12 w-full place-items-center rounded-md bg-brand-400 font-semibold text-white"
                @click="submit"
            >
                {{ $t("schoolPeriodFormWrapper.finish") }}
            </button>
        </div>
    </div>
</template>

<script setup>
import {
    InformationCircleIcon,
    PlusCircleIcon,
    XCircleIcon,
} from "@heroicons/vue/24/outline";
import FormIntroduction from "@/Views/Admin/GettingStarted/Schedule/FormIntroduction.vue";
import { nextTick, ref } from "vue";
import TextInput from "@/Components/TextInput.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { addSuffix } from "@/utils.js";
import { router } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const isFormRendered = ref(false);

const form = ref({
    start_time: "",
    minutes_per_period: null,
    no_of_periods: null,
    level_category_ids: [1, 2, 3],
    custom_periods: [
        {
            name: "",
            duration: null,
            before_period: null,
            days: [
                t("schoolPeriodFormWrapper.days[0]"),
                t("schoolPeriodFormWrapper.days[1]"),
                t("schoolPeriodFormWrapper.days[2]"),
                t("schoolPeriodFormWrapper.days[3]"),
                t("schoolPeriodFormWrapper.days[4]"),
            ],
        },
    ],
});

const formStep = ref(0);

function renderForm() {
    isFormRendered.value = true;
    formStep.value = 1;
    nextTick(() => {
        document.getElementById("startTime").focus();
    });
}

function updateStep() {
    formStep.value = formStep.value + 1;
    nextTick(() => {
        if (formStep.value === 1) {
            document.getElementById("startTime").focus();
        }
    });
}

function revertStep() {
    formStep.value = formStep.value - 1;
    if (formStep.value === 1) {
        nextTick(() => {
            document.getElementById("startTime").focus();
        });
    }
}

function setDuration(minute) {
    form.value.minutes_per_period = minute;
}

function setPeriod(session) {
    form.value.no_of_periods = session;
}

function openOtherDurationForm() {
    isOtherDuration.value = true;
    form.value.minutes_per_period = null;
}

function setCustomPeriodDuration(index, minute) {
    form.value.custom_periods[index].duration = minute;
}

function setCustomPeriodBefore(index, period) {
    form.value.custom_periods[index].before_period = period;
}

function updateCustomPeriodDays(index, day) {
    if (form.value.custom_periods[index].days.includes(day)) {
        form.value.custom_periods[index].days = form.value.custom_periods[
            index
        ].days.filter((d) => d !== day);
    } else {
        form.value.custom_periods[index].days.push(day);
    }
}

function addCustomPeriod() {
    form.value.custom_periods.push({
        name: "",
        duration: null,
        before_period: null,
        days: [
            t("schoolPeriodFormWrapper.days[0]"),
            t("schoolPeriodFormWrapper.days[1]"),
            t("schoolPeriodFormWrapper.days[2]"),
            t("schoolPeriodFormWrapper.days[3]"),
            t("schoolPeriodFormWrapper.days[4]"),
        ],
    });
}

function removeCustomPeriod(index) {
    form.value.custom_periods.splice(index, 1);
}

const durations = ref([30, 40, 45, 60, 90]);
const otherDuration = ref(null);
const isOtherDuration = ref(false);

function setOtherDuration() {
    if (!!otherDuration.value) {
        durations.value = [30, 40, 45, 60, 90];
        durations.value.push(parseInt(otherDuration.value));
        durations.value = Array.from(new Set(durations.value));

        durations.value.sort((a, b) => a - b);
        setDuration(parseInt(otherDuration.value));

        isOtherDuration.value = false;
        otherDuration.value = null;
    }
}

const periods = ref([5, 6, 7, 8, 9]);
const otherPeriod = ref(null);
const isOtherPeriod = ref(false);

function setOtherPeriod() {
    if (!!otherPeriod.value) {
        periods.value = [5, 6, 7, 8, 9];
        periods.value.push(parseInt(otherPeriod.value));
        periods.value = Array.from(new Set(periods.value));

        periods.value.sort((a, b) => a - b);
        setPeriod(parseInt(otherPeriod.value));

        isOtherPeriod.value = false;
        otherPeriod.value = null;
    }
}

function submit() {
    router.post(
        "/school-periods/create",
        {
            school_periods: [form.value],
        },
        {
            onSuccess: () => {
                router.get("/getting-started/school-schedule");
            },
        }
    );
}
</script>

<style scoped></style>
