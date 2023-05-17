<template>

    <div class="relative col-span-3 flex h-full flex-col items-center justify-between overflow-auto border-r">

        <form v-if="isFormRendered" class="flex w-full flex-col justify-start gap-4 p-4">

            <div class="flex w-full items-center justify-between">
                <div class="flex grow items-center justify-center gap-1 text-xs font-semibold text-gray-500">
                    Step
                    <div class="grid h-6 w-6 place-items-center rounded-full border bg-black text-xs font-semibold text-white">{{ formStep }}</div>
                    of 4
                </div>
            </div>

            <fieldset :class="{'opacity-50' : formStep !== 1}" class="flex h-fit w-full flex-col items-center gap-2 rounded-md border border-gray-400 p-4 shadow-sm">

                <div class="flex w-full items-start justify-between">

                    <div class="flex items-center gap-1.5">

                        <span class="text-sm font-semibold">
                            Step
                        </span>

                        <span class="grid h-5 w-5 place-items-center rounded-full border border-black text-xs font-semibold">
                            1
                        </span>

                    </div>

                    <div class="flex flex-col items-end">
                        <h3 class="text-sm font-semibold">Start time of the day</h3>
                        <h3 v-if="formStep === 1" class="text-right text-sm text-gray-500">What time does the first period of the school day start?</h3>
                    </div>

                </div>

                <input v-if="formStep === 1" id="startTime" v-model="form.start_time" :disabled="formStep !== 1" type="time" class="w-full appearance-none rounded-md border border-gray-200 font-semibold focus:border-gray-500 focus:ring-0" />

                <div v-if="formStep === 1" class="flex w-full items-center justify-end gap-3">
                    <PrimaryButton :disabled="formStep !== 1 || !!!form.start_time" @click="updateStep">Next</PrimaryButton>
                </div>

            </fieldset>

            <fieldset :class="{'opacity-50' : formStep !== 2}" class="flex h-fit w-full flex-col items-center gap-4 rounded-md border border-gray-400 p-4 shadow-sm">

                <div class="flex w-full items-start justify-between">

                    <div class="flex items-center gap-1.5">

                        <span class="text-sm font-semibold">
                            Step
                        </span>

                        <span class="grid h-5 w-5 place-items-center rounded-full border border-black text-xs font-semibold">
                            2
                        </span>

                    </div>

                    <div class="flex flex-col items-end">
                        <h3 class="text-sm font-semibold">Duration of a single period</h3>
                        <h3 v-if="formStep === 2" class="text-sm text-gray-500">How many minutes are in a single period?</h3>
                    </div>

                </div>

                <div v-if="formStep === 2" class="flex w-full items-center justify-center gap-3">

                    <button v-for="(minute, m) in durations" :key="m" :disabled="formStep !== 2" type="button" :class="form.minutes_per_period === minute ? 'bg-black text-white' : 'bg-white text-black'" class="grid h-10 w-10 place-items-center rounded-full border border-gray-500 font-semibold text-gray-500" @click="setDuration(minute)">
                        {{ minute }}
                    </button>

                    <button type="button" class="grid h-10 w-10 place-items-center rounded-full border border-gray-500 font-semibold text-gray-500" @click="isOtherDuration = true">
                        <span class="mb-0.5 text-xl">+</span>
                    </button>

                </div>

                <div v-if="isOtherDuration && formStep === 2" class="relative flex w-full items-center justify-center gap-3">
                    <button type="button" class="absolute right-0 mr-[0.2rem] h-[calc(2.25rem-0.4rem)] rounded-[calc(0.375rem-0.1rem)] border bg-black px-2 text-xs font-semibold text-white" @click="setOtherDuration">Set Minute</button>
                    <TextInput v-model="otherDuration" placeholder="50" type="number" class="w-full" />
                </div>

                <div v-if="formStep === 2" class="flex w-full items-center justify-end gap-3">
                    <TertiaryButton :disabled="formStep !== 2" @click="revertStep">Back</TertiaryButton>
                    <PrimaryButton :disabled="formStep !== 2 || !!!form.minutes_per_period" @click="updateStep">Next</PrimaryButton>
                </div>

                <div v-if="formStep === 2" class="-mb-2 flex w-full gap-1.5 text-xs text-gray-500">

                    <InformationCircleIcon class="inline-block h-4 w-4 stroke-2" />
                    For your periods with a custom duration, you can add them later in Step 4, "Custom Periods".

                </div>

            </fieldset>

            <fieldset :class="{'opacity-50' : formStep !== 3}" class="flex h-fit w-full flex-col items-center gap-4 rounded-md border border-gray-400 p-4 shadow-sm">

                <div class="flex w-full items-start justify-between gap-2">

                    <div class="flex items-center gap-1.5">

                        <span class="text-sm font-semibold">
                            Step
                        </span>

                        <span class="grid h-5 w-5 place-items-center rounded-full border border-black text-xs font-semibold">
                            3
                        </span>

                    </div>

                    <div class="flex flex-col items-end">
                        <h3 class="text-sm font-semibold">Periods per day</h3>
                        <h3 v-if="formStep === 3" class="text-right text-sm text-gray-500">How many periods (sessions) will a typical school day have?</h3>
                    </div>

                </div>

                <div v-if="formStep === 3" class="flex w-full items-center justify-center gap-3">

                    <button v-for="(session, s) in periods" :key="s" :disabled="formStep !== 3" type="button" :class="form.no_of_periods === session ? 'bg-black text-white' : 'bg-white text-black'" class="grid h-10 w-10 place-items-center rounded-full border border-gray-500 font-semibold text-gray-500" @click="setPeriod(session)">
                        {{ session }}
                    </button>

                    <button type="button" class="grid h-10 w-10 place-items-center rounded-full border border-gray-500 font-semibold text-gray-500" @click="isOtherPeriod = true">
                        <span class="mb-0.5 text-xl">+</span>
                    </button>

                </div>

                <div v-if="isOtherPeriod && formStep === 3" class="relative flex w-full items-center justify-center gap-3">
                    <button type="button" class="absolute right-0 mr-[0.2rem] h-[calc(2.25rem-0.4rem)] rounded-[calc(0.375rem-0.1rem)] border bg-black px-2 text-xs font-semibold text-white" @click="setOtherPeriod">Set Periods</button>
                    <TextInput v-model="otherPeriod" placeholder="50" type="number" class="w-full" />
                </div>

                <div v-if="formStep === 3" class="flex w-full items-center justify-end gap-3">
                    <TertiaryButton :disabled="formStep !== 3" @click="revertStep">Back</TertiaryButton>
                    <PrimaryButton :disabled="formStep !== 3 || !!!form.no_of_periods" @click="updateStep">Next</PrimaryButton>
                </div>

                <div v-if="formStep === 3" class="-mb-2 flex w-full gap-1.5 text-xs text-gray-500">

                    <InformationCircleIcon class="inline-block h-4 w-4 stroke-2" />
                    This does not include your recess, lunch break or any other custom periods

                </div>

            </fieldset>

            <fieldset :class="{'opacity-50' : formStep !== 4}" class="flex h-fit w-full flex-col items-center gap-4 rounded-md border border-gray-400 p-4 shadow-sm">

                <div class="flex w-full items-start justify-between gap-2">

                    <div class="flex items-center gap-1.5">

                        <span class="text-sm font-semibold">
                            Step
                        </span>

                        <span class="grid h-5 w-5 place-items-center rounded-full border border-black text-xs font-semibold">
                            4
                        </span>

                    </div>

                    <div class="flex flex-col items-end">
                        <h3 class="text-sm font-semibold">Custom Periods</h3>
                        <h3 v-if="formStep === 4" class="text-right text-sm text-gray-500">
                            Add your custom periods here.
                            Examples can be
                            <span class="inline-block">"Break time"</span>,
                            <span class="inline-block">"Lunch break"</span>,
                            <span class="inline-block">"Recess"</span>,
                            <span class="inline-block">"Homeroom"</span>
                        </h3>
                    </div>

                </div>

                <div v-if="formStep === 4" class="flex w-full flex-col gap-3">

                    <template v-for="(customPeriod, c) in form.custom_periods" :key="c">

                        <div v-if="c !== 0" class="relative mt-2 h-1 w-full border-t border-gray-300">
                            <XCircleIcon class="absolute right-3 top-3 h-4 w-4 cursor-pointer stroke-gray-500 stroke-2 hover:stroke-negative-100" @click="removeCustomPeriod(c)" />
                        </div>

                        <div class="flex w-full flex-col items-center justify-center gap-2">

                            <TextInput v-model="customPeriod.name" placeholder="Break time" class="w-full" label="Custom Period Name" required />

                            <div class="flex w-full items-center justify-start gap-3 py-1">

                                <span class="min-w-[80.64px] pl-0.5 text-xs font-semibold text-gray-500">Duration (min)</span>

                                <button v-for="(minute, m) in [30, 40, 45, 60, 90, 120]" :key="m" type="button" :class="{ 'border-2 !border-black bg-black text-white' : customPeriod.duration === minute }" class="grid h-8 w-8 place-items-center rounded-full border border-gray-500 text-xs font-semibold text-gray-500" @click="setCustomPeriodDuration(c, minute)">
                                    {{ minute }}
                                </button>

                            </div>

                            <div class="flex w-full items-start justify-start gap-3 py-1">

                                <span class="flex h-8 min-w-[85.5px] items-center pl-0.5 text-xs font-semibold text-gray-500">Before Period</span>

                                <div class="flex flex-wrap items-center gap-3">
                                    <button v-for="(period, p) in Array.from(Array(form.no_of_periods).keys()).map(i => i + 1)" :key="p" type="button" :class="{ 'border-2 !border-black bg-black text-white': customPeriod.before_period === period }" class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-500 text-xs font-semibold text-gray-500" @click="setCustomPeriodBefore(c, period)">
                                        <span>{{ period }}</span>
                                        <sup>{{ addSuffix(period).slice(addSuffix(period).length - 2, addSuffix(period).length) }}</sup>
                                    </button>
                                </div>

                            </div>

                            <div class="flex w-full items-center justify-start gap-3 py-1">

                                <span class="min-w-[85.5px] pl-0.5 text-xs font-semibold text-gray-500">Days</span>

                                <button v-for="(day, d) in ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']" :key="d" :class="{ 'border-2 !border-black bg-black text-white' : customPeriod.days.includes(day) }" type="button" class="grid h-8 w-8 place-items-center rounded-full border border-gray-500 text-xs font-semibold text-gray-500" @click="updateCustomPeriodDays(c, day)">
                                    <span class="capitalize">
                                        {{ day.slice(0, 2) }}
                                    </span>
                                </button>

                            </div>

                        </div>

                    </template>

                </div>

                <div v-if="formStep === 4" class="-mb-2 flex w-full gap-1.5 text-xs text-gray-500">

                    <InformationCircleIcon class="inline-block h-4 w-4 stroke-2" />
                    Click on the "Finish" button on the bottom of the screen to finish setting up your schedule.

                </div>

                <div v-if="formStep === 4" class="flex w-full items-center justify-end gap-3">
                    <button type="button" class="flex w-fit items-center gap-2 rounded-md border bg-gray-100 px-4 py-2 text-xs font-semibold text-gray-500" @click="addCustomPeriod">
                        <PlusCircleIcon class="h-4 w-4 stroke-2" />
                        <span>Add new Period</span>
                    </button>
                    <TertiaryButton :disabled="formStep !== 4 || !!!form.start_time" @click="revertStep">Back</TertiaryButton>
                </div>

            </fieldset>

        </form>

        <FormIntroduction v-else @submit="renderForm" />

        <div v-if="formStep === 4" class="sticky bottom-0 h-fit w-full p-4">
            <button class="grid h-12 w-full place-items-center rounded-md bg-black font-semibold text-white" @click="submit">Finish</button>
        </div>

    </div>

