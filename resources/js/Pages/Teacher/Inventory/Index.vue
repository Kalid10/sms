<template>
    <div class="my-5 flex min-h-full w-10/12 flex-col space-y-5">
        <div class="flex w-full items-center justify-between">
            <Title title="School Inventory" />
        </div>

        <div
            class="flex w-6/12 flex-col space-y-4 rounded-lg"
            :class="
                pendingItems.data.length ? 'h-full' : 'h-3/5 justify-center'
            "
        >
            <div
                v-if="pendingItems.data.length"
                class="text-center text-xl font-semibold"
            >
                Your Pending Inventory Check outs
            </div>

            <div
                v-for="(item, index) in pendingItems.data"
                :key="index"
                class="rounded-lg bg-white p-4 shadow-sm"
            >
                <div
                    class="flex flex-col items-center justify-between space-y-4 text-sm lg:flex-row lg:space-y-0"
                >
                    <div class="flex flex-col items-center space-y-6 lg:w-5/12">
                        <div class="text-gray-800">
                            Item: {{ item.item.name }}
                        </div>
                        <div class="text-gray-600">
                            Quantity: {{ item.quantity }}
                        </div>
                    </div>
                    <div class="flex flex-col space-y-4 lg:w-5/12">
                        <div class="text-gray-600">
                            From, {{ item.provider.name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ moment(item.date).format("dddd MMM DD, YYYY") }}
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex justify-evenly">
                    <SecondaryButton
                        title="Decline"
                        class="rounded bg-red-600 py-2 px-4 text-white hover:bg-red-700"
                        @click="updateInventory('declined', item.id)"
                    />
                    <SecondaryButton
                        title="Confirm"
                        class="rounded bg-brand-400 py-2 px-4 text-white hover:bg-brand-500"
                        @click="updateInventory('approved', item.id)"
                    />
                </div>
            </div>

            <EmptyView
                v-if="!pendingItems.data.length"
                title="You dont have any pending check outs"
            />
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import Title from "@/Views/Teacher/Views/Title.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import moment from "moment";
import EmptyView from "@/Views/EmptyView.vue";

const pendingItems = computed(
    () => usePage().props.pending_inventory_check_outs
);

const updateInventory = (status, inventory_item_id) => {
    router.post(
        "/teacher/inventory/update",
        {
            status: status,
            inventory_item_id: inventory_item_id,
        },
        {
            onSuccess: () => {
                Notification.success("Inventory Updated Successfully");
            },
            onError: () => {
                Notification.error("Something went wrong");
            },
        }
    );
};
</script>

<style scoped></style>
