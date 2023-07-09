<template>
    <TableElement
        :selectable="false"
        :data="filteredStaffAbsentees"
        :columns="config"
        class="!rounded-lg p-5 shadow-sm"
    >
        <template #filter>
            <div class="flex justify-between gap-2">
                <TextInput
                    v-model="query"
                    class="w-full lg:max-w-lg"
                    class-style="focus:ring-1 focus:ring-zinc-700 focus:border-none focus:outline-none rounded-2xl"
                    placeholder="Search for an absent staff by name"
                />
                <SelectInput
                    v-model="selectedUserType"
                    class="h-fit w-3/12 rounded-2xl !text-sm"
                    :options="userTypeOptions"
                    placeholder="Filter by user type"
                />

                <DatePicker
                    v-model="selectedDate"
                    class="h-fit w-2/12 rounded-2xl !text-sm"
                />
                <PrimaryButton class="!rounded-2xl" @click="showModal = true">
                    <span class="flex space-x-1">
                        <SquaresPlusIcon class="w-3 stroke-white stroke-2" />
                        <span class="!text-xs">New Absentee</span>
                    </span>
                </PrimaryButton>
            </div>
        </template>
        <template #empty-data>
            <EmptyView title="No absent staff" />
        </template>
    </TableElement>

    <Modal v-model:view="showModal">
        <AbsenteeAddModal @add="showModal = false" />
    </Modal>
</template>
<script setup>
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import { SquaresPlusIcon } from "@heroicons/vue/24/outline/index";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { debounce } from "lodash";
import AbsenteeAddModal from "@/Views/Admin/Absentees/AbsenteeAddModal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Modal from "@/Components/Modal.vue";
import EmptyView from "@/Views/EmptyView.vue";
import DatePicker from "@/Components/DatePicker.vue";
import moment from "moment";

const showModal = ref(false);
const selectedUserType = ref(usePage().props.filters.user_type);

const staffAbsenteesOfTheDay = computed(
    () => usePage().props.staff_absentees_of_the_day
);

const userTypeOptions = computed(() => {
    return [
        {
            label: "All",
            value: "all",
        },
        {
            label: "Admin",
            value: "admin",
        },
        {
            label: "Teacher",
            value: "teacher",
        },
    ];
});

watch(selectedUserType, () => {
    if (selectedUserType.value) {
        router.get(
            "/admin/absentees",
            {
                type: selectedUserType.value,
            },
            {
                only: ["staff_absentees_of_the_day"],
                preserveState: true,
                replace: true,
            }
        );
    }
});

const selectedDate = ref(null);

let selectedDateString = "";

watch(selectedDate, () => {
    if (selectedDate.value) {
        const isoDate = new Date(selectedDate.value);
        selectedDateString = moment(isoDate).format("YYYY-MM-DD");
        router.get(
            "/admin/absentees",
            {
                date: selectedDateString,
            },
            {
                only: ["staff_absentees_of_the_day"],
                preserveState: true,
                replace: true,
            }
        );
    }
});

const filteredStaffAbsentees = computed(() => {
    return staffAbsenteesOfTheDay.value.data.map((staffAbsentee) => {
        return {
            name: staffAbsentee.user.name,
            email: staffAbsentee.user.email,
            reason: staffAbsentee.reason,
            type: staffAbsentee.type,
        };
    });
});

const config = [
    {
        key: "name",
        name: "Name",
    },
    {
        key: "email",
        name: "Email",
    },
    {
        name: "Reason",
        key: "reason",
    },
    {
        name: "Type",
        key: "type",
    },
];

const query = ref("");

watch(query, () => {
    find();
});

const find = debounce(() => {
    router.get(
        "/admin/absentees",
        {
            find: query.value,
        },
        {
            only: ["staff_absentees_of_the_day"],
            preserveState: true,
            replace: true,
        }
    );
}, 300);
</script>
<style scoped></style>
