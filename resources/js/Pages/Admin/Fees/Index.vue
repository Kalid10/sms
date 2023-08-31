<template>
    <div class="my-5 flex min-h-full w-11/12 flex-col space-y-6">
        <div class="flex w-full items-center justify-between">
            <Title title="Fees" />
        </div>

        <TabElement
            v-model:active="activeTab"
            in-active-tab-text="text-brand-text-100"
            :tabs="tabs"
        >
            <template #[configurationTab]>
                <Configurations />
            </template>

            <template #[studentTuitionsTab]>
                <StudentTuitions />
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
import Configurations from "@/Views/Admin/Fees/Configurations.vue";
import StudentTuitions from "@/Views/Admin/Fees/StudentTuitions.vue";
import { usePage } from "@inertiajs/vue3";

const { t } = useI18n();
const configurationTab = toUnderscore(t("fees.configuration"));
const studentTuitionsTab = toUnderscore(t("fees.studentTuitions"));
const tabs = [studentTuitionsTab, configurationTab];
const activeTab = ref(studentTuitionsTab);

const fees = computed(() => usePage().props.fees);
const paymentProviders = computed(() => usePage().props.payment_providers);

onBeforeMount(() => {
    if (paymentProviders.value.length === 0 || fees.value.length === 0) {
        activeTab.value = configurationTab;
    }
});
</script>
<style scoped></style>
