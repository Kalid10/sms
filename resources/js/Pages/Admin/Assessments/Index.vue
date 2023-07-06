<template>
    <div class="flex min-h-screen w-full justify-center bg-gray-50">
        <div class="flex w-11/12 flex-col space-y-6">
            <Title class="w-5/12" title="Assessments" />

            <div
                class="flex h-full w-full flex-col space-x-5 lg:flex-row lg:justify-between"
            >
                <div
                    class="flex h-fit w-1/2 flex-col space-y-4 rounded-lg bg-white p-5 shadow-sm"
                >
                    <div class="text-2xl font-medium">Recent Assessments</div>
                    <div
                        class="flex w-full flex-col items-center justify-between space-y-2 text-white"
                    >
                        <div
                            class="flex w-full flex-col justify-center space-y-2 py-2"
                        >
                            <Filter />
                        </div>
                        <div class="flex w-full justify-start"></div>
                    </div>

                    <div
                        class="flex flex-col items-center justify-center space-y-2"
                    >
                        <div
                            v-for="(item, index) in assessments.data"
                            :key="index"
                            class="w-full"
                        >
                            <AssessmentItem :assessment="item" />
                        </div>
                    </div>

                    <Pagination
                        class="py-3"
                        position="center"
                        :links="assessments.links"
                    />
                </div>

                <div
                    class="flex h-fit w-full flex-col space-y-4 rounded-lg lg:w-5/12"
                >
                    <AssessmentTypes />
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import Pagination from "@/Components/Pagination.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import AssessmentItem from "@/Views/Admin/Assessments/AssessmentItem.vue";
import Filter from "@/Views/Admin/Assessments/Filter.vue";
import AssessmentTypes from "@/Views/Admin/Assessments/AssessmentTypes/Table.vue";

const assessments = computed(() => usePage().props.assessments);
</script>
<style scoped></style>
