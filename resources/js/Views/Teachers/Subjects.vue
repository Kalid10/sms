<template>
    <div>
        <Heading size="lg">Subjects</Heading>
        <div v-if="uniqueSubjectsAndLevels.length > 0" class="flex w-full flex-wrap items-center space-x-1.5">
            <div
                v-for="(item, index) in uniqueSubjectsAndLevels" :key="index"
                class="m-0.5 mt-3 w-full lg:w-1/4">
                <Card :title="item.subject" :subtitle="item.levels" class="min-w-full"/>
            </div>

        </div>
        <div v-else>
            <div class="flex h-24 items-center justify-center">
                No subject attached yet!
            </div>
        </div>
    </div>
</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import Card from "@/Components/Card.vue";
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";

const teacher = usePage().props.teacher;
const uniqueSubjectsAndLevels = computed(() => {
    const subjectsMap = new Map();

    teacher.batch_subjects.forEach(subject => {
        const batchSubject = subject;
        const subjectId = batchSubject.subject.id;
        const subjectName = batchSubject.subject.full_name;
        const level = batchSubject.batch.level.name;

        if (subjectsMap.has(subjectId)) {
            subjectsMap.get(subjectId).levels.add(level);
        } else {
            subjectsMap.set(subjectId, {
                subject: subjectName,
                levels: new Set([level]),
            });
        }
    });

    return Array.from(subjectsMap.values()).map(subjectAndLevels => {
        const sortedLevels = Array.from(subjectAndLevels.levels)
            .sort((a, b) => a - b);

        const levelsString = sortedLevels.length > 1
            ? sortedLevels.slice(0, -1).join(', ') + ' and ' + sortedLevels.slice(-1)
            : sortedLevels.join(', ');

        return {
            ...subjectAndLevels,
            levels: 'Grade ' + levelsString,
        };
    });
});
</script>

<style scoped>

</style>
