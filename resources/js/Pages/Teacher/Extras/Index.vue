<template>
    <div class="my-5 flex min-h-full w-11/12 flex-col space-y-5">
        <Title title="Extras" />
        <TabElement
            v-model:active="activeTab"
            in-active-tab-text="text-brand-text-100"
            :tabs="tabs"
        >
            <template #inventory>
                <InventoryPage />
            </template>
        </TabElement>
    </div>
</template>
<script setup>
import TabElement from "@/Components/TabElement.vue";
import { ref } from "vue";
import InventoryPage from "@/Pages/Teacher/Extras/Inventory/Index.vue";
import Title from "@/Views/Teacher/Views/Title.vue";

const tabs = ["Inventory", "Attendance"];
const activeTab = ref("Inventory");
</script>
<style scoped></style>
