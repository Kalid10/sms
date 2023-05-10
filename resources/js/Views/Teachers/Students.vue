<template>
    <div class="flex max-w-full flex-col space-y-1 rounded-md">
        <div class="font-medium lg:px-4 lg:text-2xl">Students</div>
        <div class="flex h-fit flex-col justify-evenly space-y-2 rounded-sm">
            <div
                v-if="students.length > 0"
                class="flex h-fit w-full items-center justify-evenly rounded-sm bg-black py-3 text-center text-xs font-light text-white lg:text-sm lg:font-semibold"
            >
                <div class="w-4/12">Name</div>
                <div class="w-2/12">Score</div>
                <div class="w-2.5/12">Attendance %</div>
                <div class="w-2.5/12">Conduct</div>
            </div>
            <div
                class="flex w-full justify-between space-x-4 text-black"
                :class="students.length < 1 ? 'pb-3' : ''"
            >
                <TextInput
                    v-model="searchText"
                    placeholder="Search Student"
                    class="w-6/12 rounded-lg text-xs lg:text-sm"
                />

                <select-input
                    v-model="selectedBatchSubject"
                    class="w-6/12 text-xs"
                    :options="subjectGradeOptions"
                    placeholder=""
                />
            </div>
        </div>
        <div
            v-if="students.length > 0"
            class="flex flex-col items-center justify-center divide-y-2"
        >
            <div
                v-for="(item, index) in students"
                :key="index"
                class="flex h-14 w-full items-center justify-evenly rounded-md text-center text-xs lg:text-sm"
            >
                <div
                    class="w-6/12 cursor-pointer font-medium hover:font-semibold hover:underline hover:underline-offset-2"
                    @click="$inertia.get(`/teacher/students/${item.id}`)"
                >
                    {{ item.user.name }}
                </div>
                <!--                TODO:Add the calculated score-->
                <div class="w-3/12">60</div>
                <div class="w-2/12">{{ item.attendance_percentage }}%</div>
                <div class="w-2/12">C</div>
            </div>
        </div>
        <div
            v-else
            class="flex h-full w-full flex-col items-center justify-center rounded-md pt-2 text-center"
        >
            <ExclamationTriangleIcon class="h-6 w-6 text-gray-500" />
            <div class="mb-5 mt-2 w-full font-light">No Students Found!</div>
            <PrimaryButton @click="$inertia.get('/teacher/students')">
                View All Students
            </PrimaryButton>
        </div>
        <div
            v-if="students.length > 0"
            class="w-full cursor-pointer pr-3 text-end text-xs font-light underline decoration-neutral-500 underline-offset-2 hover:font-medium lg:text-sm"
            @click="$inertia.get('/teacher/students')"
        >
            View All Students
        </div>
    </div>
</template>

<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import SelectInput from "@/Components/SelectInput.vue";
import debounce from "lodash/debounce";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";

const teacherSubjects = usePage().props.teacher.batch_subjects;

const rawStudentsData = computed(() => {
    return usePage().props.students;
});

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
    router.visit(
        "/teacher?batch_subject_id=" +
            selectedBatchSubject.value +
            "&search=" +
            searchText.value,
        {
            only: ["students"],
            preserveState: true,
        }
    );
};

const debouncedSearch = debounce(updateStudents, 300);

watch(searchText, () => {
    debouncedSearch();
});
watch(selectedBatchSubject, () => {
    updateStudents();
});
</script>

<style scoped></style>
