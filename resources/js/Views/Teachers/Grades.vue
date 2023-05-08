<template>
    <div class="flex w-full flex-col items-center space-y-1 rounded-md">
        <div class="px-4 text-2xl font-medium">Grades</div>
        <div
            class="flex h-fit w-full items-center justify-evenly rounded-sm border-b border-gray-200 py-1 text-center font-bold"
        >
            <div class="w-4/12">Grade</div>
            <div class="w-8/12">Subjects</div>
        </div>

        <div v-if="grades.length > 0" class="flex w-full flex-col space-y-3">
            <div class="flex w-full flex-col items-center justify-center">
                <div
                    v-for="(item, index) in grades"
                    :key="index"
                    class="my-1 flex h-fit w-full items-center justify-evenly py-3 text-center text-sm"
                    :class="index % 2 === 1 ? 'bg-white' : 'bg-gray-50'"
                >
                    <div class="w-4/12">{{ item.class }}</div>
                    <div class="w-8/12">{{ item.subject }}</div>
                </div>
            </div>

            <div
                class="w-full cursor-pointer text-end text-sm font-light underline decoration-neutral-500 underline-offset-2 hover:font-medium"
                @click="$inertia.get('/teacher/grades')"
            >
                View All Grades
            </div>
        </div>

        <div v-else class="mt-8 flex flex-col items-center space-y-6">
            <ExclamationTriangleIcon class="h-6 w-6 text-gray-500" />
            <div>No Grades Found!</div>
            <PrimaryButton>Go To Grades</PrimaryButton>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";

const searchText = ref("");
const batchSubjects = usePage().props.teacher.batch_subjects;
const homeroom = usePage().props.teacher.homeroom;

const grades = computed(() => {
    const allSubjects = [
        ...homeroom.map((item) => ({
            class: item.batch.level.name + item.batch.section,
            subject: "Homeroom",
        })),
        ...batchSubjects.map((item) => ({
            class: item.batch.level.name + item.batch.section,
            subject: item.subject.full_name,
        })),
    ];

    const mergedClasses = allSubjects.reduce((acc, curr) => {
        const existingClass = acc.find((item) => item.class === curr.class);

        if (existingClass) {
            existingClass.subjects.push(curr.subject);
        } else {
            acc.push({ class: curr.class, subjects: [curr.subject] });
        }

        return acc;
    }, []);

    return mergedClasses.map(({ class: className, subjects }) => {
        const limitedSubjects = subjects.slice(0, 3);

        let subjectText = ``;
        if (limitedSubjects.length > 1) {
            subjectText = limitedSubjects
                .slice(0, -1)
                .concat("and " + limitedSubjects.slice(-1))
                .join(", ");
        } else {
            subjectText = limitedSubjects[0];
        }

        return { class: className, subject: subjectText };
    });
});
</script>

<style scoped></style>
