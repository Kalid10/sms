<template>
    <TableElement
        :selectable="false"
        :data="mapStudentTuitionHistory"
        :columns="columns"
        :filterable="false"
    >
        <template #table-header>
            <div class="flex w-full justify-between p-5">
                <div class="text-xl font-semibold capitalize">
                    Fee History of
                    {{ props.student[0].student.user.name }}
                </div>
            </div>
        </template>
    </TableElement>
</template>

<script setup>
import { computed } from "vue";
import TableElement from "@/Components/TableElement.vue";
import moment from "moment";

const props = defineProps({
    student: {
        type: Object,
        required: true,
    },
});

const mapStudentTuitionHistory = computed(() => {
    return props.student.map((s) => {
        return {
            payment: s.fee.name,
            amount: s.amount,
            status: s.status,
            dueDate: moment(s.due_date).format("MMMM DD, YYYY"),
        };
    });
});

const columns = [
    {
        key: "payment",
        name: "Payment",
    },
    {
        key: "amount",
        name: "Amount",
    },
    {
        key: "status",
        name: "Status",
    },
    {
        key: "dueDate",
        name: "Due Date",
    },
];
</script>

<style scoped></style>
