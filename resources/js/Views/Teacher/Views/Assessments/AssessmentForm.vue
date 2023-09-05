<template>
    <div>
        <Loading v-if="isLoading" :is-full-screen="true" />
        <FormElement
            :title="$t('assessmentForm.formElementTitle')"
            class="my-2"
            @submit="isAdmin() ? handleAdminSubmit() : handleTeacherSubmit()"
        >
            <!-- Form Fields -->
            <TextInput
                v-model="form.title"
                :error="form.errors.title"
                :placeholder="$t('assessmentForm.titlePlaceholder')"
                :label="$t('assessmentForm.titleLabel')"
            />
            <TextArea
                v-model="form.description"
                :error="form.errors.description"
                :rows="7"
                :placeholder="$t('assessmentForm.descriptionPlaceholder')"
                :label="$t('assessmentForm.descriptionLabel')"
            />
            <TextInput
                v-model="form.maximum_point"
                :error="form.errors.maximum_point"
                :placeholder="$t('assessmentForm.maximumPointPlaceholder')"
                :label="$t('assessmentForm.maximumPointLabel')"
                type="number"
            />
            <DatePicker
                v-model="form.due_date"
                :placeholder="$t('assessmentForm.dueDatePlaceholder')"
                :error="form.errors.due_date"
            />

            <div
                v-if="isAdmin()"
                class="dropdown-container relative flex flex-col"
            >
                <div
                    class="mt-1 flex w-full cursor-pointer items-center justify-between rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                    @click="dropdownVisible = !dropdownVisible"
                >
                    <div>
                        <span v-if="!form.level_category_ids" class="text-sm">
                            Select Grade Category(ies)
                        </span>

                        <span v-else class="text-sm">
                            Selected Category(ies):

                            <span>
                                {{
                                    levelCategoryOptions
                                        .filter((option) =>
                                            form.level_category_ids.includes(
                                                option.value
                                            )
                                        )
                                        .map((option) => option.label)
                                        .join(", ")
                                }}
                            </span>
                        </span>
                    </div>

                    <ChevronDownIcon class="h-4 w-4" />
                </div>
                <div
                    v-if="dropdownVisible"
                    class="absolute z-10 mt-10 w-full rounded-md border border-gray-300 bg-white shadow-lg"
                >
                    <div
                        v-for="level in levelCategoryOptions"
                        :key="level"
                        class="cursor-pointer p-2 hover:bg-gray-200"
                        @click="toggleSelectionLevelCategory(level.value)"
                    >
                        <input
                            type="checkbox"
                            :checked="
                                form.level_category_ids.includes(level.value)
                            "
                            class="mr-2"
                        />
                        {{ level.label }}
                    </div>
                </div>
                <div
                    v-if="form.errors && form.errors.level_category_ids"
                    class="mt-2 text-xs text-negative-50"
                >
                    * {{ form.errors.level_category_ids }}
                </div>
            </div>

            <div
                v-if="isTeacher()"
                class="dropdown-container relative flex flex-col"
            >
                <div
                    class="mt-1 flex w-full cursor-pointer items-center justify-between rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                    @click="dropdownVisible = !dropdownVisible"
                >
                    <div>
                        <span v-if="!form.batch_subject_ids" class="text-xs">
                            Select Class(es)
                        </span>

                        <span v-else class="text-sm">
                            Selected Class(es):

                            <span>
                                {{
                                    batchSubjectOptions
                                        .filter((option) =>
                                            form.batch_subject_ids.includes(
                                                option.value
                                            )
                                        )
                                        .map((option) => option.label)
                                        .join(", ")
                                }}
                            </span>
                        </span>
                    </div>

                    <ChevronDownIcon class="h-4 w-4" />
                </div>
                <div
                    v-if="dropdownVisible"
                    class="absolute z-10 mt-10 w-full rounded-md border border-gray-300 bg-white shadow-lg"
                >
                    <div
                        v-for="(batch, index) in batchSubjectOptions"
                        :key="index"
                        class="cursor-pointer p-2 hover:bg-gray-200"
                        @click="toggleSelection(batch.value)"
                    >
                        <input
                            type="checkbox"
                            :checked="
                                form.batch_subject_ids.includes(batch.value)
                            "
                            class="mr-2 rounded-md text-brand-400"
                        />
                        {{ batch.label }}
                    </div>
                </div>
                <div
                    v-if="form.errors && form.errors.batch_subject_id"
                    class="mt-2 text-xs text-negative-50"
                >
                    * {{ form.errors.batch_subject_id }}
                </div>
            </div>

            <SelectInput
                v-if="form.level_category_ids && isAdmin()"
                v-model="form.assessment_type_id"
                :options="batchAssessmentTypes"
                :error="form.errors.assessment_type_id"
                :placeholder="$t('assessmentForm.assessmentTypeId')"
            />

            <SelectInput
                v-if="form.batch_subject_ids && isTeacher()"
                v-model="form.assessment_type_id"
                :options="selectedBatchAssessmentTypes"
                :error="form.errors.assessment_type_id"
                :placeholder="$t('assessmentForm.assessmentTypeId')"
            />

            <SelectInput
                v-model="form.status"
                :options="statusOptions"
                :placeholder="$t('assessmentForm.statusPlaceholder')"
                :error="form.errors.status"
            />
            <div
                v-if="
                    form.status === 'published' || form.status === 'scheduled'
                "
                class="flex w-full rounded-sm bg-brand-50 px-4 py-2 text-center text-[0.65rem] font-light"
            >
                <InformationCircleIcon class="mr-2 w-7 text-gray-600" />

                <div>
                    <span v-html="$t('assessmentForm.message')"></span>
                </div>
            </div>
        </FormElement>

        <DialogBox
            v-model:open="showDialog"
            type="update"
            :title="$t('assessmentForm.title')"
            @confirm="isAdmin() ? handleAdminSubmit() : handleTeacherSubmit()"
        >
            <template #description>
                {{ $t("assessmentForm.alertMessage") }}
            </template>
        </DialogBox>
    </div>
