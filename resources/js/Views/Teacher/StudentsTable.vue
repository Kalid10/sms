<template>
    <div class="flex w-full justify-between">
        <!--        Table-->
        <TableElement
            :data="filteredStudents"
            :title="title"
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

                    <div v-if="showHomeroomDetail">
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
            <template #name-column="{ data }">
                <span
                    class="cursor-pointer text-xs underline underline-offset-2 hover:font-semibold"
                    @click="$emit('click', data.id)"
                >
                    {{ data.name }}
                </span>
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
import { usePage } from "@inertiajs/vue3";
import debounce from "lodash/debounce";

const emit = defineEmits(["click", "search"]);
const props = defineProps({
    title: {
        type: String,
        default: "Students List",
    },
    showHomeroomDetail: {
        type: Boolean,
        default: true,
    },
});
const searchText = ref(usePage().props.filters?.search);
const students = computed(() => {
    return usePage().props.students;
});

const filteredStudents = computed(() => {
    return students.value.data.map((item) => {
        return {
            name: {
                name: item.student.user.name,
                id: item.student.id,
            },
            attendance: item.attendance_percentage + "%",
            grade: item.quarterly_grade
                ? item.quarterly_grade.score.toFixed(1)
                : "-",
            rank: item.rank ?? " -",
            id: item.student.id,
            conduct: item.conduct ?? "-",
        };
    });
});

const config = [
    {
        key: "name",
        name: "Name",
        align: "center",
        class: "h-12 !text-[0.6rem]",
        type: "custom",
    },
    {
        key: "attendance",
        name: "Attendance%",
        align: "center",
        class: "h-12 !text-[0.65rem]",
    },
    {
        key: "grade",
        name: "Grade",
        align: "center",
        class: "h-12 !text-[0.65rem]",
    },
    {
        key: "rank",
        name: "Rank",
        align: "center",
        class: "h-12 !text-[0.65rem]",
    },
    {
        key: "conduct",
        name: "Conduct",
        align: "center",
        class: "h-12 !text-[0.65rem]",
    },
];

function emitSearch() {
    emit("search", null, searchText.value);
}

const debouncedUpdate = debounce(emitSearch, 300);

watch(searchText, () => {
    debouncedUpdate();
});
</script>

<style scoped></style>
