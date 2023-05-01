<template>

    <div class="flex flex-col gap-3">

        <div class="flex w-full flex-col">
            <div class="flex items-center gap-1.5">
                <div :class="subjectPriorityLabels[subject['priority'] - 1]" class="h-2.5 w-2.5 rounded-full" />
                <h3 class="font-semibold">{{ subject['full_name'] }}</h3>
            </div>
            <div class="flex flex-col items-baseline gap-1">
                <h3 class="text-sm text-gray-500">{{ subject['category'] }}</h3>
                <h3 class="text-xs text-gray-500">{{ subject['tags'].map(tag => toHashTag(tag)).join('&nbsp;&nbsp;') }}</h3>
            </div>
        </div>

    </div>

    <TabElement :tabs="subjectTabs">

        <template #teachers>

            <SubjectTeachers />

        </template>

        <template #grades>

            <SubjectGrades />

        </template>

    </TabElement>

</template>

<script setup>
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import TabElement from "@/Components/TabElement.vue";
import {subjectPriorityLabels, toHashTag} from "@/utils.js";
import SubjectTeachers from "@/Views/Subjects/SubjectTeachers.vue";
import SubjectGrades from "@/Views/Subjects/SubjectGrades.vue";

const subject = computed(() => usePage().props.subject)

const subjectTabs = [
    'Grades',
    'Teachers'
]

</script>

<style scoped>

</style>
