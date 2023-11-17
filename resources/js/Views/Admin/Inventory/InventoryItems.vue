<template>
    <TableElement
        v-if="!!mappedInventoryItems"
        :columns="inventoryItemsTableConfig"
        :selectable="false"
        :filterable="false"
        :data="mappedInventoryItems"
        :title="$t('pendingInventoryItems.inventoryItems')"
        header-style="!bg-brand-400 text-white"
        class="h-full !w-8/12 !rounded-lg p-4 shadow-sm"
    >
        <template #table-header>
            <div class="flex w-full justify-evenly py-5">
                <div
                    class="text-2xl font-semibold"
                    :class="canManageInventory ? 'w-10/12' : 'w-full px-6'"
                >
                    {{ $t("pendingInventoryItems.inventoryItems") }}
                </div>
                <PrimaryButton
                    v-if="canManageInventory"
                    class="flex h-fit items-center justify-center space-x-1 bg-brand-450"
                    @click="emit('add')"
                >
                    <SquaresPlusIcon class="w-4 stroke-white stroke-2" />
                    <span>
                        {{ $t("pendingInventoryItems.addItem") }}
                    </span>
                </PrimaryButton>
            </div>
        </template>

        <template #row-column="{ data }">
            <span
                class="rounded-lg px-3 py-1 font-semibold"
                :class="
                    data.quantity === 0
                        ? 'bg-red-600 text-white'
                        : data.quantity <= data.low_stock_threshold
                        ? 'bg-orange-500 text-white'
                        : 'bg-emerald-400 text-black'
                "
            >
                {{ data.quantity }}
            </span>
        </template>

        <template #date-column="{ data }">
            {{ moment(data).format("dddd MMM DD, YYYY") }}
        </template>

        <template v-if="isAdmin()" #id-column="{ data }">
            <UserMinusIcon
                class="w-5 cursor-pointer hover:scale-125"
                @click="emit('allocate', data)"
            />
        </template>
        <template v-if="isAdmin()" #item-column="{ data }">
            <PlusCircleIcon
                class="hover:brand500 w-5 cursor-pointer text-brand-400 hover:scale-125"
                @click="emit('fill', data)"
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
import { computed, onBeforeMount } from "vue";
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Pagination from "@/Components/Pagination.vue";
import { SquaresPlusIcon, UserMinusIcon } from "@heroicons/vue/24/outline";
import { PlusCircleIcon } from "@heroicons/vue/24/solid";
import { isAdmin } from "@/utils";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const emit = defineEmits(["add", "allocate", "fill"]);

const canManageInventory = computed(() => usePage().props.can_manage_inventory);
const inventoryItems = computed(() => usePage().props.inventory_items);
const mappedInventoryItems = computed(() =>
    usePage().props.inventory_items.data.map((item) => {
        return {
            ...item,
            row: {
                quantity: item.quantity,
                low_stock_threshold: item.low_stock_threshold,
            },
            item: item,
        };
    })
);
const inventoryItemsTableConfig = [
    {
        name: t("common.name"),
        key: "name",
        class: "font-semibold",
    },
    {
        name: t("common.quantity"),
        key: "row",
        type: "custom",
    },
    {
        name: t("inventoryItems.isReturnable"),
        key: "is_returnable",
        type: Boolean,
    },
    {
        name: t("inventoryItems.addedAt"),
        key: "date",
        type: "custom",
    },
    {
        name: "",
        key: "id",
        type: "custom",
    },
    {
        name: "",
        key: "item",
        type: "custom",
    },
];

onBeforeMount(() => {
    if (isAdmin()) {
        inventoryItemsTableConfig.splice(2, 0, {
            name: "Low Stock Threshold",
            key: "low_stock_threshold",
        });
    }
});
</script>
<style scoped></style>
