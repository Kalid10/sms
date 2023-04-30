<template>

    <SubjectsTable @new="isAddModalOpen = true" @archive="archiveConfirmation" @update="selectSubject" />

    <RegisterSubjectForm v-model:open="isAddModalOpen" />

    <SubjectUpdate
        v-model:open="isUpdateModalOpen"
        :subject="selectedSubject"
    />

    <DialogBox v-model:open="isDialogBoxOpen" type="archive" @confirm="archiveSubject" >
        <template #description>
            You are about to archive this subject.
            Are you sure you want to continue?
            Do not worry, all the batches and students under this subject will be available,
            and you can restore this subject anytime.
        </template>
    </DialogBox>

</template>

<script setup>
import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import SubjectUpdate from "@/Views/SubjectUpdate.vue";
import RegisterSubjectForm from "@/Views/RegisterSubjectForm.vue";
import DialogBox from "@/Components/DialogBox.vue";
import SubjectsTable from "@/Views/Subjects/SubjectsTable.vue";

const isAddModalOpen = ref(false);
const isUpdateModalOpen = ref(false);

const selectedSubjectId = ref(null);
const isDialogBoxOpen = ref(false);
function archiveConfirmation(id) {
    selectedSubjectId.value = id;
    isDialogBoxOpen.value = !isDialogBoxOpen.value;
}

function archiveSubject() {
    router.delete("/subjects/delete/" + selectedSubjectId.value, {
        onFinish: () => {
            isDialogBoxOpen.value = false;
            selectedSubjectId.value = null;
        },
    })
}

const selectedSubject = ref(null);
function selectSubject(subject) {
    selectedSubject.value = subject;
    isUpdateModalOpen.value = true
    if (!subject) {
        return (selectedSubject.value = null);
    }
}

</script>

<style scoped>
button {
    outline: none;
}
</style>
