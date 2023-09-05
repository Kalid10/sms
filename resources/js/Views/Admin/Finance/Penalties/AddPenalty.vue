<template>
    <FormElement :title="$t('addPenalty.addPenalty')" @submit="submit">
        <SelectInput
            v-model="form.type"
            :options="selectOptions"
            :label="$t('common.type')"
            :placeholder="$t('addPenalty.selectType')"
            :error="usePage().props.errors.type"
        />

        <TextInput
            v-model="form.amount"
            :placeholder="$t('addPenalty.amount')"
            :label="$t('addPenalty.amount')"
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
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const emit = defineEmits(["close"]);

const showNotification = inject("showNotification");
const isLoading = ref(false);

const selectOptions = [
    {
        value: "flat_rate",
        label: t("addPenalty.flatRate"),
    },
    {
        value: "percentage",
        label: t("addPenalty.percentage"),
    },
    {
        value: "daily",
        label: t("addPenalty.perDay"),
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
                message: t("addPenalty.errorAddingPenalty"),
            });
        },
    });
};
</script>

<style scoped></style>
