<template>
    <div>
        <TeacherTableElement
            :columns="config"
            :selectable="false"
            title="Teachers" subtitle="List of all teachers in the school system"
            :data="formattedTeachersData">
            <template #subjects-column="{data}">
                <div class="mx-auto max-w-sm overflow-hidden truncate lg:max-w-lg 2xl:max-w-3xl">
                    {{ data }}
                </div>
            </template>
        </TeacherTableElement>
    </div>
</template>

<script setup>
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import TeacherTableElement from "@/Components/TableElement.vue";

const teachers = computed(() => {
    return usePage().props.teachers.data;
});

const formattedTeachersData = computed(() => {
    return teachers.value.map(teacher => {
        // Use Set to get distinct values
        const subjectsSet = new Set();
        teacher.batch_subjects.forEach(bs => subjectsSet.add(`${bs.subject.full_name}(${bs.batch.level.name})`));
        const subjects = Array.from(subjectsSet).join(', ');

        return {
            name: teacher.user.name,
            email: teacher.user.email,
            homerooms: teacher.homeroom.map(hr => `${hr.batch.level.name}${hr.batch.section}`).join(', '),
            subjects: subjects,
        };
    });
});

const config = [
    {
        name: 'Name',
        key: 'name',
        main: true
    },
    {
        name: 'Email',
        key: 'email',
    },
    {
        name: 'Homerooms',
        key: 'homerooms',
    },
    {
        name: 'Subjects',
        key: 'subjects',
        type: 'custom',
    },
]
</script>

<style scoped>

</style>
