<template>
    <div v-if="studentsNotes.length > 0" class="flex w-full">
        <TableElement
            :selectable="false"
            :filterable="false"
            :columns="config"
            :data="studentsNotesData"
            title="Student Notes"
        />
    </div>
    <div v-else class="flex w-full items-center justify-center">
        <EmptyView title="No Student Notes Found" />
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import EmptyView from "@/Views/EmptyView.vue";

const studentsNotes = computed(() => usePage().props.students_notes);

const studentsNotesData = computed(() => {
    return studentsNotes.value.map((studentNote) => {
        return {
            title: studentNote.title,
            description: studentNote.description,
            student: studentNote.student.user.name,
            author: studentNote.author.name,
            author_type: studentNote.author.type,
        };
    });
});

const config = [
    {
        name: "Title",
        key: "title",
        class: "font-semibold",
    },
    {
        name: "Description",
        key: "description",
        class: "text-gray-500 text-xs font-semibold",
    },
    {
        name: "Student",
        key: "student",
    },
    {
        name: "Author",
        key: "author",
    },
    {
        name: "Author Type",
        key: "author_type",
        enum: ["admin", "teacher"],
    },
];
</script>
<style scoped></style>
