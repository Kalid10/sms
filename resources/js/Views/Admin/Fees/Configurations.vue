<template>
    <div class="h-full">
        <EmptyView
            v-if="paymentProviders.length === 0"
            title="No Payment Providers Found"
            class="py-5"
            sub-title="  There are no payment providers configured. To start accepting
            payments, please configure a payment provider first."
        >
            <SecondaryButton
                class="!rounded-2xl bg-brand-400 text-white"
                title="Configure Payment Provider"
                @click="showPaymentProviderForm = true"
            />
        </EmptyView>

        <Modal v-model:view="showPaymentProviderForm">
            <PaymentProviderForm @close="showPaymentProviderForm = false" />
        </Modal>
    </div>
</template>
<script setup>
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import EmptyView from "@/Views/EmptyView.vue";
import Modal from "@/Components/Modal.vue";
import PaymentProviderForm from "@/Views/Admin/Fees/PaymentProviderForm.vue";

const fees = computed(() => usePage().props.fees);
const paymentProviders = computed(() => usePage().props.payment_providers);

const showPaymentProviderForm = ref(false);
</script>
<style scoped></style>
