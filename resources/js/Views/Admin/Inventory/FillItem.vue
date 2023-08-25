<template>
    <FormElement :title="'Fill ' + item.name + ' inventory'" @submit="submit">
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
    </FormElement>

    <Loading v-if="isLoading" :is-full-screen="true" type="bounce" />
</template>
<script setup>
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import Loading from "@/Components/Loading.vue";
import { ref } from "vue";

const emit = defineEmits(["close"]);

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const isLoading = ref(false);

const itemForm = useForm({
    quantity: "",
    low_stock_threshold: "",
});

const submit = () => {
    isLoading.value = ref(true);
    router.post(
        "/admin/inventory/fill",
        {
            inventory_item_id: props.item.id,
            ...itemForm.data(),
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                itemForm.reset();
                emit("close");
            },
            onFinish: () => {
                isLoading.value = false;
            },
        }
    );
};
</script>

<style scoped></style>
