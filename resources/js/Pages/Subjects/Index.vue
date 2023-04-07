<template>
    <div class="w-full">
        <div class="flex space-x-4">
            <button
                :class="activeTab === 'tab1' ? activeButtonClass : inactiveButtonClass"
                @click="activeTab = 'tab1'"
            >
                Active Subjects
            </button>
            <button
                :class="activeTab === 'tab2' ? activeButtonClass : inactiveButtonClass"
                @click="activeTab = 'tab2'"
            >
                Deleted Subjects
            </button>
        </div>
        <div class="p-4">
            <div v-if="activeTab === 'tab1'">
                <SubjectTableElement
                    :columns="config"
                    :data="subjects"
                    :selectable="false"
                    actionable
                    row-actionable
                    subtitle="list of all subjects"
                    title="Subject"
                >
                    <template #tags-column="data">
                        <div class="flex items-center justify-center gap-2">
                            <p v-for="(tag, t) in data.data" :key="t" class="text-xs font-medium text-gray-500">
                                {{ toHashTag(tag) }}
                            </p>
                        </div>
                    </template>
                    <template #filter>
                        <SubjectTextInput
                            v-model="searchKey"
                            :selectable="false"
                            actionable
                            placeholder="Search for subject"
                            @keyup="search"
                        />
                    </template>
                    <template #action>
                        <SubjectPrimaryButton
                            title="Add Subject"
                            @click="toggleAddModal"
                        />
                    </template>
                    <template #row-actions="{ row }">
                        <button @click="selectSubject(row)">
                            <PencilSquareIcon class="h-4 w-4"/>
                        </button>
                        <button @click="toggleDialogBox(row.id)">
                            <TrashIcon class="h-4 w-4"/>
                        </button>
                    </template>
                </SubjectTableElement>
            </div>
            <div v-if="activeTab === 'tab2'">
                <SubjectTableElement
                    :data="deletedSubjects"
                    :selectable="false"
                    actionable
                    row-actionable
                    subtitle="list of deleted subjects"
                    title="Deleted Subjects">
                    <template #filter>
                        <SubjectTextInput
                            v-model="searchKey"
                            :selectable="false"
                            actionable
                            placeholder="Search for deleted subject"
                            @keyup="search"
                        />
                    </template>
                    <template #row-actions="{ row }">
                        <SubjectPrimaryButton @click="restoreSubject(row.id)">
                            <p>Restore</p>
                        </SubjectPrimaryButton>
                    </template>
                </SubjectTableElement>
            </div>
        </div>
    </div>

    <RegisterSubjectForm v-if="isAddModalOpen" :toggle="isAddModalOpen"/>

    <DialogBox v-if="isDialogBoxOpen" @confirm="removeSubject"/>

    <SubjectUpdate
        v-if="isUpdateModalOpen"
        :subject="selectedSubject"
        :toggle="isUpdateModalOpen"
    />
</template>
<script setup>
import {computed, ref} from "vue";
import {router, usePage} from "@inertiajs/vue3";
import {debounce} from "lodash";
import {toHashTag} from "@/utils"
import {PencilSquareIcon, TrashIcon} from "@heroicons/vue/24/outline";
import SubjectTableElement from "@/Components/TableElement.vue";
import SubjectUpdate from "@/Views/SubjectUpdate.vue";
import SubjectPrimaryButton from "@/Components/PrimaryButton.vue";
import SubjectTextInput from "@/Components/TextInput.vue";
import RegisterSubjectForm from "@/Views/RegisterSubjectForm.vue";
import DialogBox from "@/Components/DialogBox.vue";

const isAddModalOpen = ref(false);
const isDialogBoxOpen = ref(false);

const selectedSubjectId = ref(null);

function toggleDialogBox(id) {
    isDialogBoxOpen.value = !isDialogBoxOpen.value;
    selectedSubjectId.value = id;
}


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

const deletedSubjects = computed(() => {
    return usePage().props.deletedSubjects.data;
});

const search = debounce(() => {
    router.get(
        "/subjects",
        {search: searchKey.value},
        {preserveState: true, replace: true}
    );
}, 300);

// Remove Subject
const removeSubject = () => {
    router.delete("/subjects/delete/" + selectedSubjectId.value, {
        onFinish: () => {
            isDialogBoxOpen.value = false;
            selectedSubjectId.value = null;
        },
    })
};

const selectedSubject = ref();


function selectSubject(subject) {
    selectedSubject.value = subject;
    toggleUpdateModal();
    if (!subject) {
        return (selectedSubject.value = null);
    }
}

// Restore deleted subject
const restoreSubject = (id) => {
    router.get("/subjects/restore/" + id);
};


const config = [
    {
        name: 'Full Name',
        key: 'full_name',
    },
    {
        name: 'Short Name',
        key: 'short_name',
    },
    {
        name: 'Category',
        key: 'category',
    },
    {
        name: 'Tags',
        key: 'tags',
        type: 'custom'
    },
]

const activeButtonClass = "bg-blue-500 text-white font-bold py-2 px-4 rounded"
const inactiveButtonClass = "bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
const activeTab = ref("tab1")
</script>

<style scoped>
button {
    outline: none;
}
</style>
