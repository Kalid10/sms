<template>
    <div class="w-full py-2 lg:w-10/12 lg:py-5">
        <Title class="pb-8" :title="$t('common.subjects')" />

        <SubjectsTab
            @new="isAddModalOpen = true"
            @archive="archiveConfirmation"
            @update="selectSubject"
        />

        <RegisterSubjectForm v-model:open="isAddModalOpen" />

        <SubjectUpdate
            v-model:open="isUpdateModalOpen"
            :subject="selectedSubject"
        />
        <DialogBox
            v-model:open="isDialogBoxOpen"
            type="delete"
            @confirm="archiveSubject"
        >
            <template #description>
                {{ $t("subjectsIndex.message") }}
            </template>
        </DialogBox>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import SubjectUpdate from "@/Views/Admin/SubjectUpdate.vue";
import RegisterSubjectForm from "@/Views/Admin/RegisterSubjectForm.vue";
import DialogBox from "@/Components/DialogBox.vue";
import SubjectsTab from "@/Views/Admin/Subjects/SubjectsTab.vue";
import Title from "@/Views/Teacher/Views/Title.vue";

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
    });
}

const selectedSubject = ref(null);

function selectSubject(subject) {
    selectedSubject.value = subject;
    isUpdateModalOpen.value = true;
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
