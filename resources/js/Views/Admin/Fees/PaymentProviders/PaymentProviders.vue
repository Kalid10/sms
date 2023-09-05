<template>
    <div class="h-full">
        <EmptyView
            v-if="paymentProviders.length === 0"
            :title="$t('paymentProviders.noPaymentProvider')"
            class="py-5"
            :sub-title="$t('paymentProviders.noPaymentProviderConfigured')"
        >
            <SecondaryButton
                class="!rounded-2xl bg-brand-400 text-white"
                :title="$t('paymentProviders.configurePaymentProvider')"
                @click="showPaymentProviderForm = true"
            />
        </EmptyView>

        <TableElement
            v-else
            :data="mappedPaymentProviders"
            :columns="columns"
            :selectable="false"
            :filterable="false"
            header-style="!bg-brand-400 text-white"
            class="h-full !w-8/12 !rounded-lg p-4 shadow-sm"
        >
            <template #table-header>
                <div class="flex w-full justify-between py-5">
                    <div class="text-xl font-semibold capitalize">
                        {{ $t("paymentProviders.paymentProviders") }}
                    </div>
                    <SecondaryButton
                        class="w-fit !rounded-2xl bg-brand-400 text-white"
                        :title="$t('paymentProviders.addPaymentProvider')"
                        @click="showPaymentProviderForm = true"
                    />
                </div>
            </template>
        </TableElement>

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
import PaymentProviderForm from "@/Views/Admin/Fees/PaymentProviders/PaymentProviderForm.vue";
import TableElement from "@/Components/TableElement.vue";
import moment from "moment";

const paymentProviders = computed(() => usePage().props.payment_providers);

const mappedPaymentProviders = computed(() => {
    return paymentProviders.value.map((paymentProvider) => {
        return {
            name: paymentProvider.name,
            is_enabled: paymentProvider.is_enabled,
            date: moment(paymentProvider.created_at).format("MMMM DD, YYYY"),
            last_updated: moment(paymentProvider.updated_at).format(
                "MMMM DD, YYYY"
            ),
        };
    });
});

const columns = [
    {
        key: "name",
        name: "Name",
    },
    {
        key: "is_enabled",
        name: "Enabled",
        type: Boolean,
    },
    {
        key: "date",
        name: "Date Created",
    },
    {
        key: "last_updated",
        name: "Last Updated",
    },
];

const showPaymentProviderForm = ref(false);
</script>
<style scoped></style>
