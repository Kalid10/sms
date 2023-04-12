<template>
    <div class="w-full">
        <div class="flex space-x-4">
            <CategoryTableElement
                :columns="config"
                :data="levelCategories"
                :selectable="false"
                :filterable="false"
                actionable
                row-actionable
                subtitle="list of level categories"
                title="Level Category"
            >
                <template #action>
                    <CategoryPrimaryButton
                        title="Add Level Category"
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
        <FormElement title="Add Level Category" subtitle="" @submit="submit">
            <TextInput
                v-model="form.name"
                label="Category"
                placeholder="ex: ElementarySchool"
            />
        </FormElement>
    </Modal>
    <!--    Update Modal-->
    <Modal v-model:view="updateCategory">
        <FormElement
            title="Update Level Category"
            subtitle=""
            @submit="updateCategoryForm(editForm.id)"
        >
            <TextInput
                v-model="editForm.name"
                label="Update Category"
                placeholder=""
            />
        </FormElement>
    </Modal>
    <DialogBox
        v-if="isDialogBoxOpen"
        @close="isDialogBoxOpen = false"
        @confirm="removeCategory"
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
        name: "Name",
        key: "name",
        sortable: true,
        searchable: true,
    },
];

const levelCategories = computed(() => usePage().props.levelCategories);

const form = useForm({
    name: "",
});

const editForm = useForm({
    name: "",
    id: "",
});

const updateCategoryForm = (id) => {
    editForm.post(route("level-category.update", id), {
        onSuccess: () => {
            updateCategory.value = false;
        },
    });
};

const submit = () => {
    form.post("level-category/create", {
        onSuccess: () => {
            toggleCategoryModal();
        },
    });
};

const removeCategory = () => {
    router.delete("/level-category/delete/" + selectedCategoryId.value, {
        onFinish: () => {
            isDialogBoxOpen.value = false;
            selectedCategoryId.value = null;
        },
    });
};
</script>
