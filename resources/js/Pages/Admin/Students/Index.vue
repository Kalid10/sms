<template>
    <div class="my-5 w-10/12">
        <Title class="pb-8" title="Students" />

        <TabElement :tabs="models">
            <template #students>
                <StudentsTable @search="searchKey = $event" />
            </template>
            <template #grades>
                <BatchStudentsTable />
            </template>
        </TabElement>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { debounce } from "lodash";
import StudentsTable from "@/Pages/Admin/Students/StudentsTable.vue";
import TabElement from "@/Components/TabElement.vue";
import BatchStudentsTable from "@/Pages/Admin/Students/BatchStudentsTable.vue";
import Title from "@/Views/Teacher/Views/Title.vue";

const models = ["Students", "Grades"];

const selectedBatchId = ref(usePage().props.selected_batch.id);

const searchKey = ref("");

watch(selectedBatchId, () => {
    router.get(
        "/admin/students",
        { batch_id: selectedBatchId.value },
        { preserveState: true, replace: true }
    );
});

const search = debounce(() => {
    router.get(
        "/students/",
        { search: searchKey.value },
        {
            only: ["students"],
            preserveState: true,
            replace: true,
        }
    );
}, 300);

watch([searchKey], () => {
    search();
});
</script>

<style scoped></style>
