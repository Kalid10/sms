<template>
    <div class="my-5 flex min-h-full w-11/12 flex-col space-y-5">
        <div class="flex w-full items-center justify-between">
            <Title title="School Inventory" />
        </div>

        <div class="flex h-full w-full justify-between">
            <InventoryItems class="!w-7/12" />
            <PendingCheckouts class="!w-4/12" />
        </div>
        <Logs />
    </div>
</template>

<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import Logs from "@/Views/Admin/Inventory/Logs.vue";
import InventoryItems from "@/Views/Admin/Inventory/InventoryItems.vue";
import PendingCheckouts from "@/Views/Teacher/Inventory/PendingCheckouts.vue";
</script>

<style scoped></style>