</template>

<script setup>
import TextInput from "@/Components/TextInput.vue";
import DatePicker from "@/Components/DatePicker.vue";
import TextArea from "@/Components/TextArea.vue";
import FormElement from "@/Components/FormElement.vue";
import SelectInput from "@/Components/SelectInput.vue";
import DialogBox from "@/Components/DialogBox.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, onMounted, onUnmounted, ref, watch } from "vue";
import {
    ChevronDownIcon,
    InformationCircleIcon,
} from "@heroicons/vue/24/outline";
import { useI18n } from "vue-i18n";
import Loading from "@/Components/Loading.vue";
import { isAdmin, isTeacher } from "@/utils";
import { useUIStore } from "@/Store/ui";

const { t } = useI18n();

const emit = defineEmits(["success"]);
const props = defineProps({
    assessment: {
        type: Object,
        default: null,
    },
});

const levelCategories = computed(() => usePage().props.level_categories);
const assessmentType = computed(() => usePage().props.filters.assessment_types);

// Get level categories options
const levelCategoryOptions = computed(() => {
    return levelCategories.value.map((category) => {
        return {
            label: category.name,
            value: category.id,
        };
    });
});

let form = useForm({
    assessment_type_id: "",
    batch_subject_ids: [],
    level_category_ids: [],
    due_date: new Date(),
    title: "",
    description: "",
    maximum_point: "",
    status: "",
});

watch(
    () => props.assessment,
    (assessment) => {
        if (assessment) {
            form = useForm({
                ...assessment,
                due_date: new Date(assessment.due_date),
                status: assessment.status,
                assessment_id: assessment.id,
            });
        }
    },
    { immediate: true }
);

const showDialog = ref(false);
const teacher = usePage().props.teacher;
const assessmentTypes = usePage().props.assessment_type;

let dropdownVisible = ref(false);

onMounted(() => {
    document.addEventListener("click", outsideClickListener);
});

onUnmounted(() => {
    document.removeEventListener("click", outsideClickListener);
});

const outsideClickListener = (event) => {
    if (!event.target.closest(".dropdown-container")) {
        dropdownVisible.value = false;
    }
};

const selectedBatchAssessmentTypes = computed(() => {
    if (!form.batch_subject_ids) return []; // Return empty if no batch subject is selected.

    let types = [];

    form.batch_subject_ids.forEach((id) => {
        const batch_subject = teacher.batch_subjects.find(
            (batch_subject) => batch_subject.id === id
        );

        if (batch_subject) {
            const level_category_id =
                batch_subject.batch.level.level_category.id;
            const batchTypes = assessmentTypes
                .filter((type) => type.level_category_id === level_category_id)
                .map((type) => {
                    return {
                        label: type.name,
                        value: type.id,
                    };
                });

            types = [...types, ...batchTypes];
        }
    });

    // Remove duplicates if any batch subject has common assessment types
    return [...new Set(types.map((type) => JSON.stringify(type)))].map((type) =>
        JSON.parse(type)
    );
});

// Get selected assessment types based on selected level categories
const batchAssessmentTypes = computed(() => {
    if (form.level_category_ids.length > 0) {
        return assessmentType.value
            .filter((type) => {
                return (
                    form.level_category_ids.includes(type.level_category_id) &&
                    type.is_admin_controlled
                );
            })
            .map((type) => {
                return {
                    label: type.name,
                    value: type.id,
                };
            });
    } else {
        return [];
    }
});

const batchSubjectOptions = computed(() => {
    return teacher?.batch_subjects.map((batchSubject) => {
        return {
            value: batchSubject.id,
            label:
                batchSubject.subject.full_name +
                " " +
                batchSubject.batch.level.name +
                batchSubject.batch.section,
        };
    });
});

const statusOptions = [
    {
        label: t("assessmentForm.draft"),
        value: "draft",
    },
    {
        label: t("assessmentForm.schedule"),
        value: "scheduled",
    },
    {
        label: t("assessmentForm.publish"),
        value: "published",
    },
];

const isLoading = ref(false);

function handleTeacherSubmit() {
    const url = form.assessment_id
        ? "/teacher/assessments/update/"
        : "/teacher/assessments/create";

    isLoading.value = true;
    form.post(url, {
        preserveState: true,
        onSuccess: () => {
            emit("success");
        },
        onFinish: () => {
            isLoading.value = false;
        },
    });
}

const uiStore = useUIStore();

function handleAdminSubmit() {
    uiStore.setLoading(true, "Creating assessment...");

    const url = form.assessment_id
        ? "/admin/assessments/update/"
        : "/admin/assessments/create/";

    isLoading.value = true;
    form.post(url, {
        preserveState: true,
        onSuccess: () => {
            emit("success");
        },
        onFinish: () => {
            isLoading.value = false;
        },
        onError: () => {
            uiStore.setLoading(null, null);
        },
    });
}

const toggleSelection = (value) => {
    const index = form.batch_subject_ids.indexOf(value);
    if (index < 0) {
        form.batch_subject_ids.push(value);
    } else {
        form.batch_subject_ids.splice(index, 1);
    }
};

const toggleSelectionLevelCategory = (value) => {
    const index = form.level_category_ids.indexOf(value);
    if (index < 0) {
        form.level_category_ids.push(value);
    } else {
        form.level_category_ids.splice(index, 1);
    }
};
</script>
