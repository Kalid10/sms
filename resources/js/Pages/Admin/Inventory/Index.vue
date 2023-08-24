<template>
    <div class="my-5 flex min-h-full w-11/12 flex-col">
        <div class="flex w-full items-center justify-between">
            <Title title="Inventory Management" />
        </div>

        <div class="flex w-full justify-between space-x-4">
            <InventoryItems
                @add="showAddItemModal = true"
                @allocate="setupSelectedItem"
            />
            <PendingInventoryItems />
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
import PendingInventoryItems from "@/Views/Admin/Inventory/PendingInventoryItems.vue";
import InventoryItems from "@/Views/Admin/Inventory/InventoryItems.vue";

const showAddItemModal = ref(false);
const showAllocateItemModal = ref(false);
const selectedItemId = ref(null);

const setupSelectedItem = (value) => {
    selectedItemId.value = value;
    showAllocateItemModal.value = true;
};
</script>
<style scoped></style>
