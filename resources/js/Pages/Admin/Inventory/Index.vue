<template>
    <div class="my-5 flex min-h-full w-11/12 flex-col space-y-6">
        <div class="flex w-full items-center justify-between">
            <Title :title="$t('inventoryIndex.inventoryManagement')" />
        </div>

        <div class="flex h-full w-full flex-col items-center justify-between">
            <div class="flex h-4/6 w-full justify-between">
                <InventoryItems
                    v-if="showInventoryItems"
                    @add="showAddItemModal = true"
                    @allocate="setupSelectedItem"
                    @fill="setupFill"
                />
                <PendingInventoryItems v-else @add="showAddItemModal = true" />
                <div
                    class="flex h-fit w-2/12 flex-col items-center justify-center space-y-4 rounded-xl bg-brand-400 p-4 text-white shadow-sm"
                >
                    <div class="text-8xl font-bold">
                        {{
                            !showInventoryItems
                                ? inventoryItemCount
                                : pendingCount
                        }}
                    </div>
                    <div class="font-light">
                        {{
                            showInventoryItems
                                ? $t("inventoryIndex.pendingTransactions")
                                : $t("inventoryIndex.totalInventoryItems")
                        }}
                    </div>

                    <div v-if="pendingCount > 0">
                        <SecondaryButton
                            :title="
                                showInventoryItems
                                    ? $t('inventoryIndex.viewTransactions')
                                    : $t('inventoryIndex.viewItems')
                            "
                            class="w-full !rounded-2xl bg-brand-200 font-semibold"
                            @click="handleButtonClick"
                        >
                            {{
                                showInventoryItems
                                    ? $t("inventoryIndex.viewPending")
                                    : $t("inventoryIndex.viewInventoryItems")
                            }}
                        </SecondaryButton>
                    </div>

                    <div v-else>
                        <span class="text-sm font-light">
                            No pending transactions
                        </span>
                    </div>
                </div>
            </div>

            <Logs v-if="showLatestLogs || showLogs" ref="logsComponent" />
        </div>

        <Modal v-model:view="showAddItemModal">
            <AddItem @close="showAddItemModal = false" />
        </Modal>

        <Modal v-model:view="showAllocateItemModal">
            <AllocateItem
                :selected-item-id="selectedItemId"
                @close="showAllocateItemModal = false"
            />
        </Modal>

        <Modal v-model:view="showFillItemModal">
            <FillItem :item="selectedItem" @close="showFillItemModal = false" />
        </Modal>
    </div>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import Modal from "@/Components/Modal.vue";
import { computed, nextTick, ref } from "vue";
import AddItem from "@/Views/Admin/Inventory/AddItem.vue";
import AllocateItem from "@/Views/Admin/Inventory/AllocateItem.vue";
import InventoryItems from "@/Views/Admin/Inventory/InventoryItems.vue";
import Logs from "@/Views/Admin/Inventory/Logs.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PendingInventoryItems from "@/Views/Admin/Inventory/PendingInventoryItems.vue";
import FillItem from "@/Views/Admin/Inventory/FillItem.vue";
import { usePage } from "@inertiajs/vue3";

const showAddItemModal = ref(false);
const showAllocateItemModal = ref(false);
const selectedItemId = ref(null);
const showInventoryItems = ref(true);
const showFillItemModal = ref(false);
const showLatestLogs = ref(false);
const selectedItem = ref();
const pendingCount = computed(() => usePage().props.pending_count);
const inventoryItemCount = computed(() => usePage().props.inventory_count);

const logsComponent = ref(null);

const smoothScrollToLogs = () => {
    nextTick(() => {
        logsComponent.value.$el.scrollIntoView({ behavior: "smooth" });
    });
};

const setupSelectedItem = (item) => {
    selectedItemId.value = item;
    showAllocateItemModal.value = true;
};

const setupFill = (value) => {
    selectedItem.value = value;
    showFillItemModal.value = true;
};

const showLogs = computed(() => {
    return pendingCount.value > 0;
});

const handleButtonClick = () => {
    showLatestLogs.value = !showLatestLogs.value;
    smoothScrollToLogs();
};
</script>
<style scoped></style>
