<template>
    <div class="flex h-full cursor-pointer flex-col">
        <div
            v-if="!isLessonPlansEmpty"
            class="text-center font-medium 2xl:text-2xl"
        >
            {{ title }}
        </div>
        <div class="flex flex-col items-center justify-center">
            <div
                v-if="!isLessonPlansEmpty"
                class="flex w-full flex-col space-y-2 py-4"
            >
                <div
                    class="mb-1 flex w-full flex-col items-center divide-y divide-gray-50"
                >
                    <Items
                        v-for="(item, index) in lessonPlans"
                        :key="index"
                        :item="item"
                        :view="view"
                        :class="view === 'class' ? 'mb-3' : 'mb-2'"
                        class="min-w-full"
                    />
                </div>
                <LinkCell
                    class="flex w-full justify-end"
                    href="/teacher/lesson-plan"
                    :value="$t('lessonPlans.viewAllLessonPlans')"
                />
            </div>
            <div
                v-else
                class="flex h-full flex-col items-center justify-center space-y-6"
            >
                <ExclamationTriangleIcon class="h-6 w-6 text-gray-500" />
                <div> {{ $t('lessonPlans.noLessonPlan')}} </div>
                <LinkCell
                    href="/teacher/lesson-plan"
                    :value="$t('lessonPlans.viewAllLessonPlans')"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import { computed, watch } from "vue";
import Items from "@/Views/Teacher/Views/Home/LessonPlan/Index.vue";
import LinkCell from "@/Components/LinkCell.vue";

const props = defineProps({
    propsLessonPlans: {
        type: Array,
        default: null,
    },
    title: {
        type: String,
        default: "Recent Lesson Plans",
    },
    view: {
        type: String,
        default: "teacher",
    },
});
let lessonPlans =
    props.propsLessonPlans ?? usePage().props.teacher.lesson_plans;

const isLessonPlansEmpty = computed(() => {
    return !lessonPlans?.length;
});
watch(
    () => props.propsLessonPlans,
    (newLessonPlans) => {
        lessonPlans = newLessonPlans;
    }
);
</script>

<style scoped></style>
