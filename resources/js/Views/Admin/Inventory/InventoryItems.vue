<template>
    <TableElement
        v-if="!!mappedInventoryItems"
        :columns="inventoryItemsTableConfig"
        :selectable="false"
        :filterable="false"
        :data="mappedInventoryItems"
        title="Inventory Items"
        header-style="!bg-brand-400 text-white"
        class="!w-6/12 !rounded-lg p-4 shadow-sm"
    >
        <template #table-header>
            <div class="flex w-full justify-evenly py-3">
                <div class="w-10/12 text-2xl font-semibold">
                    Inventory Items
                </div>
                <PrimaryButton class="h-fit w-fit" @click="emit('add')">
                    Add Item
                </PrimaryButton>
            </div>
        </template>

        <template #row-column="{ data }">
            <span
                class="rounded-lg px-3 py-1 font-semibold text-white"
                :class="
                    data.quantity === 0
                        ? 'bg-red-600'
                        : data.quantity <= data.low_stock_threshold
                        ? 'bg-orange-500'
                        : 'bg-positive-50'
                "
            >
                {{ data.quantity }}
            </span>
        </template>

        <template #date-column="{ data }">
            {{ moment(data).format("dddd MMM DD, YYYY") }}
        </template>

        <template #id-column="{ data }">
            <UserMinusIcon
                class="w-5 cursor-pointer hover:scale-125"
                @click="emit('allocate', data)"
            />
        </template>

        <template #footer>
            <Pagination
                :preserve-state="true"
                :links="inventoryItems.links"
                position="center"
            />
        </template>
    </TableElement>
</template>

<script setup>
import moment from "moment";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Pagination from "@/Components/Pagination.vue";
import { UserMinusIcon } from "@heroicons/vue/24/outline";

const emit = defineEmits(["add", "allocate"]);

const inventoryItems = computed(() => usePage().props.inventory_items);
const mappedInventoryItems = computed(() =>
    usePage().props.inventory_items.data.map((item) => {
        console.log(item);
        return {
            ...item,
            row: {
                quantity: item.quantity,
                low_stock_threshold: item.low_stock_threshold,
            },
        };
    })
);
const inventoryItemsTableConfig = [
    {
        name: "Name",
        key: "name",
        class: "font-semibold",
    },
    {
        name: "Quantity",
        key: "row",
        type: "custom",
    },

    {
        name: "Low Stock Alert Threshold",
        key: "low_stock_threshold",
        class: "uppercase",
    },
    {
        name: "Is Returnable?",
        key: "is_returnable",
        type: Boolean,
    },
    {
        name: "Added At",
        key: "date",
        type: "custom",
    },
    {
        name: "",
        key: "id",
        type: "custom",
    },
];
</script>
<style scoped></style>
