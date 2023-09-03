<template>
    <FormElement title="Add Penalty" @submit="submit">
        <SelectInput
            v-model="form.type"
            :options="selectOptions"
            label="Type"
            placeholder="Select Type"
            :error="usePage().props.errors.type"
        />

        <TextInput
            v-model="form.amount"
            placeholder="Amount"
            label="Amount"
            type="number"
            :error="usePage().props.errors.amount"
        />

        <Loading v-if="isLoading" is-full-screen />
    </FormElement>
</template>
<script setup>
import FormElement from "@/Components/FormElement.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import { inject, ref } from "vue";
import Loading from "@/Components/Loading.vue";

const emit = defineEmits(["close"]);

const showNotification = inject("showNotification");
const isLoading = ref(false);

const selectOptions = [
    {
        value: "flat_rate",
        label: "Flat Rate",
    },
    {
        value: "percentage",
        label: "Percentage",
    },
    {
        value: "daily",
        label: "Per Day",
    },
];
const form = useForm({
    type: "",
    amount: "",
});

const submit = () => {
    isLoading.value = true;
    form.post("/admin/penalties/create", {
        preserveScroll: true,
        onSuccess: () => {
            isLoading.value = false;
            emit("close");
        },
        onError: () => {
            isLoading.value = false;
            showNotification({
                type: "error",
                message: "There was an error adding the penalty.",
            });
        },
    });
};
</script>

<style scoped></style>
