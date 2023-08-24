<template>
    <div class="my-5 flex min-h-full w-10/12 flex-col">
        <div class="flex w-full items-center justify-between">
            <Title title="Inventory Management" />
        </div>

        <TableElement
            v-if="!!inventoryItems?.data"
            :columns="tableConfig"
            :selectable="false"
            :filterable="false"
            :data="inventoryItems?.data"
            title="Inventory Items"
            header-style="!bg-brand-400 text-white"
            class="!rounded-lg p-4 shadow-sm"
        >
            <template #table-header>
                <div class="flex w-full justify-evenly py-3">
                    <div class="w-10/12 text-2xl font-semibold">
                        Inventory Items
                    </div>
                    <PrimaryButton
                        class="h-fit w-fit"
                        @click="showAddItemModal = true"
                    >
                        Add Item
                    </PrimaryButton>
                </div>
            </template>
            <template #is_returnable-column="{ data }">
                {{ data ? "Yes" : "No" }}
            </template>

            <template #date-column="{ data }">
                {{ moment(data).format("dddd MMM DD, YYYY") }}
            </template>

            <template #id-column="{ data }">
                <UserMinusIcon
                    class="w-5 cursor-pointer hover:scale-125"
                    @click="
                        showAllocateItemModal = true;
                        selectedItemId = data;
                    "
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
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import Pagination from "@/Components/Pagination.vue";
import moment from "moment/moment";
import { UserMinusIcon } from "@heroicons/vue/24/outline";
import AddItem from "@/Views/Admin/Inventory/AddItem.vue";
import AllocateItem from "@/Views/Admin/Inventory/AllocateItem.vue";

const showAddItemModal = ref(false);

const inventoryItems = computed(() => usePage().props.inventory_items);
const tableConfig = [
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
        name: "Is Returnable?",
        key: "is_returnable",
        type: "custom",
    },
    {
        name: "Status",
        key: "status",
        class: "uppercase",
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

const showAllocateItemModal = ref(false);
const selectedItemId = ref(null);
</script>
<style scoped></style>
