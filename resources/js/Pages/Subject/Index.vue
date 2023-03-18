<template>
    <SubjectTableElement
        :data="subjects"
        :selectable="false"
        actionable
        row-actionable
        subtitle="list of all subjects"
        title="Subject"
    >
        <template #action>
            <div class="flex flex-row space-x-4">
                <SubjectTextInput
                    v-model="searchKey"
                    :selectable="false"
                    actionable
                    data="subjects"
                    placeholder="Search for subject"
                    subtitle="list of all subjects"
                    title="Subject"
                    @keyup="search"
                />

                <SubjectPrimaryButton
                    title="Add Subject"
                    @click="toggleAddModal"
                />
            </div>
        </template>
        <template #row-actions="{ row }">
            <SubjectPrimaryButton @click="selectSubject(row)">
                <PencilSquareIcon class="h-4 w-4" />
            </SubjectPrimaryButton>
            <SubjectPrimaryButton @click="removeSubject(row.id)">
                <TrashIcon class="h-4 w-4" />
            </SubjectPrimaryButton>
        </template>
    </SubjectTableElement>

    <SubjectAdd v-if="isAddModalOpen" :toggle="isAddModalOpen" />

    <SubjectUpdate
        v-if="isUpdateModalOpen"
        :subject="selectedSubject"
        :toggle="isUpdateModalOpen"
    />
</template>
<script setup>
import { computed, ref } from "vue";
import SubjectAdd from "@/Pages/Subject/Add.vue";
import { router, usePage } from "@inertiajs/vue3";
import { debounce } from "lodash";
import { PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/outline";
import SubjectTableElement from "@/Components/TableElement.vue";
import SubjectUpdate from "@/Pages/Subject/Update.vue";
import SubjectPrimaryButton from "@/Components/PrimaryButton.vue";
import SubjectTextInput from "@/Components/TextInput.vue";

const isAddModalOpen = ref(false);

function toggleAddModal() {
    isAddModalOpen.value = !isAddModalOpen.value;
}

const isUpdateModalOpen = ref(false);

function toggleUpdateModal() {
    isUpdateModalOpen.value = !isUpdateModalOpen.value;
}

const subjects = computed(() => {
    return usePage().props.subjects.data;
});

const searchKey = ref(usePage().props.searchKey);

const search = debounce(() => {
    router.get(
        "/subjects",
        { search: searchKey.value },
        { preserveState: true, replace: true }
    );
}, 300);

// Remove Subject
const removeSubject = (id) => {
    router.delete("/subjects/delete/" + id);
};

const selectedSubject = ref();

function selectSubject(subject) {
    selectedSubject.value = subject;
    toggleUpdateModal();
    if (!subject) {
        return (selectedSubject.value = null);
        toggleUpdateModal();
    }
}
</script>
