<template>
    <div class="flex w-full flex-col items-center">
        <TableElement
            :data="mappedFees"
            :columns="columns"
            :selectable="false"
            :filterable="false"
            header-style="!bg-brand-400 text-white"
            class="h-full w-full !rounded-lg p-4 shadow-sm"
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
    </div>
</template>
<script setup>
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import moment from "moment";
import Modal from "@/Components/Modal.vue";
import AddFee from "@/Views/Admin/Finance/Fees/AddFee.vue";
import { upperCase } from "lodash";

const fees = computed(() => usePage().props.fees);

const showAddFeeForm = ref(false);
const mappedFees = computed(() => {
    return fees.value.map((fee) => {
        return {
            name: fee.name,
            amount: fee.amount + " " + fee.currency,
            due_date: moment(fee.due_date).format("MMMM DD, YYYY"),
            last_updated: moment(fee.updated_at).format("MMMM DD, YYYY"),
            is_active: fee.status === "active",
            penalty: fee.penalty
                ? upperCase(fee.penalty.type) +
                  " ( " +
                  fee.penalty.amount +
                  " ETB" +
                  " )"
                : "-",
            level_category: fee.level_category.name,
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
        name: "Categories",
        key: "level_category",
    },
    {
        key: "penalty",
        name: "Penalty",
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
