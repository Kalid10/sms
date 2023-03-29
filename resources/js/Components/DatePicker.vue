<template>

    <label class="relative flex flex-col gap-1">
        <span v-if="label" class="">
            <span class="pl-0.5 text-sm font-semibold text-gray-500">{{ label }}</span>
            <span v-if="required" class="pl-0.5 text-xs text-red-600">*</span>
        </span>

        <span
            class="relative flex h-10 w-full items-center justify-between rounded-md border border-gray-200 py-2 px-3 placeholder:text-sm"
            @click="viewPanel = !viewPanel"
        >
            <input type="date" class="sr-only" :required="required" :disabled="disabled" />
            <span :class="[!! selectedDate ? 'text-black' : 'text-gray-500']" class="truncate whitespace-nowrap text-sm">
                {{ selectedDate ?? placeholder }}
            </span>
            <CalendarIcon class="h-4 w-4 stroke-gray-500 stroke-2" />

        </span>

        <span v-if="viewPanel" ref="panelViewer" class="absolute bottom-0 right-0 z-50 -mb-2 min-w-[282.98px] translate-y-full rounded-md border bg-white drop-shadow-md" @click="elementClick">

                <span class="flex min-w-fit flex-col overflow-hidden rounded-md">

                    <span class="col-span-7 flex h-12 min-w-fit items-center justify-between border-b bg-neutral-50 p-2 shadow">

                        <ChevronLeftIcon class="h-4 w-4 min-w-fit cursor-pointer stroke-gray-500 stroke-[3]" @click="previous" />
                        <span class="flex min-w-fit select-none gap-1 text-sm font-semibold text-gray-500">
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
                        <ChevronRightIcon class="h-4 w-4 cursor-pointer stroke-gray-500 stroke-[3]" @click="next" />

                    </span>

                    <span v-if="panel === 'date'" ref="daysPanel" class="grid grid-cols-7 p-2">

                        <span v-for="i in days" :key="i" class="grid place-items-center p-2.5 text-xs font-medium text-gray-500">
                            {{ i }}
                        </span>

                        <span v-for="i in offsetDays" :key="i" class="grid place-items-center p-2.5 text-sm text-gray-500">
                            {{ numberOfPreviousDays - offsetDays + i }}
                        </span>

                        <button
                            v-for="i in numberOfDays" :key="i" type="button"
                            :class="[
                                isDateSelected(i).value ?
                                    range ?
                                    isDateSelected(i).range === 'start' ?
                                    'rounded-l-md bg-black text-white' :
                                    'rounded-r-md bg-black text-white' :
                                    'rounded-md bg-black text-white' :
                                    'hover:bg-black/10',
                                isBetweenRange(i) ? 'bg-black/10' : '',
                            ]"
                            class="grid place-items-center p-2.5 text-sm font-medium focus:outline-none"
                            @click="selectDate(i)"
                        >
                            {{ i }}
                        </button>

                        <span v-for="k in 7- ((offsetDays + numberOfDays) % 7)" :key="k" class="grid place-items-center p-2.5 text-sm text-gray-500">
                            {{ k }}
                        </span>

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

                    <span v-if="panel === 'date'" class="grid place-items-center p-2">

                        <button
                            v-if="! range"
                            type="button"
                            :class="modelValue?.toDateString() === new Date().toDateString() ? 'bg-black/10 border-black text-black' : 'text-gray-500 hover:bg-black/10'"
                            class="flex h-10 w-full items-center justify-center gap-1 rounded-md border text-center text-sm focus:outline-none" @click="selectToday"
                        >
                            <span>Today</span>
                            {{ new Date().toLocaleDateString() }}
                        </button>

                        <button
                            v-else
                            type="button"
                            :class="!! startDate ? 'bg-black/5 border-black text-black' : 'text-gray-500'"
                            :disabled="!! ! startDate"
                            class="flex h-10 w-full items-center justify-center gap-1 rounded-md border text-center text-sm focus:outline-none" @click="clearRange"
                        >
                            <span>Clear</span>
                        </button>

                    </span>

                </span>

            </span>

    </label>

</template>

