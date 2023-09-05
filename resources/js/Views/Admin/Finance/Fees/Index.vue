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
                    <SelectInput
                        v-model="selectedFeeableType"
                        class="h-fit w-full rounded-2xl !text-sm lg:w-3/12"
                        :options="feeableTypeOptions"
                        placeholder="Filter by Fee Period"
                    />

                    <!--                    TODO: Add this filter When there is more than one target user type-->
                    <!--                    <SelectInput-->
                    <!--                        v-model="selectedTargetUserType"-->
                    <!--                        class="h-fit w-full rounded-2xl !text-sm lg:w-3/12"-->
                    <!--                        :options="targetUserTypeOptions"-->
                    <!--                        placeholder="Filter by Target User"-->
                    <!--                    />-->
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
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import moment from "moment";
import Modal from "@/Components/Modal.vue";
import AddFee from "@/Views/Admin/Finance/Fees/AddFee.vue";
import { upperCase } from "lodash";
import SelectInput from "@/Components/SelectInput.vue";

const fees = computed(() => usePage().props.fees);

const feeableTypeOptions = computed(() => {
    return [
        {
            label: "All",
            value: resetFilter,
        },
        {
            label: "School Year",
            value: "school_years",
        },
        {
            label: "Semester",
            value: "semesters",
        },
        {
            label: "Quarter",
            value: "quarters",
        },
    ];
});

const targetUserTypeOptions = computed(() => {
    return [
        {
            label: "All",
            value: resetFilter,
        },
        {
            label: "Student",
            value: "student",
        },
        {
            label: "Teacher",
            value: "teacher",
        },
        {
            label: "Admin",
            value: "admin",
        },
        {
            label: "Guardian",
            value: "guardian",
        },
    ];
});

const resetFilter = () => {
    selectedFeeableType.value = null;
    selectedTargetUserType.value = null;
};

const selectedFeeableType = ref(null);
const selectedTargetUserType = ref(null);

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

watch(selectedFeeableType, (value) => {
    if (value) {
        applyFeeableTypeFilter();
    }
});

watch(selectedTargetUserType, (value) => {
    if (value) {
        applyTargetUserTypeFilter();
    }
});

function applyFeeableTypeFilter() {
    router.get(
        "/admin/fees",
        {
            target_user_type: selectedTargetUserType.value,
        },
        {
            preserveState: true,
        }
    );
}

function applyTargetUserTypeFilter() {
    router.get(
        "/admin/fees",
        {
            target_user_type: selectedTargetUserType.value,
        },
        {
            preserveState: true,
        }
    );
}

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
