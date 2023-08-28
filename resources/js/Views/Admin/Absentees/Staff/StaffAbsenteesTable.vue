<template>
    <TableElement
        :selectable="false"
        :data="filteredStaffAbsentees"
        :columns="config"
        class="min-h-screen !rounded-lg shadow-sm lg:p-5"
    >
        <template #filter>
            <div class="flex flex-col justify-between gap-2 lg:flex-row">
                <TextInput
                    v-model="query"
                    class="w-full lg:max-w-lg"
                    class-style="focus:ring-1 focus:ring-zinc-700 focus:border-none focus:outline-none rounded-2xl"
                    :placeholder="$t('staffAbsenteesTable.searchStaff')"
                />
                <SelectInput
                    v-model="selectedUserType"
                    class="h-fit w-full rounded-2xl !text-sm lg:w-3/12"
                    :options="userTypeOptions"
                    :placeholder="$t('staffAbsenteesTable.userType')"
                />

                <DatePicker
                    v-model="selectedDate"
                    class="h-full w-full rounded-2xl !text-sm lg:w-2/12"
                />
                <div class="flex justify-end">
                    <PrimaryButton
                        class="!rounded-2xl bg-brand-450"
                        @click="showModal = true"
                    >
                        <span class="flex space-x-1">
                            <SquaresPlusIcon
                                class="w-3 stroke-white stroke-2"
                            />
                            <span class="!text-xs">{{
                                $t("staffAbsenteesTable.newAbsentee")
                            }}</span>
                        </span>
                    </PrimaryButton>
                </div>
            </div>
        </template>

        <template #empty-data>
            <EmptyView :title="$t('staffAbsenteesTable.noAbsentStaff')" />
        </template>

        <template #row-column="{ data }">
            <PencilSquareIcon
                class="w-5 cursor-pointer hover:scale-125"
                @click="
                    showUpdateModal = true;
                    selectedAbsentee = data;
                "
            />
        </template>
    </TableElement>

    <Modal v-model:view="showModal">
        <AbsenteeAddModal @add="showModal = false" />
    </Modal>

    <Modal v-model:view="showUpdateModal">
        <UpdateAbsenteeForm
            :absentee="selectedAbsentee"
            @update="showUpdateModal = false"
        />
    </Modal>
</template>
<script setup>
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import {
    PencilSquareIcon,
    SquaresPlusIcon,
} from "@heroicons/vue/24/outline/index";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { debounce } from "lodash";
import AbsenteeAddModal from "@/Views/Admin/Absentees/AbsenteeAddModal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Modal from "@/Components/Modal.vue";
import EmptyView from "@/Views/EmptyView.vue";
import DatePicker from "@/Components/DatePicker.vue";
import moment from "moment";

import { useI18n } from "vue-i18n";
import UpdateAbsenteeForm from "@/Views/Admin/Absentee/UpdateAbsenteeForm.vue";

const { t } = useI18n();
const showModal = ref(false);
const selectedUserType = ref(usePage().props.filters.user_type);

const showUpdateModal = ref(false);
const selectedAbsentee = ref(null);

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
            leave: staffAbsentee.is_leave,
            row: staffAbsentee,
        };
    });
});

const config = [
    {
        key: "name",
        name: t("common.name"),
    },
    {
        key: "email",
        name: t("common.email"),
    },
    {
        key: "reason",
        name: t("common.reason"),
    },
    {
        key: "type",
        name: t("common.type"),
    },
    {
        name: "Leave",
        key: t("common.leave"),
        type: Boolean,
    },
    {
        name: "",
        key: "row",
        type: "custom",
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