<script setup>
import { ref, nextTick, computed } from "vue";
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
        default: null
    },
    startDate: {
        type: [Date, null],
        default: null
    },
    endDate: {
        type: [Date, null],
        default: null
    },
    range: {
        type: Boolean,
        default: false
    },
    availableYears: {
        type: Array,
        default: () => [2020, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030]
    }
})

const emits = defineEmits(['update:modelValue', 'update:startDate', 'update:endDate'])

const viewPanel = ref(false)
const panelViewer = ref(null)

onClickOutside(panelViewer, () => {
    viewPanel.value = false
})

const panel = ref('date')
function changePanel(to) {
    panel.value = to
    emits('update:modelValue', null)
}

const months = [
    'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
]
const days = [
    'Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'
]

const today = new Date()
const selectedYear = ref(!! props.modelValue ? props.modelValue.getFullYear() : today.getFullYear())
const selectedMonth = ref(!! props.modelValue ? props.modelValue.getMonth() : today.getMonth())
const selectedDate = computed(() => {

    if (!! props.modelValue || props.startDate || props.endDate) {

        if (! props.range) {

            if (props.modelValue) {
                return props.modelValue.toLocaleDateString()
            }

        } else {

            let selectedDate = ''

            if (props.startDate) {
                selectedDate = props.startDate.toLocaleDateString() + ' - '
            }

            if (props.endDate) {
                selectedDate += props.endDate.toLocaleDateString()
            }

            return selectedDate
        }

    }

    return null
})

const offsetDays = computed(() => new Date(selectedYear.value, selectedMonth.value).getDay())
const numberOfPreviousDays = computed(() => 35 - (new Date(selectedMonth.value === 0 ? selectedYear.value - 1 : selectedYear.value, selectedMonth.value - 1 > -1 ? selectedMonth.value - 1 : 11, 35).getDate()))
const numberOfDays = computed(() => 35 - (new Date(selectedYear.value, selectedMonth.value, 35).getDate()))

function previous() {

    switch (panel.value) {
        case 'date':
            if (selectedMonth.value === 0) {
                selectedMonth.value = 11
                selectedYear.value--
            } else {
                selectedMonth.value--
            }
            break
        case 'month':
            selectedYear.value--
            break
        case 'year':
            break
    }

}

function next() {

    switch (panel.value) {
        case 'date':
            if (selectedMonth.value === 11) {
                selectedMonth.value = 0
                selectedYear.value++
            } else {
                selectedMonth.value++
            }
            break
        case 'month':
            selectedYear.value++
            break
        case 'year':
            break
    }

}

function isBetweenRange(date) {

    const selectedDate = new Date(selectedYear.value, selectedMonth.value, date)

    if (props.range && !! props.startDate && !! props.endDate) {
        return selectedDate > props.startDate && selectedDate < props.endDate
    }

    return false;
}

function isDateSelected(date) {

    const selectedDate = new Date(selectedYear.value, selectedMonth.value, date).toDateString()

    if (! props.range) {
        return {
            value: !! props.modelValue && selectedDate === props.modelValue.toDateString()
        }
    } else {
        return {
            value: !! props.startDate && selectedDate === props.startDate.toDateString() ||
                !! props.endDate && selectedDate === props.endDate.toDateString(),
            range: !! props.startDate && selectedDate === props.startDate.toDateString() ? 'start' :
                !! props.endDate && selectedDate === props.endDate.toDateString() ? 'end' : null
        }
    }

}

function isStartDateSelected() {

}

function selectDate(date) {
    if (! props.range) {
        emits('update:modelValue', new Date(selectedYear.value, selectedMonth.value, date))
    } else {

        if (!! ! props.startDate) {
            emits('update:startDate', new Date(selectedYear.value, selectedMonth.value, date))
        } else {
            emits('update:endDate', new Date(selectedYear.value, selectedMonth.value, date))
        }
    }

}

function selectToday() {
    selectedMonth.value = new Date().getMonth()
    selectedYear.value = new Date().getFullYear()
    emits('update:modelValue', new Date())
}

function clearRange() {
    selectToday()
    emits('update:startDate', null)
    emits('update:endDate', null)
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

* {
    @apply select-none;
}

</style>
