<template>
    <FormElement
        :title="$t('addFee.addFee')"
        @cancel="feeForm.reset()"
        @submit="submit"
    >
        <TextInput
            v-model="feeForm.name"
            :placeholder="$t('common.name')"
            :label="$t('common.name')"
            :error="usePage().props.errors.name"
        />

        <TextInput
            v-model="feeForm.description"
            :placeholder="$t('common.description')"
            :label="$t('common.description')"
            :error="usePage().props.errors.description"
        />

        <TextInput
            v-model="feeForm.amount"
            :placeholder="$t('fees.amount')"
            :label="$t('fees.amount')"
            type="number"
            :error="usePage().props.errors.amount"
        />

        <DatePicker
            v-model="feeForm.due_date"
            :placeholder="$t('fees.dueDate')"
            :label="$t('fees.dueDate')"
            :error="usePage().props.errors.due_date"
        />
        <SelectInput
            v-if="penalties.length"
            v-model="feeForm.penalty_id"
            :options="penaltiesOptions"
            :placeholder="$t('addFee.selectPenalty')"
            :label="$t('addFee.selectPenalty')"
        />

        <!--        Create new penalty section-->

        <div
            v-if="!showPenaltyForm & penalties.length"
            class="cursor-pointer text-end text-sm underline-offset-2 hover:font-medium hover:underline"
            @click="showPenaltyForm = true"
        >
            {{ $t("addFee.wantCreatePenalty") }}
        </div>

        <!--        Empty penalty section-->
        <div
            v-if="!showPenaltyForm & !penalties.length"
            class="flex w-full flex-col items-center justify-center space-y-3"
        >
            <div class="px-5 text-center font-medium">
                {{ $t("addFee.noPenaltiesFound") }}
            </div>
            <SecondaryButton
                :title="$t('addPenalty.addPenalty')"
                class="!rounded-2xl bg-brand-400 text-white"
                @click="showPenaltyForm = true"
            />
        </div>

        <!--        Add penalty form-->
        <div
            v-if="showPenaltyForm"
            class="flex flex-col items-center space-y-3"
        >
            <div class="flex w-full justify-between px-4 pt-5">
                <div class="grow text-center font-medium">
                    {{ $t("addPenalty.addPenalty") }}
                </div>
                <XMarkIcon
                    class="h-5 w-5 cursor-pointer hover:scale-125 hover:text-red-500"
                    @click="showPenaltyForm = false"
                />
            </div>
            <SelectInput
                v-model="penaltyForm.type"
                :options="penaltyTypeSelectOptions"
                :placeholder="$t('addPenalty.selectType')"
                :label="$t('common.type')"
                class="w-full"
                :error="usePage().props.errors.type"
            />

            <TextInput
                v-model="penaltyForm.amount"
                :placeholder="$t('fees.amount')"
                :label="$t('fees.amount')"
                class="w-full"
                type="number"
                :error="usePage().props.errors.amount"
            />
            <SecondaryButton
                :title="$t('addFee.savePenalty')"
                class="w-fit !rounded-2xl bg-brand-400 text-white"
                @click="savePenalty"
            />
        </div>

        <SelectInput
            v-model="feeForm.feeable_type"
            :options="feeableTypeOptions"
            :placeholder="$t('addFee.selectFeePeriod')"
            :label="$t('addFee.selectFeePeriod')"
            :error="usePage().props.errors.feeable_type"
        />
        <SelectInput
            v-if="feeForm.feeable_type"
            v-model="feeForm.feeable_id"
            :options="feableIdOptions"
            :placeholder="$t('addFee.selectFeePeriod')"
            :label="$t('addFee.selectFeePeriod')"
            :error="usePage().props.errors.feeable_id"
        />

        <Toggle v-model="feeForm.is_active" :label="$t('addFee.isFeeActive')" />

        <Loading v-if="isLoading" is-full-screen />
    </FormElement>
</template>
<script setup>
import FormElement from "@/Components/FormElement.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import Loading from "@/Components/Loading.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { computed, inject, ref, watch } from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { XMarkIcon } from "@heroicons/vue/20/solid";
import { upperCase } from "lodash";
import Toggle from "@/Components/Toggle.vue";
import DatePicker from "@/Components/DatePicker.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const emit = defineEmits(["close"]);
const isLoading = ref(false);
const showPenaltyForm = ref(false);
const showNotification = inject("showNotification");

const feeForm = useForm({
    name: "",
    amount: "",
    description: "",
    penalty_id: "",
    feeable_type: "",
    feeable_id: "",
    is_active: true,
    due_date: new Date(),
});

const penalties = computed(() => usePage().props.penalties);
const penaltiesOptions = computed(() => {
    return penalties.value.map((penalty) => {
        return {
            value: penalty.id,
            label: upperCase(penalty.type) + " - " + penalty.amount,
        };
    });
});

const penaltyForm = useForm({
    type: "",
    amount: "",
});

const penaltyTypeSelectOptions = [
    {
        value: "flat_rate",
        label: t("addPenalty.flatRate"),
    },
    {
        value: "percentage",
        label: t("addPenalty.percentage"),
    },
    {
        value: "daily",
        label: t("addPenalty.perDay"),
    },
];

const activeQuarters = computed(() => usePage().props.active_quarters);
const activeSemesters = computed(() => usePage().props.active_semesters);
const activeSchoolYearId = computed(
    () => usePage().props.active_school_year_id
);
const feeableTypeOptions = [
    {
        value: "quarters",
        label: t("common.quarter"),
    },
    {
        value: "semesters",
        label: t("common.semester"),
    },
    {
        value: "school_years",
        label: t("common.schoolYear"),
    },
];
const feableIdOptions = ref();
watch(
    () => feeForm.feeable_type,
    (newValue) => {
        if (newValue === "school_years") {
            feeForm.feeable_id = activeSchoolYearId.value;
        } else if (newValue === "semesters") {
            feableIdOptions.value = activeSemesters.value.map((semester) => {
                return {
                    value: semester.id,
                    label: semester.name,
                };
            });
        } else if (newValue === "quarters") {
            feableIdOptions.value = activeQuarters.value.map((quarter) => {
                return {
                    value: quarter.id,
                    label: quarter.name + " - (" + quarter.semester.name + ")",
                };
            });
        }
    }
);

const savePenalty = () => {
    isLoading.value = true;
    penaltyForm.post("/admin/penalties/create", {
        preserveScroll: true,
        onSuccess: () => {
            isLoading.value = false;
            showPenaltyForm.value = false;

            // set the penalty id to the fee form
            feeForm.penalty_id = penalties.value[penalties.value.length - 1].id;
        },
        onError: () => {
            isLoading.value = false;
            showNotification({
                type: "error",
                message: t("addFee.errorAddingPenalty"),
            });
        },
    });
};

const submit = () => {
    isLoading.value = true;
    feeForm.post("/admin/fees/create", {
        preserveScroll: true,
        onSuccess: () => {
            isLoading.value = false;
            emit("close");
        },
        onError: () => {
            isLoading.value = false;
            showNotification({
                type: "error",
                message: t("addFee.errorAddingFee"),
            });
        },
    });
};
</script>

<style scoped></style>
