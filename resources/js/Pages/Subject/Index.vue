<template>
    <subject-table-element
        :columns="config" :data="subjects" :selectable=false actionable
        subtitle="list of all subjects" title="Subject">
        <template #all-actions>
            <div class="flex flex-row space-x-4">
                <subject-text-input
                    v-model="searchKey" class="w-1/2" placeholder="Search for a subject"
                    @keyup="search"/>
                <subject-primary-button :click="toggleAddModal" class="w-1/2" title="Add"/>
            </div>
        </template>
    </subject-table-element>

    <subject-add v-if="isAddModalOpen" :toggle="isAddModalOpen"/>
    <subject-update v-if="isUpdateModalOpen" :subjects="subjects" :toggle="isUpdateModalOpen"/>
</template>
<script setup>
import {computed, ref} from "vue";
import SubjectAdd from "@/Pages/Subject/Add.vue";
import {router, usePage} from "@inertiajs/vue3";
import {debounce} from "lodash";
import SubjectTableElement from "@/Components/TableElement.vue";
import SubjectTextInput from "@/Components/TextInput.vue";
import SubjectPrimaryButton from "@/Components/PrimaryButton.vue";
import SubjectUpdate from "@/Pages/Subject/Update.vue";

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
            "/subject",
            {search: searchKey.value},
            {preserveState: true, replace: true},
        );
    },
    300);

// Remove Subject
const removeSubject = (id) => {
    Inertia.delete(`/subject/${id}`);
}

const config = [
    {
        name: 'ID',
        key: 'id',
    },
    {
        name: 'Full Name',
        key: 'full_name',
    },
    {
        name: 'Short Name',
        key: 'short_name',
    },
    {
        name: 'Update',
        key: 'update',
        link: `subject/update/ ${subjects.value.id || 'id'}`,
    },
    {
        name: 'Delete',
        key: 'delete',
        link: `subject/delete/ ${subjects.value.id || 'id'}`,
    },
]
</script>
