<template>
    <div class="w-full">
        <div class="mb-4">
            <h2 class="text-base font-semibold text-brand-text-500">
                {{ $t("periodForm.classSchedule") }}
            </h2>

            <p class="text-xs font-light text-brand-text-300">
                {{ $t("periodForm.description") }}
            </p>
        </div>

        <div class="flex w-full flex-col space-y-3">
            <TimePicker
                v-model="schoolPeriodForm.start_time"
                :subtext="$t('periodForm.startTimePickerSubtext')"
                placeholder="0"
                :label="$t('periodForm.startTimePickerLabel')"
                :show-sub-text="true"
                :toggle-subtext="true"
                :required="true"
            />

            <TextInput
                v-model="schoolPeriodForm.minutes_per_period"
                placeholder="0"
                type="number"
                :label="$t('periodForm.minutesPerPeriodLabel')"
                :subtext="$t('periodForm.minutesPerPeriodSubtext')"
                :show-sub-text="false"
                :toggle-subtext="true"
                :required="true"
            />

            <TextInput
                v-model="schoolPeriodForm.no_of_periods"
                placeholder="0"
                :label="$t('periodForm.noOfPeriodsLabel')"
                :subtext="$t('periodForm.noOfPeriodSubtext')"
                :show-sub-text="false"
                type="number"
                :toggle-subtext="true"
                :required="true"
            />

            <div class="flex flex-col space-y-2">
                <!--            Level Category Section-->
                <div class="my-2">
                    <label
                        class="pl-0.5 text-xs font-semibold text-brand-text-300"
                    >
                        {{ $t("periodForm.selectLevelCategories") }}
                    </label>

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
                        class="inline-block w-4 text-brand-text-300"
                    />

                    <span class="text-xs text-brand-text-300">
                        {{ $t("periodForm.note") }}
                    </span>
                </div>

                <!--            Custom Period Section-->
                <div>
                    <div class="flex items-center space-x-2">
                        <span
                            class="mb-1 grow pl-0.5 text-xs font-semibold text-brand-text-300"
                            >{{ $t("periodForm.customPeriod") }}</span
                        >
                        <div
                            class="flex justify-center"
                            @click="addCustomTimeInput()"
                        >
                            <a
                                class="cursor-pointer text-xs text-brand-text-400 hover:text-brand-text-500"
                            >
                                {{ $t("periodForm.addNew") }}
                            </a>
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
                                :label="$t('periodForm.customTimeNameLabel')"
                                :placeholder="
                                    $t('periodForm.customTimeNamePlaceholder')
                                "
                                :required="true"
                            />
                            <div class="flex justify-between">
                                <TextInput
                                    v-model="customTimeInput.duration"
                                    class="w-5/12"
                                    type="number"
                                    :label="
                                        $t('periodForm.customTimeDurationLabel')
                                    "
                                    placeholder="0"
                                    :required="true"
                                />
                                <TextInput
                                    v-model="customTimeInput.before_period"
                                    class="w-5/12"
                                    type="number"
                                    min="1"
                                    :max="schoolPeriodForm.minutes_per_period"
                                    :label="
                                        $t('periodForm.customBeforePeriodLabel')
                                    "
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

                        <span class="text-[0.65rem] text-brand-text-300">
                            {{ $t("periodForm.hintAddCustomPeriods") }}
                        </span>
                    </div>
                </div>
            </div>

            <SecondaryButton
                class="mt-3 bg-black text-xs text-white"
                :title="$t('periodForm.title')"
                @click="addClassSchedule"
            >
                {{ $t("periodForm.submit") }}
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
