<template>
    <TableElement
        :data="mappedFees"
        :columns="columns"
        :selectable="false"
        :filterable="false"
        header-style="!bg-brand-400 text-white"
        class="h-full !w-8/12 !rounded-lg p-4 shadow-sm"
    >
        <template #table-header>
            <div class="flex w-full justify-between py-5">
                <div class="text-xl font-semibold capitalize">Fees</div>
                <SecondaryButton
                    class="w-fit !rounded-2xl bg-brand-400 text-white"
                    title="Add Fee"
                    @click="showAddFeeForm = true"
                />
            </div>
        </template>
    </TableElement>

    <Modal v-model:view="showAddFeeForm">
        <AddFee @close="showAddFeeForm = false" />
    </Modal>
</template>
<script setup>
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import moment from "moment";
import Modal from "@/Components/Modal.vue";
import AddFee from "@/Views/Admin/Fees/Fees/AddFee.vue";

const fees = computed(() => usePage().props.fees);

const showAddFeeForm = ref(false);
const mappedFees = computed(() => {
    return fees.value.map((fee) => {
        return {
            name: fee.name,
            amount: fee.amount,
            due_date: moment(fee.due_date).format("MMMM DD, YYYY"),
            last_updated: moment(fee.updated_at).format("MMMM DD, YYYY"),
            is_active: fee.status === "active",
        };
    });
});

const columns = [
    {
        key: "name",
        name: "Name",
    },
    {
        key: "amount",
        name: "Amount",
    },

    {
        key: "is_active",
        name: "Active",
        type: Boolean,
    },
    {
        key: "due_date",
        name: "Due Date",
    },

    {
        key: "last_updated",
        name: "Last Updated",
    },
];
</script>
<style scoped></style>
