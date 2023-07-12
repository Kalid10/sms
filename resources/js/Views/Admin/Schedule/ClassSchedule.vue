<template>
    <div class="mx-auto min-w-full">
        <div class="mx-auto my-4 max-w-3xl text-center">
            <h2
                class="text-4xl font-extrabold leading-tight tracking-tight text-gray-900 dark:text-white"
            >
                {{ $t('classSchedule.schedule')}}
            </h2>
        </div>

        <div
            class="mx-auto flex max-w-3xl flex-row items-center justify-center gap-4 lg:mt-16"
        >
            <SelectInput
                v-model="month"
                :options="months"
                :placeholder="$t('classSchedule.selectMonthPlaceholder')"
                class="w-9/12"
            />

            <PrimaryButton
                :title="$t('classSchedule.addScheduleTitle')"
                class="max-w-full"
                @click="showModalToggle"
            />
        </div>

        <div class="flex justify-center">
            <p
                v-if="filteredSchedules.length === 0"
                class="mt-5 text-xl font-semibold text-gray-700"
            >
                 {{ $t('classSchedule.noSchoolScheduleIn')}}  {{ months[month - 1].label }} {{ year }}
            </p>
            <div v-else class="mx-auto flex flex-col gap-2">
                <h1
                    v-if="!month"
                    class="mt-5 w-full justify-center text-lg font-medium text-gray-700"
                >
                    {{ filteredSchedules.length }} {{ $t('classSchedule.schedules')}}
                </h1>
                <h1
                    v-else
                    class="mt-5 w-full justify-center text-lg font-medium text-gray-700"
                >
                    {{ filteredSchedules.length }} {{ $t('classSchedule.schedulesIn')}}
                    {{ months[month - 1].label }} {{ year }}
                </h1>
                <TextInput
                    v-model="query"
                    class="w-full"
                    :placeholder="$t('classSchedule.queryPlaceholder')"
                />
            </div>
        </div>
        <div class="mx-auto mt-8 flow-root w-10/12 sm:mt-12 lg:mt-16">
            <div class="-my-4 divide-y divide-gray-200">
                <div
                    v-for="(schoolSchedule, index) in filteredSchedules"
                    :key="index"
                    class="relative flex flex-col gap-2 py-4 sm:flex-row sm:items-center sm:gap-6"
                >
                    <p
                        class="border-fro w-32 shrink-0 border-l-2 pl-3 text-xs font-normal text-gray-500 dark:text-gray-400 sm:text-right"
                    >
                        {{
                            moment(schoolSchedule.start_date).format(
                                "MMMM Do YYYY, h:mm:ss a"
                            )
                        }}
                    </p>
                    <h3 class="text-md font-semibold text-gray-900">
                        <p class="hover:underline">
                            {{ schoolSchedule.title }}
                        </p>
                        <p class="text-sm">
                            {{ schoolSchedule.body }}
                        </p>
                    </h3>
                    <p
                        class="absolute top-0 right-0 mb-3 text-xs font-normal text-gray-500"
                    >
                        {{ schoolSchedule.tags }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <SchoolSchedule v-if="showModal"/>
</template>

<script setup>
import {ref, watch, watchEffect} from "vue";
import SchoolSchedule from "@/Views/Admin/GettingStarted/Schedule/SchoolSchedule.vue";
import {router} from "@inertiajs/vue3";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import moment from "moment";

import {useI18n} from "vue-i18n";
const {t} = useI18n()
const showModal = ref(false);

function showModalToggle() {
    showModal.value = !showModal.value;
}

const query = ref("");

function search() {
    router.get(
        "/admin/schedules",
        {
            search: query.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
}

const key = ref(0);
watch([query], () => {
    search();
});

const months = [
    {
        label: t('classSchedule.months[0]'),
        value: 1,
    },
    {
        label:  t('classSchedule.months[1]'),
        value: 2,
    },
    {
        label:  t('classSchedule.months[2]'),
        value: 3,
    },
    {
        label:  t('classSchedule.months[3]'),
        value: 4,
    },
    {
        label:  t('classSchedule.months[4]'),
        value: 5,
    },
    {
        label:  t('classSchedule.months[5]'),
        value: 6,
    },
    {
        label:  t('classSchedule.months[6]'),
        value: 7,
    },
    {
        label:  t('classSchedule.months[7]'),
        value: 8,
    },
    {
        label:  t('classSchedule.months[8]'),
        value: 9,
    },
    {
        label: t('classSchedule.months[9]'),
        value: 10,
    },
    {
        label:  t('classSchedule.months[10]'),
        value: 11,
    },
    {
        label:  t('classSchedule.months[11]'),
        value: 12,
    },
];

const props = defineProps({
    schoolSchedules: {
        type: Array,
        required: true,
    },
});
const month = ref(null);
const year = ref(new Date().getFullYear());

let filteredSchedules = ref([]);

watchEffect(() => {
    filteredSchedules.value = props.schoolSchedules.filter((schedule) => {
        if (!month.value) {
            return true;
        }
        const scheduleDate = new Date(schedule.start_date);
        return (
            scheduleDate.getMonth() + 1 === month.value &&
            scheduleDate.getFullYear() === year.value
        );
    });
});
</script>
