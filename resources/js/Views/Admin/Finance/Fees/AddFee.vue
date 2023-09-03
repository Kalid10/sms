<template>
    <FormElement
        title="Add Fee"
        class="!overflow-y-auto"
        @cancel="feeForm.reset()"
        @submit="submit"
    >
        <TextInput
            v-model="feeForm.name"
            placeholder="Name"
            label="Name"
            :error="usePage().props.errors.name"
        />

        <TextInput
            v-model="feeForm.description"
            placeholder="Description"
            label="Description"
            :error="usePage().props.errors.description"
        />

        <TextInput
            v-model="feeForm.amount"
            placeholder="Amount"
            label="Amount"
            type="number"
            :error="usePage().props.errors.amount"
        />

        <DatePicker
            v-model="feeForm.due_date"
            placeholder="Due Date"
            label="Due Date"
            :error="usePage().props.errors.due_date"
        />

        <SelectInput
            v-model="feeForm.feeable_type"
            :options="feeableTypeOptions"
            label="Select Fee Period"
            placeholder="Select Fee Period"
            :error="usePage().props.errors.feeable_type"
        />
        <SelectInput
            v-if="feeForm.feeable_type"
            v-model="feeForm.feeable_id"
            :options="feableIdOptions"
            label="Select Fee Period"
            placeholder="Select Fee Period"
            :error="usePage().props.errors.feeable_id"
        />

        <div class="w-full rounded-md border p-2">
            <div class="text-sm font-semibold">
                Select Target Grade Categories
            </div>
            <div
                v-for="(item, index) in levelCategoryOptions"
                :key="index"
                class="cursor-pointer p-2 hover:bg-gray-200"
                @click="toggleLevelCategorySelection(item.value)"
            >
                <input
                    type="checkbox"
                    :checked="feeForm.level_category_ids.includes(item.value)"
                    class="mr-2 rounded-md text-brand-450"
                />
                {{ item.label }}
            </div>
        </div>

        <SelectInput
            v-if="penalties.length"
            v-model="feeForm.penalty_id"
            :options="penaltiesOptions"
            label="Select Penalty"
            placeholder="Select Penalty"
        />

        <!--        Create new penalty section-->
        <div
            v-if="!showPenaltyForm & penalties.length"
            class="cursor-pointer text-end text-sm underline-offset-2 hover:font-medium hover:underline"
            @click="showPenaltyForm = true"
        >
            Do you want to create a new penalty for this fee?
        </div>

        <!--        Empty penalty section-->
        <div
            v-if="!showPenaltyForm & !penalties.length"
            class="flex w-full flex-col items-center justify-center space-y-3 rounded-lg border border-brand-500 p-3"
        >
            <div class="px-5 text-center font-medium">
                No penalties have been found. To add a penalty with this fee,
                you may create one below.
            </div>
            <SecondaryButton
                title="Add Penalty"
                class="!rounded-2xl bg-brand-400 text-white"
                @click="showPenaltyForm = true"
            />
        </div>

        <!--        Add penalty form-->
        <div
            v-if="showPenaltyForm"
            class="flex flex-col items-center space-y-3 rounded-md border border-brand-500 p-3"
        >
            <div class="flex w-full justify-between px-4 pt-5">
                <div class="grow text-center font-medium">Add Penalty</div>
                <XMarkIcon
                    class="h-5 w-5 cursor-pointer hover:scale-125 hover:text-red-500"
                    @click="showPenaltyForm = false"
                />
            </div>
            <SelectInput
                v-model="penaltyForm.type"
                :options="penaltyTypeSelectOptions"
                label="Type"
                class="w-full"
                placeholder="Select Type"
                :error="usePage().props.errors.type"
            />

            <TextInput
                v-model="penaltyForm.amount"
                placeholder="Amount"
                label="Amount"
                class="w-full"
                type="number"
                :error="usePage().props.errors.amount"
            />
            <SecondaryButton
                title="Save Penalty"
                class="w-fit !rounded-2xl bg-brand-400 text-white"
                @click="savePenalty"
            />
        </div>

        <div class="flex w-full justify-between p-3">
            <Toggle
                v-model="feeForm.is_student_tuition_fee"
                label="Is this student tuition fee?"
            />
            <Toggle v-model="feeForm.is_active" label="Is fee active?" />
        </div>
        <SelectInput
            v-model="feeForm.feeable_type"
            :options="feeableTypeOptions"
            label="Select Fee Period"
            placeholder="Select Fee Period"
            :error="usePage().props.errors.feeable_type"
        />
        <SelectInput
            v-if="feeForm.feeable_type"
            v-model="feeForm.feeable_id"
            :options="feableIdOptions"
            label="Select Fee Period"
            placeholder="Select Fee Period"
            :error="usePage().props.errors.feeable_id"
        />

        <!--        <Toggle v-model="feeForm.is_active" label="Is this fee active?" />-->

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
import DatePicker from "@/Components/DatePicker.vue";
import { useUIStore } from "@/Store/ui";

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
    level_category_ids: [],
    is_student_tuition_fee: false,
});

// Penalties section
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
        label: "Flat Rate",
    },
    {
        value: "percentage",
        label: "Percentage",
    },
    {
        value: "daily",
        label: "Per Day",
    },
];

// Feeable section
const activeQuarters = computed(() => usePage().props.active_quarters);
const activeSemesters = computed(() => usePage().props.active_semesters);
const activeSchoolYearId = computed(
    () => usePage().props.active_school_year_id
);
const feeableTypeOptions = [
    {
        value: "quarters",
        label: "Quarter",
    },
    {
        value: "semesters",
        label: "Semester",
    },
    {
        value: "school_years",
        label: "School Year",
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
                message: "There was an error adding the penalty.",
            });
        },
    });
};

const uiStore = useUIStore();

const submit = () => {
    isLoading.value = true;
    uiStore.setLoading(true, "Creating fee");

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
                message: "There was an error adding the fee.",
            });
            uiStore.setLoading(false);
        },
    });
};

// Level categories
const levelCategories = computed(() => usePage().props.level_categories);
const levelCategoryOptions = computed(() => {
    return levelCategories.value.map((levelCategory) => {
        return {
            value: levelCategory.id,
            label: levelCategory.name,
        };
    });
});

const toggleLevelCategorySelection = (value) => {
    if (feeForm.level_category_ids.includes(value)) {
        feeForm.level_category_ids = feeForm.level_category_ids.filter(
            (id) => id !== value
        );
    } else {
        feeForm.level_category_ids.push(value);
    }
};
</script>

<style scoped></style>
