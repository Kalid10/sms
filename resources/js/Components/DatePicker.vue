<template>

    <label class="flex flex-col gap-1">
            <span v-if="label" class="">
                <span class="pl-0.5 text-sm font-semibold text-gray-500">{{ label }}</span>
                <span v-if="required" class="pl-0.5 text-xs text-red-600">*</span>
            </span>
        <input type="date" class="sr-only" :required="required" :disabled="disabled" />
        <span
            ref="datePicker"
            class="relative flex h-10 w-full items-center justify-between rounded-md border border-gray-200 py-2 px-3 placeholder:text-sm"
            @click="displayPicker = false"
        >
            <span :class="[!! selectedDate ? 'text-black' : 'text-gray-500']" class="text-sm">
                {{ selectedDate ?? placeholder }}
            </span>
            <CalendarIcon class="h-4 w-4 stroke-gray-500 stroke-2" />

            <span v-if="displayPicker" class="absolute bottom-0 right-0 -mb-2 translate-y-full rounded-md border bg-white drop-shadow-md">

                <span class="flex min-w-fit flex-col overflow-hidden rounded-md">

                    <span class="col-span-7 flex h-12 items-center justify-between border-b bg-neutral-50 p-2 shadow">

                        <ChevronLeftIcon class="h-4 w-4 cursor-pointer stroke-gray-500 stroke-[3]" @click="previousMonth" />
                        <span class="flex select-none gap-1 text-sm font-semibold text-gray-500">
                            <button v-if="panel === 'date'" type="button" @click="changePanel('month')">
                                {{ months[selectedMonth] }}
                            </button>
                            <button v-if="panel === 'month' || panel === 'date'" type="button" @click="changePanel('year')">
                                {{ selectedYear }}
                            </button>
                            <span v-else>
                                Select Year
                            </span>
                        </span>
                        <ChevronRightIcon class="h-4 w-4 cursor-pointer stroke-gray-500 stroke-[3]" @click="nextMonth" />

                    </span>

                    <span v-if="panel === 'date'" ref="daysPanel" class="grid grid-cols-7 p-2">

                        <span v-for="i in offsetDays" :key="i" class="grid place-items-center p-2.5" />

                        <button
                            v-for="i in numberOfDays" :key="i" type="button"
                            :class="isDateSelected(i) ? 'bg-black text-white' : 'hover:bg-black/10'"
                            class="grid place-items-center rounded-md p-2.5 text-sm focus:outline-none"
                            @click="selectDate(i)"
                        >
                            {{ i }}
                        </button>

                    </span>

                    <span v-else-if="panel === 'month'" ref="monthsPanel" class="grid grid-cols-3 p-2">

                        <button
                            v-for="(month, m) in months" :key="m" type="button"
                            class="grid place-items-center rounded-md p-2.5 text-sm focus:outline-none"
                            :class="isMonthSelected(m) ? 'bg-black text-white' : 'hover:bg-black/10'"
                            @click="selectMonth(m)"
                        >
                            {{ month }}
                        </button>

                    </span>

                    <span v-else ref="yearsPanel" class="grid min-w-[16rem] grid-cols-4 p-2">

                        <button
                            v-for="(year, y) in availableYears" :key="y" type="button"
                            class="grid place-items-center rounded-md p-2.5 text-sm focus:outline-none"
                            :class="isYearSelected(year) ? 'bg-black text-white' : 'hover:bg-black/10'"
                            @click="selectYear(year)"
                        >
                            {{ year }}
                        </button>

                    </span>

                    <span class="grid place-items-center p-2">

                        <button
                            type="button"
                            :class="modelValue?.toDateString() === new Date().toDateString() ? 'bg-black text-white' : 'hover:bg-black/10'"
                            class="flex h-10 w-full items-center justify-center gap-1 rounded-md border-2 text-center text-sm text-gray-500 focus:outline-none" @click="selectToday"
                        >
                            <span>Today</span>
                            {{ new Date().toLocaleDateString() }}
                        </button>

                    </span>

                </span>

            </span>

        </span>
    </label>

</template>

<script setup>
import { ref, computed } from "vue";
import { onClickOutside } from "@vueuse/core";
import { ChevronLeftIcon, ChevronRightIcon, CalendarIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    label: {
        type: String,
        default: null
    },
    required: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    },
    placeholder: {
        type: String,
        default: 'Select a date'
    },
    modelValue: {
        type: [Date, null],
        required: true
    },
    availableYears: {
        type: Array,
        default: () => [2020, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030]
    }
})

const emits = defineEmits(['update:modelValue'])

const displayPicker = ref(false)
function toggleDisplayPicker() {
    displayPicker.value = !displayPicker.value
}

const datePicker = ref(null)
onClickOutside(datePicker, () => {
    toggleDisplayPicker()
})

const panel = ref('date')
function changePanel(to) {
    panel.value = to
}

const months = [
    'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
]

const today = new Date()
const selectedYear = ref(!! props.modelValue ? props.modelValue.getFullYear() : today.getFullYear())
const selectedMonth = ref(!! props.modelValue ? props.modelValue.getMonth() : today.getMonth())
const selectedDate = computed(() => {
    if (props.modelValue) {
        return props.modelValue.toLocaleDateString()
    } else {
        return null
    }
})

const offsetDays = computed(() => new Date(selectedYear.value, selectedMonth.value).getDay())
const numberOfDays = computed(() => 35 - (new Date(selectedYear.value, selectedMonth.value, 35).getDate()))

function previousMonth() {
    if (selectedMonth.value === 0) {
        selectedMonth.value = 11
        selectedYear.value--
    } else {
        selectedMonth.value--
    }
}

function nextMonth() {
    if (selectedMonth.value === 11) {
        selectedMonth.value = 0
        selectedYear.value++
    } else {
        selectedMonth.value++
    }
}

function isDateSelected(date) {
    return !! props.modelValue && date === props.modelValue.getDate() &&
        selectedMonth.value === props.modelValue.getMonth() &&
        selectedYear.value === props.modelValue.getFullYear()
}

function selectDate(date) {
    emits('update:modelValue', new Date(selectedYear.value, selectedMonth.value, date))
}

function selectToday() {
    selectedMonth.value = new Date().getMonth()
    selectedYear.value = new Date().getFullYear()
    emits('update:modelValue', new Date())
}

function isMonthSelected(month_index) {
    return month_index === selectedMonth.value
}

function selectMonth(month_index) {
    selectedMonth.value = month_index
    panel.value = 'date'
}

function isYearSelected(year) {
    return year === selectedYear.value
}

function selectYear(year) {
    selectedYear.value = year
    panel.value = 'month'
}

</script>

<style scoped>

</style>
