<template>
    <div
        v-for="(item, index) in assessments"
        :key="index"
        class="mt-1 flex items-center justify-between py-1.5 lg:mt-2 lg:py-1.5"
    >
        <div class="flex w-2/12 justify-center">
            <AssessmentIcon :item="item" />
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
                <PencilIcon class="w-4 opacity-50 hover:opacity-100" />
            </div>
        </div>
    </div>
</template>

<script setup>
// ...
import AssessmentIcon from "@/Views/Teacher/Home/Assessments/Item/AssessmentIcon.vue";
import AssessmentDetails from "@/Views/Teacher/Home/Assessments/Item/AssessmentDetails.vue";
import AssessmentScore from "@/Views/Teacher/Home/Assessments/Item/Score/Index.vue";
import { PencilIcon } from "@heroicons/vue/24/solid";
import { computed } from "vue";

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
</script>
