<template>
    <TableElement
        :data="mappedFees"
        :columns="columns"
        :selectable="false"
        header-style="!bg-brand-400 text-white"
        class="h-full w-full !rounded-lg p-4 shadow-sm"
    >
        <template #table-header>
            <div class="flex w-full justify-between py-5">
                <div class="text-xl font-semibold capitalize">
                    Student Tuition's
                </div>
            </div>
        </template>

        <template #filter>
            <div class="flex flex-col justify-between gap-2 lg:flex-row">
                <TextInput
                    v-model="query"
                    class="w-full lg:max-w-lg"
                    class-style="focus:ring-1 focus:ring-zinc-700 focus:border-none focus:outline-none rounded-2xl"
                    placeholder="Search for a student"
                />

                <SelectInput
                    v-model="selectedStatus"
                    class="h-fit w-full rounded-2xl !text-sm lg:w-3/12"
                    :options="statusOptions"
                    placeholder="Filter by Status"
                />
            </div>
        </template>

        <template #student_id-column="{ data }">
            <div
                class="flex cursor-pointer rounded text-xs"
                @click="onRowClick(data)"
            >
                <ArrowTopRightOnSquareIcon
                    class="w-4 stroke-black hover:scale-125"
                />
            </div>
        </template>

        <template #footer>
            <Pagination
                preserve-state
                :links="studentTuitions.links"
                position="center"
            />
        </template>
    </TableElement>

    <Modal v-model:view="showFeeHistoryModal">
        <FeeHistory :student="studentTuitionHistory" />
    </Modal>
</template>
<script setup>
import TableElement from "@/Components/TableElement.vue";
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import moment from "moment";
import TextInput from "@/Components/TextInput.vue";
import { debounce } from "lodash";
import SelectInput from "@/Components/SelectInput.vue";
import { ArrowTopRightOnSquareIcon } from "@heroicons/vue/24/outline";
import FeeHistory from "@/Views/Admin/Finance/FeeHistory.vue";
import Modal from "@/Components/Modal.vue";
import Pagination from "@/Components/Pagination.vue";

const studentTuitions = computed(() => usePage().props.student_tuitions);

const studentTuitionHistory = computed(() => {
    return usePage().props.student_tuition_history;
});

const mappedFees = computed(() => {
    return studentTuitions.value.data.map((studentTuition) => {
        return {
            student_id: studentTuition.student_id,
            student: studentTuition.student.user.name,
            paymentName: studentTuition.fee.name,
            description: studentTuition.fee.description,
            amount: studentTuition.amount,
            status: studentTuition.status,
            paymentProvider: studentTuition.payment_provider?.name,
            dueDate: moment(studentTuition.due_date).format("MMMM DD, YYYY"),
            guardianContact: studentTuition.student.guardian.user.email,
        };
    });
});

const showFeeHistoryModal = ref(false);

const selectedStatus = ref(null);
const selectedStudentId = ref(null);

const onRowClick = (studentId) => {
    selectedStudentId.value = studentId;
    router.get(
        "/admin/fees",
        {
            student_id: selectedStudentId.value,
        },
        {
            only: ["student_tuition_history"],
            preserveState: true,

            onSuccess: () => {
                showFeeHistoryModal.value = true;
            },
        }
    );
};

const statusOptions = computed(() => {
    return [
        {
            label: "All",
            value: resetFilter,
        },
        {
            label: "Paid",
            value: "paid",
        },
        {
            label: "Unpaid",
            value: "unpaid",
        },
    ];
});

const query = ref("");

watch(query, () => {
    find();
});

const find = debounce(() => {
    router.get(
        "/admin/fees",
        {
            find: query.value,
        },
        {
            only: ["student_tuitions"],
            preserveState: true,
            replace: true,
        }
    );
}, 300);

watch(selectedStatus, () => {
    if (selectedStatus.value) {
        applyStatusFilter();
    }
});

function applyStatusFilter() {
    router.get(
        "/admin/fees",
        {
            status: selectedStatus.value,
        },
        {
            only: ["student_tuitions"],
            preserveState: true,
            replace: true,
        }
    );
}

const resetFilter = () => {
    selectedStatus.value = null;
};

const columns = [
    {
        key: "student",
        name: "Student",
    },
    {
        key: "paymentName",
        name: "Payment Name",
    },
    {
        key: "description",
        name: "Description",
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
        key: "paymentProvider",
        name: "Payment Provider",
    },
    {
        key: "dueDate",
        name: "Due Date",
    },
    {
        key: "guardianContact",
        name: "Guardian Contact",
    },
    {
        key: "student_id",
        name: "History",
        type: "custom",
        class: "flex justify-center items-center",
    },
];
</script>
<style scoped></style>
