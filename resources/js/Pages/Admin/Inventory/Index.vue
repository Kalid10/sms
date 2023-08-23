<template>
    <div class="my-5 flex min-h-full w-10/12 flex-col">
        <div class="flex w-full items-center justify-between">
            <Title title="Inventory Management" />
            <PrimaryButton class="h-fit w-fit" @click="showAddItemModal = true">
                Add Item
            </PrimaryButton>
        </div>

        <Modal v-model:view="showAddItemModal">
            <FormElement
                title="Add Item"
                @cancel="itemForm.reset()"
                @submit="submit"
            >
                <TextInput
                    v-model="itemForm.name"
                    label="Name"
                    placeholder="Name"
                />
                <TextArea
                    v-model="itemForm.description"
                    placeholder="Description"
                    label="Item Description"
                />
                <TextInput
                    v-model="itemForm.quantity"
                    label="Quantity"
                    type="number"
                    placeholder="Quantity"
                />
                <div class="flex w-full justify-between">
                    <SelectInput
                        v-model="itemForm.visibility"
                        :options="visibilityOptions"
                        placeholder="Select Visibility"
                        class="w-5/12"
                    />
                    <Toggle
                        v-model="itemForm.is_returnable"
                        label="Is the Item Returnable?"
                    />
                </div>
            </FormElement>
        </Modal>
        <Loading v-if="isLoading" :is-full-screen="true" type="bounce" />
    </div>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import Toggle from "@/Components/Toggle.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextArea from "@/Components/TextArea.vue";
import Loading from "@/Components/Loading.vue";

const showAddItemModal = ref(false);
const isLoading = ref(false);
const visibilityOptions = [
    {
        label: "All",
        value: "all",
    },
    {
        label: "Teachers",
        value: "teachers",
    },
    {
        label: "Admins",
        value: "admins",
    },
];
const itemForm = useForm({
    name: "",
    description: "",
    quantity: "",
    is_returnable: false,
    visibility: "",
});

const submit = () => {
    isLoading.value = true;
    itemForm.post("/admin/inventory/create", {
        preserveState: true,
        onFinish: () => {
            isLoading.value = false;
            showAddItemModal.value = false;
        },
    });
};
</script>
<style scoped></style>
