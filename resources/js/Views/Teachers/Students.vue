<template>
    <div class="mt-16 flex max-w-full flex-col space-y-2 rounded-md">
        <div class="flex h-28 flex-col justify-between rounded-t-md bg-black px-4 pt-3 pb-3.5 text-white">
            <div class="text-2xl font-medium">Students</div>
            <div class="flex w-full justify-between space-x-6 text-black">
                <TextInput
                    v-model="searchText" placeholder="Search Student"
                    class="w-6/12 rounded-lg text-sm"/>

                <select-input
                    v-model="selectedBatchSubject" class="w-6/12" :options="subjectGradeOptions"
                    placeholder=""/>
            </div>
        </div>
        <div v-if="students.length > 0" class="flex flex-col items-center justify-center divide-y-2">
            <div
                class="flex h-10 w-full items-center justify-evenly rounded-md text-center text-sm">
                <div
                    class="w-6/12">
                    Name
                </div>
                <div class="w-2/12">Score</div>
                <div class="w-2/12">Attendance</div>
                <div class="w-2/12">Conduct</div>
            </div>

            <div
                v-for="(item, index) in students"
                :key="index"
                class="flex h-14 w-full items-center justify-evenly rounded-md text-center text-sm">
                <div
                    class="w-6/12 cursor-pointer font-medium hover:font-semibold hover:underline hover:underline-offset-2"
                    @click="$inertia.get('/teacher/student/2')">
                    {{ item.user.name }}
                </div>
                <div class="w-2/12  font-bold">60</div>
                <div class="w-2/12">11/12</div>
                <div class="w-2/12">C</div>
            </div>
        </div>
        <div v-else class="flex h-full w-full flex-col items-center justify-center rounded-md text-center">
            <div class="mb-5 w-full font-light">No Students Found!</div>
            <PrimaryButton @click="$inertia.get('/teacher/students')"> View All Students</PrimaryButton>
        </div>
        <div
            v-if="students.length > 0"
            class="w-full cursor-pointer pr-3 text-end text-sm font-light underline decoration-neutral-500 underline-offset-2 hover:font-medium"
            @click="$inertia.get('/teacher/students')">
            View All Students
        </div>
    </div>
</template>


<script setup>
import {router, usePage} from "@inertiajs/vue3";
import {computed, ref, watch} from "vue";
import SelectInput from "@/Components/SelectInput.vue";
import debounce from "lodash/debounce";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const teacherSubjects = usePage().props.teacher.batch_subjects;

const rawStudentsData = computed(() => {
        return usePage().props.students;
    }
);

const students = computed(() => {
    return rawStudentsData.value.flatMap(
        (batchSubject) => batchSubject.students
    );
});

const selectedBatchSubject = ref(rawStudentsData.value[0].batch_subject_id);

const searchText = ref(rawStudentsData.value[0].search);

const subjectGradeOptions = computed(() => {
    return teacherSubjects.map((subject) => {
        return {
            value: subject.id,
            label:
                subject.subject.full_name +
                " " +
                subject.batch.level.name +
                subject.batch.section,
        };
    });
});

const updateStudents = () => {
    router.visit("/teacher?batch_subject_id=" + selectedBatchSubject.value + "&search=" + searchText.value, {
        only: ["students"],
        preserveState: true,
    });
};

const debouncedSearch = debounce(updateStudents, 300);

watch(searchText, () => {
    debouncedSearch();
});

watch(selectedBatchSubject, () => {
    updateStudents();
});
</script>


<style scoped>
</style>
