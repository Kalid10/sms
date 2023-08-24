<template>
    <TableElement
        v-if="!!mappedPendingItems"
        :columns="pendingCheckoutsTableConfig"
        :selectable="false"
        :filterable="false"
        :data="mappedPendingItems"
        title="Inventory Items"
        header-style="!bg-brand-400 text-white"
        class="!w-9/12 !rounded-lg p-4 shadow-sm"
    >
        <template #table-header>
            <div class="w-full px-6 py-5 text-2xl font-semibold">
                Pending CheckOuts
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

        <template #footer>
            <Pagination
                :preserve-state="true"
                :links="pendingItems.links"
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
import Pagination from "@/Components/Pagination.vue";

const pendingItems = computed(
    () => usePage().props.pending_inventory_check_outs
);
const mappedPendingItems = computed(() =>
    usePage().props.pending_inventory_check_outs.data.map((item) => {
        return {
            recipient_user: item.recipient.name,
            provider_user: item.provider.name,
            quantity: item.quantity,
            date: moment(item.date).format("dddd MMM DD, YYYY"),
            name: item.item.name,
            status: item.status,
        };
    })
);

const pendingCheckoutsTableConfig = [
    {
        name: "Name",
        key: "name",
        class: "font-semibold",
    },
    {
        name: "Quantity",
        key: "quantity",
    },
    {
        name: "Status",
        key: "status",
        class: "font-medium uppercase",
    },
    {
        name: "To",
        key: "recipient_user",
    },
    {
        name: "From",
        key: "provider_user",
    },

    {
        name: "Date",
        key: "date",
        type: "custom",
    },
];
</script>
<style scoped></style>
