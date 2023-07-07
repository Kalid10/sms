<template>
    <TableElement :data="filteredStudentAbsentees" :columns="config">
        <template #filter>
            <div class="flex justify-between gap-2">
                <TextInput
                    v-model="query"
                    class="w-full lg:max-w-lg"
                    placeholder="Search for an absent student by name"
                />
            </div>
        </template>
        <template #empty-data>
            <div class="flex flex-col items-center justify-center">
                <ExclamationTriangleIcon
                    class="mb-2 h-6 w-6 text-negative-50"
                />
                <p class="mb-0.5 text-sm font-semibold">{{ $t('common.noDataFound')}} </p>
            </div>
        </template>
    </TableElement>
</template>
<script setup>
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import TableElement from "@/Components/TableElement.vue";
import TextInput from "@/Components/TextInput.vue";
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { debounce } from "lodash";
import {useI18n} from "vue-i18n";
const {t} = useI18n()

const studentAbsentees = computed(() => usePage().props.student_absentees);

const filteredStudentAbsentees = computed(() => {
    return studentAbsentees.value.data.map((studentAbsentee) => {
        return {
            name: studentAbsentee.user.name,
            email: studentAbsentee.user.email,
            reason: studentAbsentee.reason,
            type: studentAbsentee.type,
        };
    });
});

const config = [
    {
        key: "name",
        name: t('common.name'),
    },
    {
        key: "email",
        name: t('common.email'),
    },
    {
        name: "Reason",
        key: t('common.reason'),
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
            students_find: query.value,
        },
        {
            only: ["student_absentees"],
            preserveState: true,
            replace: true,
        }
    );
}, 300);
</script>
<style scoped></style>
