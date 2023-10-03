<template>
    <div
        class="flex h-screen w-full flex-col space-y-8 rounded-lg bg-white p-5 shadow-sm"
    >
        <div class="flex w-full items-center">
            <div class="cursor-pointer">
                <ArrowLeftCircleIcon class="w-6" @click="sectionUrl" />
            </div>

            <div
                class="ml-3 flex w-full items-center justify-center rounded-lg px-3 py-1"
            >
                <Title :title="batch.level.name + ' Assessments'" />
            </div>
        </div>
        <div v-if="levelAssessments?.data.length > 0" class="flex w-full">
            <div class="w-7/12">
                <div
                    class="flex flex-col items-center justify-center space-y-2 px-12"
                >
                    <div
                        v-for="(item, index) in levelAssessments.data"
                        :key="index"
                        class="w-full cursor-pointer border-b"
                        @click="selectAssessment(item)"
                    >
                        <AssessmentItem :assessment="item" />
                    </div>
                </div>

                <Pagination
                    class="py-3"
                    position="center"
                    :links="levelAssessments.links"
                />
            </div>
            <div class="w-5/12">
                <div v-if="selectedAssessment" class="border-l">
                    <Details :assessment="selectedAssessment" />
                </div>
                <div v-else>
                    <div
                        class="flex flex-col items-center justify-center space-y-2 px-12"
                    >
                        <span class="text-2xl font-medium">
                            Select an assessment to view details
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <EmptyView
            v-else
            :title="$t('assessmentIndex.noAssessmentsFound')"
            class="flex w-full justify-center py-2"
        />
    </div>
</template>
<script setup>
import { computed, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import AssessmentItem from "@/Views/Admin/Assessments/AssessmentItem.vue";
import { ArrowLeftCircleIcon } from "@heroicons/vue/20/solid";
import EmptyView from "@/Views/EmptyView.vue";
import Pagination from "@/Components/Pagination.vue";
import Details from "@/Views/Admin/Assessments/Details.vue";
import Title from "@/Views/Teacher/Views/Title.vue";

const selectedAssessment = ref(null);

const batch = computed(() => usePage().props.batch);
const levelAssessments = computed(() => usePage().props.level_assessments);

const sectionUrl = () => {
    router.get("/admin/levels/" + batch.value.level.id);
};

const selectAssessment = (assessment) => {
    selectedAssessment.value = assessment;
};
</script>
<style scoped></style>
