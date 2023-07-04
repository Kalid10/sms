<template>
    <TableElement
        class="flex w-full"
        :selectable="false"
        :filterable="false"
        :columns="config"
        :data="studentsData"
    >
        <template #date_of_birth-column="{ data }">
            {{ Math.abs(moment(data).diff(new Date(), "years")) }}
        </template>

        <template #empty-data>
            <div class="flex flex-col items-center justify-center">
                <ExclamationTriangleIcon
                    class="mb-2 h-6 w-6 text-negative-50"
                />
                <p class="text-sm font-semibold">No data found</p>
            </div>
        </template>

        <template #footer>
            <Pagination
                :links="students.links"
                position="center"
                preserve-state
            />
        </template>
    </TableElement>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import Pagination from "@/Components/Pagination.vue";
import moment from "moment";

const students = computed(() => usePage().props.students);

const studentsData = computed(() => {
    return students.value.data.map((student) => {
        return {
            name: student.user.name,
            username: student.user.username,
            gender: student.user.gender,
            date_of_birth: student.user.date_of_birth,
        };
    });
});

const config = [
    {
        name: "",
        key: "name",
        class: "font-semibold",
        align: "left",
    },
    {
        name: "",
        key: "username",
        class: "text-gray-500 text-xs font-semibold",
        align: "left",
    },
    {
        name: "",
        key: "gender",
        type: "enum",
        options: ["male", "female"],
        align: "left",
    },
    {
        name: "Age",
        key: "date_of_birth",
        type: "custom",
        align: "left",
    },
];
</script>
<style scoped></style>
