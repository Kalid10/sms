<template>
    <div class="mx-3 my-5 w-full lg:mx-0 lg:w-10/12">
        <Title class="pb-8" :title="$t('studentsIndex.students')" />

        <TabElement v-model:active="activeTab" :tabs="tabs">
            <template #[studentsTab]>
                <StudentsTable
                    :show-title="false"
                    @search="searchKey = $event"
                />
            </template>
            <template #[gradesTab]>
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
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const tabs = [t("studentsIndex.students"), t("studentsIndex.grades")];
const studentsTab = t("studentsIndex.students");
const gradesTab = t("studentsIndex.grades");

const activeTab = ref(studentsTab);

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