</template>

<script setup>
import { InformationCircleIcon, PlusCircleIcon, XCircleIcon } from "@heroicons/vue/24/outline";
import FormIntroduction from "@/Views/GettingStarted/Schedule/FormIntroduction.vue";
import {nextTick, ref} from "vue";
import TextInput from "@/Components/TextInput.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {addSuffix} from "@/utils.js";
import {router} from "@inertiajs/vue3";

const isFormRendered = ref(false)

const form = ref({
    start_time: "",
    minutes_per_period: null,
    no_of_periods: null,
    level_category_ids: [1, 2, 3],
    custom_periods: [{
        name: "",
        duration: null,
        before_period: null,
        days: ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']
    }]
})

const formStep = ref(0)

function renderForm() {
    isFormRendered.value = true
    formStep.value = 1
    nextTick(() => {
        document.getElementById('startTime').focus()
    })
}

function updateStep() {
    formStep.value = formStep.value + 1
    nextTick(() => {
        if (formStep.value === 1) {
            document.getElementById('startTime').focus()
        }
    })
}

function revertStep() {
    formStep.value = formStep.value - 1
    if (formStep.value === 1) {
        nextTick(() => {
            document.getElementById('startTime').focus()
        })
    }
}

function setDuration(minute) {
    form.value.minutes_per_period = minute
}

