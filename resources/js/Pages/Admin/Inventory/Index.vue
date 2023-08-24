<template>
    <div class="my-5 flex min-h-full w-11/12 flex-col space-y-6">
        <div class="flex w-full items-center justify-between">
            <Title title="Inventory Management" />
        </div>

        <div class="flex h-full w-full flex-col items-center justify-between">
            <div class="flex h-4/6 w-full justify-between">
                <InventoryItems
                    v-if="showInventoryItems"
                    @add="showAddItemModal = true"
                    @allocate="setupSelectedItem"
                />
                <PendingInventoryItems v-else />
                <div
                    class="flex h-fit w-2/12 flex-col items-center justify-center space-y-4 rounded-xl bg-brand-400 p-4 text-white shadow-sm"
                >
                    <div class="text-8xl font-bold">4</div>
                    <div class="font-light">
                        {{
                            showInventoryItems
                                ? "Pending Transactions"
                                : "Total Inventory Items"
                        }}
                    </div>
                    <SecondaryButton
                        :title="
                            showInventoryItems
                                ? 'View Transactions'
                                : 'View Items'
                        "
                        class="w-full !rounded-2xl bg-brand-200 font-semibold"
                        @click="showInventoryItems = !showInventoryItems"
                    />
                </div>
            </div>

            <Logs />
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
    </div>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";
import AddItem from "@/Views/Admin/Inventory/AddItem.vue";
import AllocateItem from "@/Views/Admin/Inventory/AllocateItem.vue";
import InventoryItems from "@/Views/Admin/Inventory/InventoryItems.vue";
import Logs from "@/Views/Admin/Inventory/Logs.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PendingInventoryItems from "@/Views/Admin/Inventory/PendingInventoryItems.vue";

const showAddItemModal = ref(false);
const showAllocateItemModal = ref(false);
const selectedItemId = ref(null);
const showInventoryItems = ref(true);

const setupSelectedItem = (value) => {
    selectedItemId.value = value;
    showAllocateItemModal.value = true;
};
</script>
<style scoped></style>
