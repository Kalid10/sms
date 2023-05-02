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

            <template #filter>
                <div class="flex w-full gap-4">
                    <TextInput v-model="searchKey" class="w-full" placeholder="Search for a teacher by name"/>
                </div>
            </template>

            <template #empty-data>
                <div class="flex flex-col items-center justify-center">
                    <ExclamationTriangleIcon class="mb-2 h-6 w-6 text-negative-50"/>
                    <p class="text-sm font-semibold">
                        No data found
                    </p>
                    <div v-if="searchKey.length">
                        <p v-if="searchKey === null" class="text-sm text-gray-500">
                            No teacher has been enrolled
                        </p>
                        <p v-else class="text-center text-sm text-gray-500">
                            Your search query "<span class="font-medium text-black">{{ searchKey }}</span>" did not
                            match
                            <span class="block">any teacher's name</span>
                        </p>
                    </div>
                </div>
            </template>
        </TeacherTableElement>
    </div>
</template>

<script setup>
import {computed, ref, watch} from "vue";
import {router, usePage} from "@inertiajs/vue3";
import TeacherTableElement from "@/Components/TableElement.vue";
import {ExclamationTriangleIcon} from "@heroicons/vue/24/outline/index";
import TextInput from "@/Components/TextInput.vue";
import {debounce} from "lodash";

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

const searchKey = ref('');

const search = debounce(() => {
    router.get(
        "/teachers/",
        {search: searchKey.value},
        {
            only: ["teachers"],
            preserveState: true, replace: true
        }
    );
}, 300);

watch([searchKey], () => {
    search()
})

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