function setPeriod(session) {
    form.value.no_of_periods = session
}

function setCustomPeriodDuration(index, minute) {
    form.value.custom_periods[index].duration = minute
}

function setCustomPeriodBefore(index, period) {
    form.value.custom_periods[index].before_period = period
}

function updateCustomPeriodDays(index, day) {
    if (form.value.custom_periods[index].days.includes(day)) {
        form.value.custom_periods[index].days = form.value.custom_periods[index].days.filter(d => d !== day)
    } else {
        form.value.custom_periods[index].days.push(day)
    }
}

function addCustomPeriod() {
    form.value.custom_periods.push({
        name: "",
        duration: null,
        before_period: null,
        days: ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']
    })
}

function removeCustomPeriod(index) {
    form.value.custom_periods.splice(index, 1)
}

const durations = ref([30, 45, 60, 90, 120])
const otherDuration = ref(null)
const isOtherDuration = ref(false)

function setOtherDuration() {

    if (!!otherDuration.value) {

        durations.value = [30, 45, 60, 90, 120]
        durations.value.push(parseInt(otherDuration.value))
        durations.value = Array.from(new Set(durations.value))

        durations.value.sort((a, b) => a - b)
        setDuration(parseInt(otherDuration.value))

        isOtherDuration.value = false
        otherDuration.value = null

    }

}

const periods = ref([5, 6, 7, 8, 9])
const otherPeriod = ref(null)
const isOtherPeriod = ref(false)
function setOtherPeriod() {

    if (!!otherPeriod.value) {

        periods.value = [5, 6, 7, 8, 9]
        periods.value.push(parseInt(otherPeriod.value))
        periods.value = Array.from(new Set(periods.value))

        periods.value.sort((a, b) => a - b)
        setPeriod(parseInt(otherPeriod.value))

        isOtherPeriod.value = false
        otherPeriod.value = null

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
                router.get("/getting-started/class-schedule");
            },
        }
    );
}

</script>

<style scoped>

</style>
