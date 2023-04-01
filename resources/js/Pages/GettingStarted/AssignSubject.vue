<template>
    <div class=" p-3">
        <Heading size="lg">Assign Subject to Section</Heading>
        <div
            class="mb-9 flex w-full cursor-pointer items-center justify-between rounded-lg bg-gray-100 p-4"
            @click="showLevels = !showLevels"
        >
            <h2 :class="[ showLevels ? 'text-gray-500' : 'text-gray-900' ]" class="font-medium">{{  showLevels ? 'Select a Grade' : selectedGrade }}</h2>
            <ChevronDownIcon
                class="h-5 w-5 text-gray-500">
            </ChevronDownIcon>
        </div>
        <div v-if="showLevels" class="space-y-4">
            <div v-for="grade in grades" :key="grade.id">
                <div
                    class="flex w-full cursor-pointer items-center justify-between rounded-lg bg-gray-100 p-4"
                    @click="activeGrade(grade)"
                >
                    <h2 class="font-medium text-gray-900">{{grade}}</h2>
                    <ChevronDownIcon
                        class="h-5 w-5 text-gray-500"></ChevronDownIcon>
                </div>
            </div>
        </div>

        <!--the tab component -->
        <div class="pt-5 text-gray-700">
            <div class=" text-gray-700">
                <div class=" pt-4 shadow">
                    <div class="flex flex-col gap-3">
                        <header class="flex gap-3 overflow-x-auto px-3">
                            <button
                                class="whitespace-nowrap rounded-md bg-gray-200 p-3 text-sm ">
                                all
                            </button>
                            <div v-for="(section, index) in sections" :key="index">
                                <button
                                    class="whitespace-nowrap "
                                    :class="section.isActive ?'rounded-md bg-gray-200 p-3 text-sm':'bg-white bg-gray-200 p-3 text-sm' "
                                    @click="activeSection(index)">
                                    {{ section }}
                                </button>
                            </div>
                            <PrimaryButton
                                class="whitespace-nowrap"
                                @click="showGroupModal=true"
                            >
                                group
                            </PrimaryButton>
                        </header>
                        <p>{{}}</p>
                        <div class="rounded-md ">
                            <div class="m-5 md:m-1">
                                <TableElement
                                    :data="newSubjects"
                                    :columns="configSubjectTable"
                                    :filterable="false"
                                    class="p-5">
                                    <template #footer >
                                        <PrimaryButton title="done" class="float-right"></PrimaryButton>
                                    </template>
                                </TableElement>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                </div>
    <Modal v-model:view="showGroupModal" view="view" title="Group section " class="max-w-md">
        <FormElement title="Group Sections" @submit="addSectionGroup()" >
            <p class="flex justify-center">Group multiple section to make subject assigning easy</p>
                <TextInput v-model="groupedSection.name" required label="Group name" name="" placeholder="name"></TextInput>
            <p>Sections:</p>
<!--            group of section -->
            <p>{{ selectedSections }}</p>

            <div class="grid grid-cols-2 gap-2 ">
                <div v-for="(section,index) in sections" :key="index">
                    <div>
                        <p class="">{{section}}</p>
                        <Toggle v-model="selectedSections[section]"  :label="section"></Toggle>
                    </div>
                </div>
            </div>
        </FormElement>
    </Modal>

</template>

<script setup>
import {ref,computed} from "vue";
import {
    subjects as fakeSubjects,
    allGrades,
    batches, sectionsOfLevel,
} from "@/fake";
import {ChevronDownIcon} from "@heroicons/vue/24/outline";
import TableElement from "@/Components/TableElement.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import Heading from "@/Components/Heading.vue";
import {useForm} from "@inertiajs/vue3";
import Toggle from "@/Components/Toggle.vue";
const showDetail = ref(false)
const configSubjectTable = [
    {
    name: 'Id',
    key: 'id',
    sortable: true,
    searchable: true,
},
    {
        name: 'Name',
        key: 'name',
        sortable: true,
        searchable: true,
    },
    {
        name: 'Short Name',
        key: 'short_name',
        sortable: true,
        searchable: true,
    },
]
const subjects = ref([...fakeSubjects])
const newSubjects = computed(() => {
    return subjects.value.map(subject => {
        return {
            ...subject,
            selected: Math.ceil(Math.random()*10) % 2
        }
    })
})

const showLevels = ref(false)
const showGroupModal = ref(false)
const grades = allGrades(batches);
const selectedGrade = ref(grades[0])
const sections = ref(sectionsOfLevel(batches,selectedGrade.value))


// level and section related functions
function activeGrade(grade){
    showLevels.value = false
    selectedGrade.value = grade
    sections.value = sectionsOfLevel(batches,grade)
}
function activeSection(index) {
    alert("section " + index);
}
const selectedSections = ref({})
const groupedSection = useForm(
    {
        name:'',
        sections:selectedSections.value
    }
)
function addSectionGroup() {
    alert(groupedSection.sections);
    console.log(groupedSection.sections)
    showGroupModal.value = false


}



</script>
