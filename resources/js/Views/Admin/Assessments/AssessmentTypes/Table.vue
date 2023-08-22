<template>
    <!-- Add/Update Modal -->
    <Modal v-model:view="isModalOpen">
        <FormElement :title="modalTitle" @submit="submitForm">
            <div class="flex w-full gap-3">
                <TextInput
                    v-model="form.name"
                    :error="form.errors.name"
                    class="w-8/12"
                    :label="$t('common.name')"
                    :placeholder="$t('assessmentIndex.name')"
                    required
                />
                <TextInput
                    v-model="form.percentage"
                    :error="form.errors.percentage"
                    type="number"
                    :label="$t('assessmentIndex.percentage')"
                    :placeholder="$t('assessmentIndex.percentage')"
                    :min="0"
                    :max="100"
                    class="w-4/12"
                    required
                />
            </div>

            <div v-if="isUpdate" class="w-full">
                <label class="pl-0.5 text-sm font-semibold text-brand-text-300">
                    {{ $t("assessmentIndex.updateLevelCategories") }}</label
                >

                <SelectInput
                    v-model="form.level_category_id"
                    :options="levelCategoriesWithoutAll"
                    class="my-2"
                    placeholder=""
                />
            </div>

            <div v-else>
                <label class="pl-0.5 text-sm font-semibold text-brand-text-300">
                    {{ $t("assessmentIndex.selectLevelCategories") }}</label
                >

                <div class="flex flex-row gap-6 rounded border p-3">
                    <div
                        v-for="(
                            levelCategory, index
                        ) in levelCategoriesWithoutAll"
                        :key="index"
                        class="flex items-center space-x-2"
                    >
                        <div>
                            <input
                                v-model="form.level_category_id"
                                type="checkbox"
                                :value="levelCategory.value"
                                class="rounded border-gray-300 text-black focus:ring-black"
                            />
                        </div>
                        <label class="text-sm">{{ levelCategory.label }}</label>
                    </div>
                </div>
            </div>

            <Toggle
                v-model="form.customizable"
                :label="$t('assessmentIndex.customizable')"
            />

            <div v-show="form.customizable">
                <div class="flex gap-3">
                    <TextInput
                        v-model="form.min_assessments"
                        :error="form.errors.min_assessments"
                        class="w-full"
                        type="number"
                        :label="$t('assessmentIndex.minimumAssessments')"
                        :placeholder="
                            $t('assessmentIndex.minimumAssessmentsPlaceHolder')
                        "
                    />

                    <TextInput
                        v-model="form.max_assessments"
                        :error="form.errors.max_assessments"
                        class="w-full"
                        type="number"
                        :label="$t('assessmentIndex.maximumAssessments')"
                        :placeholder="
                            $t('assessmentIndex.maximumAssessmentsPlaceHolder')
                        "
                    />
                </div>
                <div class="mt-2 pl-3">
                    <span class="text-[0.7rem] font-light">
                        {{ $t("assessmentIndex.minMaxHint") }}
                    </span>
                </div>
            </div>
        </FormElement>
    </Modal>

    <div
        class="flex flex-col space-y-1 rounded-lg bg-white px-2 py-4 shadow-sm"
    >
        <div class="px-3 text-2xl font-medium">
            {{ $t("assessmentIndex.assessmentType") }}
        </div>

        <TableElement
            :selectable="false"
            :columns="config"
            :footer="false"
            :header="true"
            :data="filteredAssessmentTypes"
            actionable
            row-actionable
            class="px-2 !shadow-none"
        >
            <template #filter>
                <div class="flex w-full items-center justify-between gap-2">
                    <div class="flex w-full items-center space-x-2">
                        <SelectInput
                            v-model="filterByLevelCategory"
                            :options="levelCategories"
                            :placeholder="
                                $t('assessmentIndex.filterByLevelCategory')
                            "
                            class="w-8/12"
                        />
                    </div>

                    <PrimaryButton
                        class="flex w-4/12 items-center justify-center space-x-1.5 !rounded-3xl bg-brand-450 p-2"
                        @click="openAddModal"
                    >
                        <SquaresPlusIcon class="w-3.5 text-white" />
                        <span>{{ $t("assessmentIndex.addType") }}</span>
                    </PrimaryButton>
                </div>
            </template>

            <template #name-column="{ data }">
                <div class="flex items-center gap-2">
                    <div class="h-1.5 w-1.5 rotate-45 bg-orange-300" />
                    <span class="form-control-sm text-sm">{{ data }}</span>
                </div>
            </template>

            <template #percentage-column="{ data }">
                <div class="flex justify-start gap-1">
                    <span class="text-xs font-semibold"> {{ data }}% </span>
                </div>
            </template>

            <template #level_category-column="{ data }">
                <div class="flex items-center gap-1">
                    <div
                        class="h-1.5 w-1.5 rounded-full"
                        :class="categoryColors[data.name]"
                    />
                    <div class="flex items-center gap-1">
                        <span class="text-xs">
                            {{ data.name }}
                        </span>
                    </div>
                </div>
            </template>

            <template #updated_at-column="{ data }">
                <div class="flex w-full justify-start">
                    <span class="text-xs text-gray-500"> {{ data }}</span>
                </div>
            </template>

            <template #row-actions="{ row }">
                <button @click="openUpdateModal(row)">
                    <PencilSquareIcon class="h-4 w-4" />
                </button>
                <button @click="toggleDialogBox(row.id)">
                    <TrashIcon class="h-4 w-4" />
                </button>
            </template>
        </TableElement>
    </div>

    <DialogBox
        v-if="isDialogBoxOpen"
        open
        @abort="isDialogBoxOpen = false"
        @confirm="remove"
    />
