<template>
    <div v-if="studentsNotes.length > 0" class="flex w-full">
        <TableElement
            :selectable="false"
            :filterable="false"
            :columns="config"
            :data="studentsNotesData"
            :title="$t('common.studentNotes')"
        />
    </div>
    <div v-else class="flex w-full items-center justify-center">
        <EmptyView :title="$t('batchStudentNote.noStudentNotes')" />
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import EmptyView from "@/Views/EmptyView.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
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
        name: t("common.title"),
        key: "title",
        class: "font-semibold",
    },
    {
        name: t("common.description"),
        key: "description",
        class: "text-brand-text-300 text-xs font-semibold",
    },
    {
        name: t("common.student"),
        key: "student",
    },
    {
        name: t("batchStudentNote.Author"),
        key: "author",
    },
    {
        name: t("batchStudentNote.authorType"),
        key: "author_type",
        enum: ["admin", "teacher"],
    },
];
</script>
<style scoped></style>
