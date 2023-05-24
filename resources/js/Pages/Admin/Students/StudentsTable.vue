<template>

    <TableElement
        :columns="config"
        :selectable="false"
        :header="false"
        title="Students"
        subtitle="List of all students in the school system"
        :data="formattedStudentsData">

        <template #filter>
            <div class="flex w-full gap-4">
                <TextInput v-model="searchKey" class="w-full" placeholder="Search for a student by name"/>
            </div>
        </template>


        <template #name-column="{ data }">
            <div class="flex items-start gap-2">
                <span class="font-medium">{{ data }}</span>
            </div>
        </template>

        <template #email-column="{ data }">
            <div class="flex items-center gap-1">
                <span class="text-xs">
                {{ data }}
            </span>
            </div>
        </template>

        <template #grade-column="{ data }">
            <div class="flex items-center gap-1">
                    <span class="text-xs font-semibold">
                {{ data }}
            </span>
            </div>
        </template>

        <template #empty-data>
            <div class="flex flex-col items-center justify-center">
                <ExclamationTriangleIcon class="mb-2 h-6 w-6 text-negative-50"/>
                <p class="text-sm font-semibold">
                    No data found
                </p>
                <div v-if="searchKey.length">
                    <p v-if="searchKey === null" class="text-sm text-gray-500">
                        No student has been enrolled
                    </p>
                    <p v-else class="text-center text-sm text-gray-500">
                        Your search query "<span class="font-medium text-black">{{ searchKey }}</span>" did not
                        match
                        <span class="block">any student's name</span>
                    </p>
                </div>
            </div>
        </template>
    </TableElement>

</template>
<script setup>
import TableElement from "@/Components/TableElement.vue";
import {computed, ref, watch} from "vue";
import {router, usePage} from "@inertiajs/vue3";
import {debounce} from "lodash";
import TextInput from "@/Components/TextInput.vue";
import {ExclamationTriangleIcon} from "@heroicons/vue/24/outline";

const students = computed(() => {
    return usePage().props.students.data;
});

const formattedStudentsData = computed(() => {
    return students.value.map(student => {
        return {
            id: student.id,
            name: student.user.name,
            email: student.user.email,
            gender: student.user.gender,
            batch_id: student.batches[0].id,
            grade: student.batches.map(ba => `${ba.level.name}-${ba.batch.section}`).join(', ')
        };
    });
});

const searchKey = ref('');

const search = debounce(() => {
    router.get(
        "/students/",
        {search: searchKey.value},
        {
            only: ["students"],
            preserveState: true, replace: true
        }
    );
}, 300);

watch([searchKey], () => {
    search();
})

const config = [
    {
        name: 'Name',
        key: 'name',
        link: '/students/{id}',
        align: 'left',
        type: 'custom',
    },
    {
        name: 'Email',
        key: 'email',
        type: 'custom',
    },
    {
        name: 'Gender',
        key: 'gender',
        type: 'enum',
        options: ['female', 'male'],
    },
    {
        name: 'Grade',
        key: 'grade',
        type: 'custom',
        align: 'right',

    },
]


</script>
