<template>

    <div class="flex flex-col gap-2 md:grid md:grid-cols-2 md:grid-rows-2 lg:flex lg:flex-row">
        <StatisticsCard :data="level['batches_count'].toString()" :subtitle="`Sections in ${activeSchoolYear}`" title="Sections">
            <template #icon>
                <UserCircleIcon/>
            </template>
        </StatisticsCard>
        <StatisticsCard :data="studentsCount.toString()" :subtitle="`Students in ${parseLevel(level.name)} for ${activeSchoolYear.split(' ').slice(2)}`" title="Students">
            <template #icon>
                <UserIcon/>
            </template>
        </StatisticsCard>
        <StatisticsCard :data="teachers.size.toString()" :subtitle="`Teachers in ${parseLevel(level.name)} for ${activeSchoolYear.split(' ').slice(2)}`" title="Teachers">
            <template #icon>
                <UsersIcon/>
            </template>
        </StatisticsCard>
        <StatisticsCard :data="subjects.size.toString()" :subtitle="`Subjects given for ${parseLevel(level.name)} students`" title="Subjects">
            <template #icon>
                <UserPlusIcon/>
            </template>
        </StatisticsCard>
    </div>

</template>

<script setup>
import StatisticsCard from "@/Views/StatisticsCard.vue"
import {UserIcon, UsersIcon, UserPlusIcon, UserCircleIcon} from "@heroicons/vue/24/outline"
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import {parseLevel} from "@/utils.js";

const level = computed(() => usePage().props.level)
const activeSchoolYear = computed(() => usePage().props.level.batches[0]?.school_year.name)
const studentsCount = computed(() => usePage().props.level.batches.reduce((acc, batch) => {
    return acc + batch['students_count']
}, 0))
const teachers = computed(() => usePage().props.level.batches.reduce((acc, batch) => {
    batch.subjects.map(subject => subject.teacher_id).forEach(acc.add, acc)
    return acc
}, new Set))
const subjects = computed(() => usePage().props.level.batches.reduce((acc, batch) => {
    batch.subjects.map(subject => subject.subject_id).forEach(acc.add, acc)
    return acc
}, new Set))
</script>

<style scoped>

</style>
