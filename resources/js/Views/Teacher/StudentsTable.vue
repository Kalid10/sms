<template>
    <div class="flex w-full justify-between">
        <!--        Table-->
        <TableElement
            :data="filteredStudents"
            :title="studentListTitle"
            :selectable="false"
            :columns="config"
            class="!!text-[0.5rem] border-none bg-red-400"
            :footer="true"
            header-style="!bg-zinc-700 text-white !text-[0.65rem]"
        >
            <template #filter>
                <div class="flex h-full w-full justify-between text-center">
                    <TextInput
                        v-model="searchText"
                        placeholder="Search"
                        class="w-5/12"
                    />

                    <div>
                        <div class="mb-1 text-[0.55rem] font-light">
                            Homeroom Teacher
                        </div>
                        <div
                            class="cursor-pointer text-xs font-semibold underline-offset-2 hover:underline"
                        >
                            Mr.Bereket Gobeze
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <Pagination
                    :preserve-state="true"
                    :links="students.links"
                    position="center"
                />
            </template>
        </TableElement>
    </div>
</template>
<script setup>
import Pagination from "@/Components/Pagination.vue";
import TextInput from "@/Components/TextInput.vue";
import TableElement from "@/Components/TableElement.vue";
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import debounce from "lodash/debounce";

const searchText = ref(usePage().props.filters?.search);
const students = computed(() => {
    return usePage().props.students;
});

const assessments = computed(() => {
    return usePage().props.assessments;
});
const batchSubject = computed(() => {
    return usePage().props.batch_subject;
});
const filteredStudents = computed(() => {
    return students.value.data.map((item) => {
        return {
            name: item.student.user.name,
            attendance: item.attendance_percentage + "%",
            grade: item.student.quarterly_grade
                ? item.student.quarterly_grade.score.toFixed(1)
                : "-",
            rank: item.student.batch_subject_rank ?? "-",
            id: item.student.id,
            conduct: item.student.conduct ?? "-",
        };
    });
});

const studentListTitle = computed(() => {
    return `${batchSubject.value.batch.level.name} ${batchSubject.value.batch.section} - ${batchSubject.value.subject.full_name} Students List`;
});

const config = [
    {
        key: "name",
        name: "Name",
        align: "center",
        class: "h-12  !text-[0.6rem]",
        link:
            "/teacher/students/{id}" +
            "?batch_subject_id=" +
            batchSubject.value.id,
    },
    {
        key: "attendance",
        name: "Attendance%",
        align: "center",
        class: "h-12  !text-[0.65rem]",
    },
    {
        key: "grade",
        name: "Grade",
        align: "center",
        class: "h-12  !text-[0.65rem]",
    },
    {
        key: "rank",
        name: "Rank",
        align: "center",
        class: "h-12  !text-[0.65rem]",
    },
    {
        key: "conduct",
        name: "Conduct",
        align: "center",
        class: "h-12  !text-[0.65rem]",
    },
];

const updateBatchInfo = () => {
    router.visit(
        "/teacher/class?batch_subject_id=" +
            batchSubject.value.id +
            "&search=" +
            searchText.value,
        {
            preserveState: true,
        }
    );
};

const debouncedUpdate = debounce(updateBatchInfo, 300);

watch(searchText, () => {
    debouncedUpdate();
});
</script>

<style scoped></style>
