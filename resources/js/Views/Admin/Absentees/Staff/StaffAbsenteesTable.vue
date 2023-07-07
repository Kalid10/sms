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
                <SelectInput
                    v-model="selectedType"
                    class="h-fit w-2/12 rounded-2xl !text-sm"
                    :options="typeOptions"
                    placeholder="Filter by user type"
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
import SelectInput from "@/Components/SelectInput.vue";

const showModal = ref(false);
const selectedType = ref(usePage().props.filters.type);
const staffAbsenteesOfTheDay = computed(
    () => usePage().props.staff_absentees_of_the_day
);

const userTypes = computed(() => usePage().props.user_types);

const typeOptions = computed(() => {
    return [
        {
            label: "All",
            value: "all",
        },
        ...userTypes.value.map((type) => {
            return {
                label: type.charAt(0).toUpperCase() + type.slice(1),
                value: type.toLowerCase(),
            };
        }),
    ];
});

watch(selectedType, () => {
    if (selectedType.value) {
        router.get(
            "/admin/absentees",
            {
                type: selectedType.value,
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
