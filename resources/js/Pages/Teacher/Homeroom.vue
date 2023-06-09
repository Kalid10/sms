<template>
    <div class="flex h-screen w-full flex-col space-y-2 bg-gray-50">
        <div class="flex w-full justify-between space-x-6 bg-gray-50">
            <!--        Left Side-->
            <div class="flex w-8/12 flex-col space-y-2 py-5 pl-5">
                <Header
                    title="Homeroom Classes"
                    :select-input-options="homeroomOptions"
                    :selected-input="selectedHomeroom"
                    @change="updateBatchInfo"
                />
                <StudentsTable
                    :show-homeroom-detail="false"
                    @search="updateBatchInfo"
                />
            </div>
            <div class="w-4/12">sdf</div>
        </div>
    </div>
</template>
<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import Header from "@/Views/Teacher/Header.vue";
import StudentsTable from "@/Views/Teacher/StudentsTable.vue";

const homeroomClasses = computed(() => usePage().props.homeroom_classes);
const selectedHomeroom = ref(usePage().props.filters.batch_id);
const students = computed(() => usePage().props.students);

const homeroomOptions = computed(() => {
    return homeroomClasses.value.map((homeroom) => {
        return {
            value: homeroom.batch.id,
            label: homeroom.batch.level.name + " " + homeroom.batch.section,
        };
    });
});

const updateBatchInfo = (batchId, search) => {
    if (batchId !== null) selectedHomeroom.value = batchId;
    router.get(
        "/teacher/homeroom",
        {
            batch_id: selectedHomeroom.value,
            search: search,
        },
        {
            preserveState: true,
        }
    );
};
</script>
<style scoped></style>
