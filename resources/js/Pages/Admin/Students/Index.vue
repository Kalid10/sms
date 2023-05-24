<template>
    <div class="my-5 w-10/12">
        <div class="flex flex-col items-center justify-center gap-2 ">
            <h1 class="text-2xl font-semibold text-gray-700">Welcome to Students page</h1>
        </div>

        <TabElement :tabs="models">
            <template #students>
                <StudentsTable/>
            </template>
            <template #grades>
                <BatchStudentsTable/>
            </template>
        </TabElement>
    </div>
</template>

<script setup>
import {ref, watch} from "vue";
import {router, usePage} from "@inertiajs/vue3";
import {debounce} from "lodash";
import StudentsTable from "@/Pages/Admin/Students/StudentsTable.vue";
import TabElement from "@/Components/TabElement.vue";
import BatchStudentsTable from "@/Pages/Admin/Students/BatchStudentsTable.vue";

const models = [
    'Students',
    'Grades',
]

const selectedBatchId = ref(usePage().props.selected_batch.id);

const searchKey = ref('');

watch(selectedBatchId, () => {
    router.get(
        "/students",
        {batch_id: selectedBatchId.value},
        {preserveState: true, replace: true}
    );
});

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


</script>

<style scoped>

</style>
