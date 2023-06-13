<template>
    <div
        v-for="(item, index) in assessments"
        :key="index"
        class="mt-1 flex cursor-pointer items-center justify-between rounded-lg py-1.5 hover:bg-zinc-800 hover:pr-4 hover:text-white lg:mt-2 lg:py-1.5"
        @click="handleClick()"
    >
        <div
            class="flex min-h-full w-2/12 flex-col items-center justify-center space-y-2 font-bold"
        >
            <!--            <AssessmentIcon v-if="view !== 'class'" :item="item" />-->
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
            class="flex justify-center"
            :class="isHomeOrAdminView ? 'w-9/12  lg:w-8/12' : 'w-7/12'"
        >
            <AssessmentDetails :item="item" :view="view" />
        </div>

        <div class="flex w-3/12 items-center justify-center text-center">
            <AssessmentScore :item="item" :view="view" />

            <div
                v-if="view === 'class'"
                class="flex cursor-pointer justify-center"
            >
                <!--                <PencilIcon class="w-3 opacity-50 hover:opacity-100" />-->
            </div>
        </div>
    </div>
</template>

<script setup>
import AssessmentDetails from "@/Views/Teacher/Home/Assessments/Item/AssessmentDetails.vue";
import AssessmentScore from "@/Views/Teacher/Home/Assessments/Item/Score/Index.vue";
import { computed } from "vue";
import { router } from "@inertiajs/vue3";

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

function handleClick() {
    router.visit("/teacher/assessments");
}
</script>
