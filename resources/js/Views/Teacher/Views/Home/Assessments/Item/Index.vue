<template>
    <div
        v-for="(item, index) in assessments.data"
        :key="index"
        class="mt-1 flex cursor-pointer items-center justify-between space-y-2 rounded-lg py-1.5 px-4 hover:bg-brand-450 hover:pr-4 hover:text-white lg:mt-2 lg:py-1.5"
        :class="index % 2 === 0 ? 'bg-brand-50' : ''"
        @click="handleClick(item.id)"
    >
        <div class="flex min-h-full w-2/12 flex-col space-y-2 font-bold">
            <div
                class="flex h-9 w-9 items-center justify-center rounded-full text-xl uppercase"
                :class="{
                    'bg-blue-400 text-white': item.status === 'marking',
                    'bg-yellow-400 text-black': item.status === 'completed',
                    'bg-emerald-400 text-white': item.status === 'published',
                    'bg-neutral-700 text-white': item.status === 'scheduled',
                }"
            >
                {{ item.status.slice(0, 1).toUpperCase() }}
            </div>
            <div class="text-[0.65rem] font-light uppercase">
                {{ item.batch_subject.batch.level.name }}
                {{ item.batch_subject.batch.section }}
            </div>
        </div>

        <div
            class="flex h-full justify-center"
            :class="isHomeOrAdminView ? 'w-9/12  lg:w-9/12' : 'w-7/12'"
        >
            <AssessmentDetails :item="item" :view="view" />
        </div>

        <div class="flex w-3/12 items-center justify-center text-center">
            <AssessmentScore class="" :item="item" :view="view" />
        </div>
    </div>
    <Pagination class="pt-4" :links="assessments.links" position="center" />
</template>

<script setup>
import AssessmentDetails from "@/Views/Teacher/Views/Home/Assessments/Item/AssessmentDetails.vue";
import AssessmentScore from "@/Views/Teacher/Views/Home/Assessments/Item/Score/Index.vue";
import { computed } from "vue";
import { router } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    assessments: {
        type: Object,
        required: true,
    },
    view: {
        type: String,
        default: "home",
    },
});

const isHomeOrAdminView = computed(() => props.view !== "class");

function handleClick(id) {
    router.visit("/teacher/assessments/mark/" + id);
}
</script>
