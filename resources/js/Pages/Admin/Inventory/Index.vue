<template>
    <div class="my-5 flex min-h-full w-10/12 flex-col">
        <div class="flex w-full items-center justify-between">
            <Title title="Inventory Management" />
        </div>

        <TableElement
            v-if="!!inventoryItems?.data"
            :columns="tableConfig"
            :selectable="false"
            :filterable="false"
            :data="inventoryItems?.data"
            title="Inventory Items"
            header-style="!bg-brand-400 text-white"
            class="!rounded-lg p-4 shadow-sm"
        >
            <template #table-header>
                <div class="flex w-full justify-evenly py-3">
                    <div class="w-10/12 text-2xl font-semibold">
                        Inventory Items
                    </div>
                    <PrimaryButton
                        class="h-fit w-fit"
                        @click="showAddItemModal = true"
                    >
                        Add Item
                    </PrimaryButton>
                </div>
            </template>
            <template #is_returnable-column="{ data }">
                {{ data ? "Yes" : "No" }}
            </template>
            <template #date-column="{ data }">
                {{ moment(data).format("dddd MMM DD, YYYY") }}
            </template>
            <template #footer>
                <Pagination
                    :preserve-state="true"
                    :links="inventoryItems.links"
                    position="center"
                />
            </template>
        </TableElement>

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
import { computed, ref } from "vue";
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import Toggle from "@/Components/Toggle.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextArea from "@/Components/TextArea.vue";
import Loading from "@/Components/Loading.vue";
import TableElement from "@/Components/TableElement.vue";
import Pagination from "@/Components/Pagination.vue";
import moment from "moment/moment";

const inventoryItems = computed(() => usePage().props.inventory_items);
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

const tableConfig = [
    {
        name: "Name",
        key: "name",
        class: "font-semibold",
    },
    {
        name: "Quantity",
        key: "quantity",
    },
    {
        name: "Is Returnable?",
        key: "is_returnable",
        type: "custom",
    },
    {
        name: "Status",
        key: "status",
        class: "uppercase",
    },
    {
        name: "Added At",
        key: "date",
        type: "custom",
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
