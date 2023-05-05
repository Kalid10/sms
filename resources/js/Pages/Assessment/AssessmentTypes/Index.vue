<template>

    <!-- Add/Update Modal -->
    <Modal v-model:view="modalOpen">
        <FormElement :title="modalTitle" @submit="submitForm">
            <div class="flex gap-3">
                <TextInput
                    v-model="form.name"
                    :error="form.errors.name"
                    class="w-full" label="Name"
                    placeholder="name"
                    required
                />
                <div class="relative flex">
                    <TextInput
                        v-model="form.percentage"
                        :error="form.errors.percentage"
                        type="number" class="w-full"
                        label="Percentage"
                        placeholder="percentage"
                        required
                    />
                    <ReceiptPercentIcon class="absolute bottom-0 right-0 mx-8 my-2 h-6 w-6"/>
                </div>

            </div>

            <div v-if="isUpdate" class="w-full">
                <label class="pl-0.5 text-sm font-semibold text-gray-500"> Update level categories:</label>

                <SelectInput
                    v-model="form.level_category_id"
                    :options="levelCategoriesWithoutAll"
                    class="my-2"
                    placeholder=""/>
            </div>

            <div v-else>
                <label class="pl-0.5 text-sm font-semibold text-gray-500"> Select level categories:</label>

                <div class="flex flex-row gap-6 rounded border p-3">

                    <div

                        v-for="(levelCategory, index) in levelCategoriesWithoutAll"
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
                label="Customizable"/>

            <div
                v-show="form.customizable"
            >
                <div class="flex gap-3">
                    <TextInput
                        v-model="form.min_assessments"
                        :error="form.errors.min_assessments"
                        class="w-full" type="number"
                        label="Minimum Assessments"
                        placeholder="min assessment"
                    />

                    <TextInput
                        v-model="form.max_assessments"
                        :error="form.errors.max_assessments"
                        class="w-full"
                        type="number"
                        label="Maximum Assessments"
                        placeholder="max assessment"/>
                </div>
                <div class="mt-2 pl-3">
                    <span class="text-[0.7rem] font-light">
                        Min and max assessments are the minimum and maximum number of tests or exams that a student is required to take.
                    </span>
                </div>
            </div>
        </FormElement>

    </Modal>

    <TableElement
        :selectable="false"
        :filterable="false"
        :columns="config"
        :footer="false"
        :header="false"
        :data="filteredAssessmentTypes"
        actionable
        row-actionable
        title="List of Assessment Types"
    >
        <template #action>
            <div class="flex w-full items-center gap-2">

                <SelectInput
                    v-model="filterByLevelCategory"
                    :options="levelCategories"
                    placeholder="filter by level category"
                    class="lg:text-md w-40 text-xs md:w-48"/>

                <PrimaryButton
                    title="Add Assessment Type"
                    class="lg:text-md text-xs"
                    @click="openAddModal"/>

            </div>
        </template>

        <template #name-column="{ data }">
            <div class="flex items-center gap-2">
                <div class="h-1.5 w-1.5 rotate-45 bg-orange-300"/>
                <span class="form-control-sm text-sm">{{ data }}</span>
            </div>
        </template>

        <template #percentage-column="{ data }">
            <div class="flex justify-start gap-1">
                <span class="text-xs font-semibold ">
                    {{ data }}%
                </span>
            </div>
        </template>

        <template #level_category-column="{ data }">
            <div class="flex items-center gap-1">
                <div class="h-1.5 w-1.5 rounded-full" :class="categoryColors[data.name]"/>
                <div class="flex items-center gap-1">
                <span class="text-xs">
                {{ data.name }}
            </span>
                </div>
            </div>
        </template>

        <template #updated_at-column="{ data }">
            <div class="flex w-full justify-start">
                <span class="text-xs text-gray-500">Last updated {{ data }}</span>
            </div>
        </template>

        <template #row-actions="{ row }">
            <button @click="openUpdateModal(row)">
                <PencilSquareIcon class="h-4 w-4"/>
            </button>
            <button @click="toggleDialogBox(row.id)">
                <TrashIcon class="h-4 w-4"/>
            </button>
        </template>

    </TableElement>

    <DialogBox
        v-if="isDialogBoxOpen"
        open
        @abort="isDialogBoxOpen = false"
        @confirm="remove"/>

</template>
<script setup>
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import Toggle from "@/Components/Toggle.vue";
import {computed, ref} from "vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import {PencilSquareIcon, ReceiptPercentIcon, TrashIcon} from "@heroicons/vue/24/outline";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TableElement from "@/Components/TableElement.vue";
import DialogBox from "@/Components/DialogBox.vue";
import moment from "moment";
import SelectInput from "@/Components/SelectInput.vue";


const modalOpen = ref(false);
const isUpdate = ref(false);
const filterByLevelCategory = ref(null);

function openAddModal() {
    isUpdate.value = false;
    form.reset();
    modalOpen.value = true;
}

function openUpdateModal(row) {
    isUpdate.value = true;
    form.id = row.id;
    form.name = row.name;
    form.percentage = row.percentage;
    form.level_category_id = row.level_category_id
    form.customizable = row.customizable;
    form.min_assessments = row.min_assessments;
    form.max_assessments = row.max_assessments;
    modalOpen.value = true;
}

const modalTitle = computed(() => (isUpdate.value ? "Update Assessment Type" : "Add Assessment Type"));

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
    return usePage().props.assessmentTypes.map((assessment_type) => {
        return {
            ...assessment_type,
            updated_at: moment(assessment_type.updated_at).fromNow()
        }
    })
})

const level_categories = computed(() => usePage().props.levelCategories)

const levelCategories = computed(() => {
    return [
        {label: 'All', value: null},
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
            return assessment_type.level_category_id === filterByLevelCategory.value;
        });
    }
    return assessmentTypes.value;
});

const levelCategoriesWithoutAll = computed(() => {
    return levelCategories.value.filter((category) => category.label !== 'All');
});


const config = [
    {
        name: 'Assessment type name',
        key: 'name',
        type: 'custom',
    },
    {
        name: 'Percentage',
        key: 'percentage',
        type: 'custom',
    },
    {
        name: 'Level Category',
        key: 'level_category',
        type: 'custom',
    },
    {
        name: 'Updated at',
        key: 'updated_at',
        type: 'custom',
    },
]

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

function submit() {
    if (form.customizable === false) {
        form.min_assessments = null;
        form.max_assessments = null;
    }

    form.post(route('assessments.type.create'), {
        onSuccess: () => {
            form.reset();
            modalOpen.value = false;
        },
        onError: () => {
            console.log('error');
        },
    });
}

function update(id) {
    if (form.customizable === false) {
        form.min_assessments = null;
        form.max_assessments = null;
    }
    form.post(route('assessments.type.update', id), {
        onSuccess: () => {
            form.reset();
            modalOpen.value = false;
        },
    });
}

const remove = () => {
    router.delete('/assessments/type/destroy/' + selectedAssessmentId.value, {
        onFinish: () => {
            console.log(selectedAssessmentId.value);
            isDialogBoxOpen.value = false;
            selectedAssessmentId.value = null;
        },
    });
}

const categoryColors = {
    'Kindergarten': 'bg-blue-500',
    'ElementarySchool': 'bg-orange-500',
    'HighSchool': 'bg-green-500'
}

</script>
<style scoped></style>
