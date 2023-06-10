<template>
    <div class="justify-betweens my-2 flex flex-row gap-4">
        <label class=" mx-2 flex w-32 items-center text-sm text-gray-800">Select a grade:</label>
        <SelectInput
            v-model="selectedBatchId" :options="batchOptions" placeholder="Select a batch"
            class="w-full cursor-pointer"/>

        <button
            type="button"
            class="rounded text-xs font-light text-blue-600 underline">
            <Link :href="'/admin/levels/' + selectedBatchId">
                Full information on {{
                    selectedBatchLabel
                }} =>
            </Link>
        </button>
    </div>

    <TableElement
        :columns="config"
        :selectable="false"
        :header="false"
        :filterable="false"
        :title="'Students in ' + selectedBatchLabel"
        :data="batchStudentData">

        <template #name-column="{ data }">
            <div class="flex items-start gap-2">
                <span class="font-medium">{{ data }}</span>
            </div>
        </template>

        <template #email-column="{ data }">
            <div class="flex items-center gap-1">
                <div class="h-1.5 w-1.5 rotate-45 bg-emerald-400"/>
                <span class="text-xs">
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
                        No teacher has been enrolled
                    </p>
                    <p v-else class="text-center text-sm text-gray-500">
                        Your search query "<span class="font-medium text-black">{{ searchKey }}</span>" did not
                        match
                        <span class="block">any teacher's name</span>
                    </p>
                </div>
            </div>
        </template>
    </TableElement>
</template>
<script setup>
import TableElement from "@/Components/TableElement.vue";
import SelectInput from "@/Components/SelectInput.vue";
import {computed, ref, watch} from "vue";
import {Link, router, usePage} from "@inertiajs/vue3";
import {debounce} from "lodash";
import {ExclamationTriangleIcon} from "@heroicons/vue/24/outline";

const batchStudents = computed(() => {
    return usePage().props.batch_students;
});

// Map the batch_student data
const batchStudentData = computed(() => {
    return batchStudents.value.map(batch_student => {
        return {
            id: batch_student.student.id,
            name: batch_student.student.user.name,
            email: batch_student.student.user.email,
            gender: batch_student.student.user.gender,
            grade: batch_student.level.name,
            section: batch_student.batch.section,
        }
    })
})

const batchOptions = computed(() => {
    return usePage().props.batches.map(batch => {
        return {
            label: batch.level.name + ' ' + batch.section,
            value: batch.id
        }
    });
});

const selectedBatchLabel = computed(() => {
    return usePage().props.selected_batch.level.name + ' ' + usePage().props.selected_batch.section;
});

const selectedBatchId = ref(usePage().props.selected_batch.id);

const searchKey = ref('');

watch(selectedBatchId, () => {
    router.get(
        "/admin/students",
        {batch_id: selectedBatchId.value},
        {preserveState: true, replace: true}
    );
});

const search = debounce(() => {
    router.get(
        "/admin/students/",
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
        link: '/admin/students/{id}',
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
        options: ['female', 'male']
    },
]
</script>
