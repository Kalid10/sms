<template>
    <TableElement
        v-if="!!mappedPendingItems"
        :columns="pendingCheckoutsTableConfig"
        :selectable="false"
        :filterable="false"
        :data="mappedPendingItems"
        :title="$t('pendingInventoryItems.inventoryItems')"
        header-style="!bg-brand-400 text-white"
        class="!w-9/12 !rounded-lg p-4 shadow-sm"
    >
        <template #table-header>
            <div class="flex w-full justify-evenly py-5">
                <div
                    class="text-2xl font-semibold"
                    :class="canManageInventory ? 'w-10/12' : 'w-full px-6'"
                >
                    {{ $t("pendingInventoryItems.pendingCheckout") }}
                </div>
                <PrimaryButton
                    v-if="canManageInventory"
                    class="h-fit w-fit"
                    @click="emit('add')"
                >
                    {{ $t("common.addItem") }}
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
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useI18n } from "vue-i18n";

const { $t } = useI18n();
const emit = defineEmits(["add"]);

const canManageInventory = computed(() => usePage().props.can_manage_inventory);
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
        name: t("common.name"),
        key: "name",
        class: "font-semibold",
    },
    {
        name: t("common.quantity"),
        key: "quantity",
    },
    {
        name: t("inventoryLogs.status"),
        key: "status",
        class: "font-medium uppercase",
    },
    {
        name: t("inventoryLogs.to"),
        key: "recipient_user",
    },
    {
        name: t("inventoryLogs.from"),
        key: "provider_user",
    },

    {
        name: t("inventoryLogs.date"),
        key: "date",
        type: "custom",
    },
];
</script>
<style scoped></style>
