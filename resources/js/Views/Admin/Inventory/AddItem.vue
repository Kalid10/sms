<template>
    <FormElement
        :title="$t('inventoryAddItem.addItem')"
        @cancel="itemForm.reset()"
        @submit="submit"
    >
        <TextInput
            v-model="itemForm.name"
            :label="$t('common.name')"
            :placeholder="$t('common.name')"
            :error="usePage().props.errors.name"
        />

        <TextArea
            v-model="itemForm.description"
            :placeholder="$t('common.description')"
            :label="$t('inventoryAddItem.itemDescription')"
            :error="usePage().props.errors.description"
        />
        <TextInput
            v-model="itemForm.quantity"
            :label="$t('common.quantity')"
            :placeholder="$t('common.quantity')"
            type="number"
            :error="usePage().props.errors.quantity"
        />
        <TextInput
            v-model="itemForm.low_stock_threshold"
            :label="$t('inventoryAddItem.lowStockAlertThreshold')"
            :placeholder="$t('inventoryAddItem.enterQuantityAlert')"
            type="number"
            :error="usePage().props.errors.low_stock_threshold"
        />

        <div class="flex w-full justify-between">
            <SelectInput
                v-model="itemForm.visibility"
                :options="visibilityOptions"
                :placeholder="$t('inventoryAddItem.selectVisibility')"
                class="w-5/12"
                :error="usePage().props.errors.visibility"
            />
            <Toggle
                v-model="itemForm.is_returnable"
                :label="$t('inventoryAddItem.isItemReturnable')"
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
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const emit = defineEmits(["close"]);
const isLoading = ref(false);

const visibilityOptions = [
    {
        label: t("common.all"),
        value: "all",
    },
    {
        label: t("common.teachers"),
        value: "teachers",
    },
    {
        label: t("common.admins"),
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
