<template>
    <FormElement title="Add Item" @cancel="itemForm.reset()" @submit="submit">
        <TextInput v-model="itemForm.name" label="Name" placeholder="Name" />

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

    <Loading v-if="isLoading" :is-full-screen="true" type="bounce" />
</template>
<script setup>
import TextInput from "@/Components/TextInput.vue";
import FormElement from "@/Components/FormElement.vue";
import TextArea from "@/Components/TextArea.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Toggle from "@/Components/Toggle.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
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
