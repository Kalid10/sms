<template>
    <div class="flex min-h-full w-full flex-col space-y-5">
        <div class="flex h-full w-full justify-between space-x-4">
            <InventoryItems class="!w-7/12" />
            <PendingCheckouts class="!w-4/12" />
        </div>
        <Logs />
    </div>
</template>

<script setup>
import Logs from "@/Views/Admin/Inventory/Logs.vue";
import InventoryItems from "@/Views/Admin/Inventory/InventoryItems.vue";
import PendingCheckouts from "@/Views/Teacher/Inventory/PendingCheckouts.vue";
</script>

<style scoped></style>
