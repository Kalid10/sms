<template>
    <TableElement
        :selectable="false"
        :data="filteredStaffAbsentees"
        :columns="config"
    >
        <template #filter>
            <div class="flex justify-between gap-2">
                <TextInput
                    v-model="query"
                    class="w-full lg:max-w-lg"
                    placeholder="Search for an absent staff by name"
                />
                <PrimaryButton @click="showModal = true">
                    <span class="flex gap-2">
                        <PlusIcon class="h-4 w-4 stroke-white stroke-2" />
                        <span>New Absentee</span>
                    </span>
                </PrimaryButton>
            </div>
        </template>
        <template #empty-data>
            <div class="flex flex-col items-center justify-center">
                <ExclamationTriangleIcon
                    class="mb-2 h-6 w-6 text-negative-50"
                />
                <p class="mb-0.5 text-sm font-semibold">No data found</p>
            </div>
        </template>
    </TableElement>

    <AbsenteeAddModal v-if="showModal" />
</template>
<script setup>
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import {
    ExclamationTriangleIcon,
    PlusIcon,
} from "@heroicons/vue/24/outline/index";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { debounce } from "lodash";
import AbsenteeAddModal from "@/Views/Admin/Absentees/AbsenteeAddModal.vue";

const showModal = ref(false);

const staffAbsenteesOfTheDay = computed(
    () => usePage().props.staff_absentees_of_the_day
);

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
