<template>
    <div
        class="flex w-6/12 flex-col space-y-4 rounded-lg"
        :class="pendingItems.data.length ? 'h-full' : 'h-3/5 justify-center'"
    >
        <div
            v-if="pendingItems.data.length"
            class="text-center text-xl font-semibold"
        >
            Your Pending Inventory CheckOuts
        </div>

        <div
            v-for="(item, index) in pendingItems.data"
            :key="index"
            class="rounded-lg bg-white p-4 shadow-sm"
        >
            <div class="flex flex-col items-center justify-between space-y-4">
                <div class="flex w-full justify-end text-xs">
                    {{ moment(item.date).format("dddd MMM DD, YYYY") }}
                </div>
                <div
                    class="flex w-full flex-col items-center space-y-6 py-4 font-semibold"
                >
                    {{ item.provider.name }} wants your approval for
                    {{ item.quantity }} X {{ item.item.name }} ?
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
</template>
<script setup>
import { computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import EmptyView from "@/Views/EmptyView.vue";
import moment from "moment";

const pendingItems = computed(
    () => usePage().props.pending_inventory_check_outs
);

const updateInventory = (status, inventory_item_id) => {
    router.post(
        "/teacher/extras/inventory/update",
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