</template>
<script setup>
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import Toggle from "@/Components/Toggle.vue";
import { computed, ref, watch } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import {
    PencilSquareIcon,
    SquaresPlusIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TableElement from "@/Components/TableElement.vue";
import DialogBox from "@/Components/DialogBox.vue";
import moment from "moment";
import SelectInput from "@/Components/SelectInput.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const isModalOpen = ref(false);
const isUpdate = ref(false);
const filterByLevelCategory = ref(null);

function openAddModal() {
    isUpdate.value = false;
    form.reset();
    isModalOpen.value = true;
}

function openUpdateModal(row) {
    isUpdate.value = true;
    form.id = row.id;
    form.name = row.name;
    form.percentage = row.percentage;
    form.level_category_id = row.level_category_id;
    form.customizable = row.customizable;
    form.min_assessments = row.min_assessments;
    form.max_assessments = row.max_assessments;
    isModalOpen.value = true;
}

const modalTitle = computed(() =>
    isUpdate.value
        ? t("assessmentIndex.updateAssessmentType")
        : t("assessmentIndex.addAssessmentType")
);

function submitForm() {
    if (isUpdate.value) {
        update(form.id);
    } else {
        submit();
    }
}

const selectedAssessmentId = ref(null);

const isDialogBoxOpen = ref(false);

function toggleDialogBox(id) {
    isDialogBoxOpen.value = !isDialogBoxOpen.value;
    selectedAssessmentId.value = id;
}

const assessmentTypes = computed(() => {
    return usePage().props.assessment_types?.map((assessment_type) => {
        return {
            ...assessment_type,
            updated_at: moment(assessment_type.updated_at).fromNow(),
        };
    });
});

const level_categories = computed(() => usePage().props.level_categories);

const levelCategories = computed(() => {
    return [
        { label: "All", value: null },
        ...level_categories.value.map((level_category) => {
            return {
                label: level_category.name,
                value: level_category.id,
            };
        }),
    ];
});

const filteredAssessmentTypes = computed(() => {
    if (filterByLevelCategory.value !== null) {
        return assessmentTypes.value.filter((assessment_type) => {
            return (
                assessment_type.level_category_id ===
                filterByLevelCategory.value
            );
        });
    }
    return assessmentTypes.value;
});

const levelCategoriesWithoutAll = computed(() => {
    return levelCategories.value.filter((category) => category.label !== "All");
});

const config = [
    {
        name: t("assessmentIndex.assessmentType"),
        key: "name",
        type: "custom",
    },
    {
        name: t("assessmentIndex.percentage"),
        key: "percentage",
        type: "custom",
    },
    {
        name: t("assessmentIndex.gradeCategory"),
        key: "level_category",
        type: "custom",
    },
    {
        name: t("assessmentIndex.updatedAt"),
        key: "updated_at",
        type: "custom",
    },
];

// Single form for both add and update actions
const form = useForm({
    id: null,
    name: "",
    percentage: "",
    level_category_id: [],
    customizable: false,
    min_assessments: "",
    max_assessments: "",
});

watch(
    () => form.percentage,
    (value) => {
        if (value < 0) form.percentage = "0";
        if (value > 100) form.percentage = "100";
    }
);

function submit() {
    if (form.customizable === false) {
        form.min_assessments = null;
        form.max_assessments = null;
    }

    form.post(route("assessments.type.create"), {
        onSuccess: () => {
            form.reset();
            isModalOpen.value = false;
        },
    });
}

function update(id) {
    if (form.customizable === false) {
        form.min_assessments = null;
        form.max_assessments = null;
    }
    form.post(route("assessments.type.update", id), {
        onSuccess: () => {
            form.reset();
            isModalOpen.value = false;
        },
    });
}

const remove = () => {
    router.delete("/assessments/type/destroy/" + selectedAssessmentId.value, {
        onFinish: () => {
            isDialogBoxOpen.value = false;
            selectedAssessmentId.value = null;
        },
    });
};

const categoryColors = {
    Kindergarten: "bg-blue-500",
    Primary: "bg-orange-500",
    Secondary: "bg-green-500",
};
</script>
<style scoped></style>
