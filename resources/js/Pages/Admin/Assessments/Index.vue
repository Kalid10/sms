<template>
    <div class="flex min-h-screen w-11/12 flex-col space-y-6">
        <Title class="w-5/12" title="Assessments" />

        <div
            class="flex w-full flex-col space-x-5 lg:flex-row lg:justify-between"
        >
            <div class="h-fit w-full lg:w-6/12">
                <div
                    class="h-fit w-full rounded-lg bg-white px-5 py-3 shadow-sm"
                >
                    <div
                        class="flex w-full flex-col items-center justify-between gap-2"
                    >
                        <div
                            class="flex w-full flex-col justify-center space-y-2 py-2"
                        >
                            <Filter />
                        </div>
                        <div class="flex w-full justify-start">
                            <TextInput
                                v-model="query"
                                class="w-4/5"
                                placeholder="Search for assessment"
                            />
                        </div>
                    </div>

                    <div
                        class="mt-3 flex flex-col justify-center divide-y divide-gray-100"
                    >
                        <div
                            v-for="(item, index) in assessments.data"
                            :key="index"
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
            </div>

            <div
                class="flex h-fit w-full flex-col space-y-4 rounded-lg border py-2 lg:w-6/12"
            >
                <AssessmentTypes />
            </div>
        </div>
    </div>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import TextInput from "@/Components/TextInput.vue";
import Pagination from "@/Components/Pagination.vue";
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import AssessmentItem from "@/Views/Admin/Assessments/AssessmentItem.vue";
import Filter from "@/Views/Admin/Assessments/Filter.vue";
import AssessmentTypes from "@/Views/Admin/Assessments/AssessmentTypes/Table.vue";

const query = ref(usePage().props.filters.search);

const assessments = computed(() => usePage().props.assessments);

function search() {
    router.get(
        "/admin/assessments/",
        {
            search: query.value,
        },
        {
            preserveState: true,
        }
    );
}

const key = ref(0);
watch([query], () => {
    search();
});
</script>
<style scoped></style>
