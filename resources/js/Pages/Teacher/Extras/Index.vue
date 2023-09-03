<template>
    <div class="flex min-h-full w-11/12 flex-col space-y-5 py-5">
        <Title title="Extras" />
        <TabElement
            v-model:active="activeTab"
            in-active-tab-text="text-brand-text-100"
            :tabs="tabs"
        >
            <template #[inventoryTab]>
                <InventoryPage />
            </template>

            <template #[absenteeTab]>
                <AbsenteePage />
            </template>

            <template #[schedulesTab]>
                <BatchSchedule />
            </template>
        </TabElement>
    </div>
</template>
<script setup>
import TabElement from "@/Components/TabElement.vue";
import { ref } from "vue";
import InventoryPage from "@/Views/Teacher/Extras/Inventory/Index.vue";
import Title from "@/Views/Teacher/Views/Title.vue";
import AbsenteePage from "@/Views/Teacher/Extras/Absentee/Index.vue";
import { toUnderscore } from "@/utils";
import { useI18n } from "vue-i18n";
import BatchSchedule from "@/Views/Teacher/BatchSchedule/Index.vue";

const { t } = useI18n();
const inventoryTab = toUnderscore(t("common.inventory"));
const absenteeTab = toUnderscore(t("common.absentee"));
const schedulesTab = toUnderscore(t("common.schedules"));
const activeTab = ref(absenteeTab);
const tabs = [inventoryTab, absenteeTab, schedulesTab];
</script>
<style scoped></style>
