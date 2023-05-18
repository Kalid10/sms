<template>
    <div
        class="flex min-h-screen w-full flex-col space-y-4 bg-white p-5 2xl:px-5"
    >
        <div class="flex h-full w-full justify-between">
            <div class="flex w-7/12 flex-col space-y-4">
                <!--        Header-->
                <div
                    class="flex h-fit flex-col items-start justify-between space-y-1 rounded-lg 2xl:space-y-1.5"
                >
                    <span class="text-xl font-semibold lg:text-4xl">
                        My Students</span
                    >
                    <span class="text-xs font-light">{{
                        moment().format(" dddd MMMM D YYYY")
                    }}</span>
                </div>

                <div class="w-full shadow-sm">
                    <!--        Table-->
                    <TableElement
                        :data="filteredStudents"
                        :title="studentListTitle"
                        :selectable="false"
                        :columns="config"
                        class="border-none"
                        :footer="false"
                        header-style="!bg-black text-white"
                    >
                        <template #filter>
                            <TextInput
                                v-model="searchText"
                                placeholder="Search"
                                class="w-4/12"
                            />
                        </template>
                    </TableElement>
                </div>
            </div>

            <div class="flex h-3/5 w-4/12 flex-col justify-evenly">
                <PerformanceHighlight />
            </div>
        </div>
    </div>
</template>

<script setup>
import moment from "moment/moment";
import PerformanceHighlight from "@/Views/Teacher/Students/PerformanceHighlights/Index.vue";
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import TableElement from "@/Components/TableElement.vue";
import TextInput from "@/Components/TextInput.vue";
import debounce from "lodash/debounce";

const students = computed(() => {
    return usePage().props.students;
});
const batchSubject = usePage().props.batch_subject;
const searchText = ref(usePage().props.search);

const filteredStudents = computed(() => {
    return students.value.map((student) => {
        return {
            name: student.user.name,
            attendance: student.attendance_percentage + "%",
            grade: "50/60",
            rank: student.id,
            id: student.id,
        };
    });
});

const studentListTitle = computed(() => {
    return `${batchSubject.batch.level.name} ${batchSubject.batch.section} - ${batchSubject.subject.full_name} Students List`;
});

const config = [
    {
        key: "name",
        name: "Name",
        align: "center",
        class: "h-12",
        link: "/teacher/students/{id}",
    },
    {
        key: "attendance",
        name: "Attendance%",
        align: "center",
    },
    {
        key: "grade",
        name: "Grade",
        align: "center",
    },
    {
        key: "rank",
        name: "Rank",
        align: "center",
        class: "font-medium",
    },
];

const updateStudents = () => {
    router.visit(
        "/teacher/students?batch_subject_id=" +
            batchSubject.id +
            "&search=" +
            searchText.value,
        {
            only: ["students", "batch_subject"],
            preserveState: true,
        }
    );
};

const debouncedSearch = debounce(updateStudents, 300);

watch(searchText, () => {
    debouncedSearch();
});
</script>

<style scoped></style>
