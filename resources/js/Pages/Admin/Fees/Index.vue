<template>
    <div class="my-5 flex min-h-full w-11/12 flex-col space-y-6">
        <div class="flex w-full items-center justify-between">
            <Title title="Finance" />
        </div>

        <TabElement
            v-model:active="activeTab"
            in-active-tab-text="text-brand-text-100"
            :tabs="tabs"
        >
            <template #[studentTuitionsTab]>
                <StudentTuitions />
            </template>

            <template #[feesTab]>
                <FeeTab />
            </template>

            <template #[penaltyTab]>
                <PenaltyTabView />
            </template>

            <template #[paymentProvidersTab]>
                <PaymentProviders />
            </template>
        </TabElement>
    </div>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import TabElement from "@/Components/TabElement.vue";
import { useI18n } from "vue-i18n";
import { toUnderscore } from "@/utils";
import { computed, onBeforeMount, ref } from "vue";
import PaymentProviders from "@/Views/Admin/Fees/PaymentProviders/PaymentProviders.vue";
import StudentTuitions from "@/Views/Admin/Fees/StudentTuitions.vue";
import { usePage } from "@inertiajs/vue3";
import FeeTab from "@/Views/Admin/Fees/Fees/Index.vue";
import PenaltyTabView from "@/Views/Admin/Fees/Penalties/Index.vue";

const { t } = useI18n();

const paymentProvidersTab = toUnderscore(t("fees.paymentProviders"));
const feesTab = toUnderscore(t("fees.fees"));
const studentTuitionsTab = toUnderscore(t("fees.studentTuitions"));
const penaltyTab = toUnderscore(t("fees.penalties"));

const tabs = [studentTuitionsTab, feesTab, penaltyTab, paymentProvidersTab];
const activeTab = ref(penaltyTab);

const fees = computed(() => usePage().props.fees);
const paymentProviders = computed(() => usePage().props.payment_providers);

onBeforeMount(() => {
    if (paymentProviders.value.length === 0) {
        activeTab.value = paymentProvidersTab;
    } else if (fees.value.length === 0) {
        activeTab.value = feesTab;
    }
});
</script>
<style scoped></style>
