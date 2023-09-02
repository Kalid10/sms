<template>
    <div class="my-5 flex min-h-full w-10/12 flex-col">
        <div class="flex space-x-4">
            <CategoryTableElement
                :columns="config"
                :data="levelCategories"
                :selectable="false"
                :filterable="false"
                actionable
                row-actionable
                :subtitle="$t('levelCategory.categoryTableSubtitle')"
                :title="$t('levelCategory.categoryTableTitle')"
            >
                <template #action>
                    <CategoryPrimaryButton
                        :title="$t('levelCategory.addLevelCategory')"
                        @click="toggleCategoryModal"
                    />
                </template>
                <template #row-actions="{ row }">
                    <button @click="editCategory(row)">
                        <PencilSquareIcon class="h-4 w-4" />
                    </button>
                    <button @click="toggleDialogBox(row.id)">
                        <TrashIcon class="h-4 w-4" />
                    </button>
                </template>
            </CategoryTableElement>
        </div>
    </div>

    <!-- Add Modal-->
    <Modal v-model:view="addCategory">
        <FormElement
            :title="$t('levelCategory.addLevelCategory')"
            subtitle=""
            @submit="submit"
        >
            <TextInput
                v-model="form.name"
                :label="$t('levelCategory.category')"
                :placeholder="$t('levelCategory.addCategoryNamePlaceholder')"
                :subtext="$t('levelCategory.description')"
                class="gap-2"
            />
        </FormElement>
    </Modal>
    <!--    Update Modal-->
    <Modal v-model:view="updateCategory">
        <FormElement
            :title="$t('levelCategory.updateLevelCategory')"
            subtitle=""
            @submit="updateCategoryForm(editForm.id)"
        >
            <TextInput
                v-model="editForm.name"
                :label="$t('levelCategory.updateCategory')"
                placeholder=""
            />
        </FormElement>
    </Modal>
    <DialogBox
        v-if="isDialogBoxOpen"
        open
        @confirm="removeCategory"
        @abort="isDialogBoxOpen = false"
    />
</template>
<script setup>
import CategoryPrimaryButton from "@/Components/PrimaryButton.vue";
import CategoryTableElement from "@/Components/TableElement.vue";
import { computed, ref } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue";
import { PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/outline";
import DialogBox from "@/Components/DialogBox.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const addCategory = ref(false);
const updateCategory = ref(false);

const isDialogBoxOpen = ref(false);

const selectedCategoryId = ref(null);

function toggleCategoryModal() {
    addCategory.value = !addCategory.value;
}

function toggleDialogBox(id) {
    isDialogBoxOpen.value = !isDialogBoxOpen.value;
    selectedCategoryId.value = id;
}

function editCategory(row) {
    updateCategory.value = !updateCategory.value;
    editForm.name = row.name;
    editForm.id = row.id;
}

const config = [
    {
        name: t("common.name"),
        key: "name",
        sortable: true,
        searchable: true,
    },
];

const levelCategories = computed(() => usePage().props.level_categories);

const form = useForm({
    name: "",
});

const editForm = useForm({
    name: "",
    id: "",
});

const updateCategoryForm = (id) => {
    editForm.post(route("level-categories.update", id), {
        onSuccess: () => {
            updateCategory.value = false;
        },
    });
};

const submit = () => {
    form.post("/level-categories/create", {
        onSuccess: () => {
            toggleCategoryModal();
        },
    });
};

const removeCategory = () => {
    router.delete("/level-categories/delete/" + selectedCategoryId.value, {
        onFinish: () => {
            isDialogBoxOpen.value = false;
            selectedCategoryId.value = null;
        },
    });
};
</script>
