<template>
    <div class="w-full">
        <div class="mb-4">
            <h2 class="text-base font-semibold text-gray-900">
                Class Schedule
            </h2>

            <p class="text-xs font-light text-gray-500">
                Below the title, there's a brief description of what the user
                can do on this page.
            </p>
        </div>

        <div class="flex w-full flex-col space-y-3">
            <TimePicker
                v-model="schoolPeriodForm.start_time"
                subtext="The start time of the day is the time when the school day begins.If you set the start time of the day to 7:00 AM, then the school day will begin at 7:00 AM."
                placeholder="0"
                label="Starting time"
                :show-sub-text="true"
                :toggle-subtext="true"
                :required="true"
            />

            <TextInput
                v-model="schoolPeriodForm.minutes_per_period"
                placeholder="0"
                type="number"
                label="Time for single period (minutes)"
                subtext="Define how long each class period lasts in minutes.
                                     This could vary based on your school's specific needs, this also excludes custom periods.
                                     For example: if your input is 45, that means each period has 45 minutes"
                :show-sub-text="false"
                :toggle-subtext="true"
                :required="true"
            />

            <TextInput
                v-model="schoolPeriodForm.no_of_periods"
                placeholder="0"
                label="Number of Periods Per Day"
                subtext="Enter the number of periods you have in a typical school day.
                            This will be the number of class sessions that are held in a day.
                            For example: if your input is is 8, that means there each day has 8 periods a day"
                :show-sub-text="false"
                type="number"
                :toggle-subtext="true"
                :required="true"
            />

            <div class="flex flex-col space-y-2">
                <!--            Level Category Section-->
                <div class="my-2">
                    <label class="pl-0.5 text-xs font-semibold text-gray-500">
                        Select level categories:</label
                    >

                    <div
                        class="mt-1.5 flex justify-between gap-6 rounded border border-zinc-200 p-3"
                    >
                        <div
                            v-for="(levelCategory, index) in levelCategories"
                            :key="index"
                            class="flex items-center space-x-1.5"
                        >
                            <input
                                v-model="schoolPeriodForm.level_category_ids"
                                type="checkbox"
                                :value="levelCategory.id"
                                class="rounded border-gray-300 text-black focus:ring-black"
                            />
                            <label class="text-xs">{{
                                levelCategory.name
                            }}</label>
                        </div>
                    </div>
                    <InformationCircleIcon
                        class="inline-block w-4 text-gray-500"
                    />

                    <span class="text-xs text-gray-500">
                        Note: Similar schedules will be applied for all classes
                        in selected categories.
                    </span>
                </div>

                <!--            Custom Period Section-->
                <div>
                    <div class="flex items-center space-x-2">
                        <span
                            class="mb-1 grow pl-0.5 text-xs font-semibold text-gray-500"
                            >Custom Period</span
                        >
                        <div
                            class="flex justify-center"
                            @click="addCustomTimeInput()"
                        >
                            <a
                                class="cursor-pointer text-xs text-zinc-700 hover:text-zinc-900"
                            >
                                + Add New</a
                            >
                        </div>
                    </div>
                    <div
                        v-for="(
                            customTimeInput, customIndex
                        ) in schoolPeriodForm.custom_periods"
                        :key="customIndex"
                    >
                        <div
                            class="relative flex flex-col space-y-2 rounded-sm border border-gray-200/50 p-3"
                        >
                            <TextInput
                                v-model="customTimeInput.name"
                                type="text"
                                label="Name"
                                placeholder="Breakfast"
                                :required="true"
                            />
                            <div class="flex justify-between">
                                <TextInput
                                    v-model="customTimeInput.duration"
                                    class="w-5/12"
                                    type="number"
                                    label="Duration (minutes)"
                                    placeholder="0"
                                    :required="true"
                                />
                                <TextInput
                                    v-model="customTimeInput.before_period"
                                    class="w-5/12"
                                    type="number"
                                    min="1"
                                    :max="schoolPeriodForm.minutes_per_period"
                                    label="Period before"
                                    placeholder="0"
                                    :required="true"
                                />
                            </div>
                            <div class="absolute top-0 right-0 p-2">
                                <TrashIcon
                                    class="w-4 stroke-red-500"
                                    @click="removeCustomPeriod(customIndex)"
                                />
                            </div>
                        </div>
                    </div>

                    <div>
                        <InformationCircleIcon
                            class="inline-block w-3 stroke-2"
                        />

                        <span class="text-[0.65rem] text-gray-500">
                            Add custom periods like lunch or recess. Fill in the
                            name, duration (in minutes), and specify which
                            period it precedes. For instance, to add a
                            'Breakfast' break before the 4th period, enter
                            'Breakfast', its length, and '4' in the respective
                            fields.
                        </span>
                    </div>
                </div>
            </div>

            <SecondaryButton
                class="mt-3 bg-black text-xs text-white"
                title="Submit"
                @click="addClassSchedule"
                >Submit
            </SecondaryButton>
        </div>
    </div>
</template>
<script setup>
import { InformationCircleIcon, TrashIcon } from "@heroicons/vue/24/outline";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TimePicker from "@/Components/TimePicker.vue";
import TextInput from "@/Components/TextInput.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { computed, onMounted, watch } from "vue";

const props = defineProps({
    form: {
        type: Object,
        default: null,
    },
});

const levelCategories = computed(() => usePage().props.level_categories || []);

onMounted(() => {
    router.reload({
        only: ["level_categories"],
        preserveState: true,
    });
});

const schoolPeriodForm = useForm({
    no_of_periods: null,
    minutes_per_period: null,
    start_time: null,
    level_category_ids: [],
    custom_periods: [{ name: "", duration: null, before_period: null }],
});

function addCustomTimeInput() {
    schoolPeriodForm.custom_periods.push({
        name: "",
        duration: null,
        before_period: null,
    });
}

function removeCustomPeriod(periodIndex, customIndex) {
    schoolPeriodForm.custom_periods.splice(customIndex, 1);
}

const emits = defineEmits(["update:form"]);

watch(schoolPeriodForm, () => {
    emits("update:form", schoolPeriodForm);
});

function addClassSchedule() {
    router.post(
        "/school-periods/create",
        {
            school_periods: [schoolPeriodForm],
        },
        {
            onSuccess: () => {
                router.get("/getting-started/class-schedule");
            },
        }
    );
}
</script>
<style scoped></style>
