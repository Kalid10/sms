<template>
    <FormElement title="Add Item" @cancel="itemForm.reset()" @submit="submit">
        <TextInput
            v-model="itemForm.name"
            label="Name"
            placeholder="Name"
            :error="usePage().props.errors.name"
        />

        <TextArea
            v-model="itemForm.description"
            placeholder="Description"
            label="Item Description"
            :error="usePage().props.errors.description"
        />
        <TextInput
            v-model="itemForm.quantity"
            label="Quantity"
            type="number"
            placeholder="Quantity"
            :error="usePage().props.errors.quantity"
        />
        <TextInput
            v-model="itemForm.low_stock_threshold"
            label="Low Stock Alert Threshold"
            type="number"
            placeholder="Enter the quantity at which to alert"
            :error="usePage().props.errors.low_stock_threshold"
        />

        <div class="flex w-full justify-between">
            <SelectInput
                v-model="itemForm.visibility"
                :options="visibilityOptions"
                placeholder="Select Visibility"
                class="w-5/12"
                :error="usePage().props.errors.visibility"
            />
            <Toggle
                v-model="itemForm.is_returnable"
                label="Is the Item Returnable?"
                :error="usePage().props.errors.is_returnable"
            />
        </div>
    </FormElement>

    <Loading v-if="isLoading" :is-full-screen="true" type="bounce" />
</template>
<script setup>
import TextInput from "@/Components/TextInput.vue";
import FormElement from "@/Components/FormElement.vue";
import TextArea from "@/Components/TextArea.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Toggle from "@/Components/Toggle.vue";
import { ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import Loading from "@/Components/Loading.vue";

const emit = defineEmits(["close"]);
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
    low_stock_threshold: "",
});

const submit = () => {
    isLoading.value = true;
    itemForm.post("/admin/inventory/create", {
        preserveState: true,
        onFinish: () => {
            isLoading.value = false;
            emit("close");
        },
    });
};
</script>
<style scoped></style>
