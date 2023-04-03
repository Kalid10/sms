<template>
    <div class=" p-3">
        <Heading size="lg">Assign Subject to Section</Heading>
        <div
            class="mb-9 flex w-full cursor-pointer items-center justify-between rounded-lg bg-gray-100 p-4"
            @click="showLevels = !showLevels"
        >
            <h2 :class="[ showLevels ? 'text-gray-500' : 'text-gray-900' ]" class="font-medium">{{  showLevels ? 'Select a Grade' : selectedGrade.level.name }}</h2>
            <ChevronDownIcon
                class="h-5 w-5 text-gray-500">
            </ChevronDownIcon>
        </div>
        <div v-if="showLevels" class="space-y-4">
            <div v-for="(grade, g) in grades" :key="g">
                <div
                    class="flex w-full cursor-pointer items-center justify-between rounded-lg bg-gray-100 p-4"
                    @click="selectGrade(grade)"
                >
                    <h2 class="font-medium text-gray-900">{{ grade.level.name }}</h2>
                    <ChevronDownIcon
                        class="h-5 w-5 text-gray-500"></ChevronDownIcon>
                </div>
            </div>
        </div>

        <!--the tab component -->
        <div class="flex flex-col ">
            <header class="flex gap-3 overflow-x-auto p-3">
                <TertiaryButton
                    class="whitespace-nowrap !border-none"
                    @click="showGroupModal=true"
                >
                    +
                </TertiaryButton>
                <button
                    class="whitespace-nowrap rounded-md p-3 text-sm"
                    :class="selectedSection === 'All' ? 'bg-gray-200 p-3 text-sm':'bg-white'"
                    @click="selectSection('All')"
                >
                    All Sections
                </button>
                <div v-for="(section, index) in sections" :key="index">
                    <button
                        class="whitespace-nowrap rounded-md p-3 text-sm"
                        :class="selectedSection === section ? 'bg-gray-200 p-3 text-sm' : 'bg-white'"
                        @click="selectSection(section)">
                        Section {{ section }}
                    </button>
                </div>
            </header>
            <TableElement
                :key="subjects"
                :data="subjects"
                :columns="configSubjectTable"
                :filterable="false"
                @select="updateSelectedSubjects"
            >

                <template #full_name-column="{ data }">

                    <div class="text-left">
                        {{ data }}
                    </div>

                </template>

                <template #tags-column="{ data }">

                    <div class="flex justify-start gap-1 text-gray-500">

                        <span v-for="(tag, t) in data" :key="t" class="rounded-lg px-1.5 text-sm">

                            {{ toHashTag(tag) }}

                        </span>

                    </div>

                </template>

                <template #short_name-column="{ data }">

                    <div class="text-right text-sm uppercase text-gray-500">
                        {{ data }}
                    </div>

                </template>

                <template #footer>
                    <PrimaryButton title="done" class="float-right"></PrimaryButton>
                </template>
            </TableElement>
        </div>
    </div>

</template>

<script setup>
import {ref, computed, watch, onMounted} from "vue";
import { toHashTag } from "@/utils";
import {
    subjects as fakeSubjects,
    allLevels,
    batches, sectionsOfLevel,
} from "@/fake";
import {ChevronDownIcon} from "@heroicons/vue/24/outline";
import TableElement from "@/Components/TableElement.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Heading from "@/Components/Heading.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";
const configSubjectTable = [
    {
        key: 'full_name',
        type: 'custom',
        name: 'All',
        header: {
            align: 'text-left'
        }
    },
    {
        key: 'tags',
        name: 'Tags',
        type: 'custom',
        header: {
            align: 'text-left'
        }
    },
    {
        name: '',
        key: 'short_name',
        type: 'custom'
    }
]

const selectedSubjectsForBatches = ref([])
function updateSelectedSubjects(items) {

    const selectedBatch = selectedSubjectsForBatches.value.find(batch => batch?.level_id === selectedGrade.value.level_id && batch?.section === selectedSection.value)

    if (selectedBatch !== undefined) {

        if (selectedSection.value === 'All') {

            selectedSubjectsForBatches.value.filter(batch => batch === selectedBatch).forEach(section => {
                section.subjects = items.items.value.map(i => i.id)
            })
            return
        }

        selectedSubjectsForBatches.value.find(batch => batch === selectedBatch).subjects = items.items.value.map(i => i.id)

    } else {

        if (selectedSection.value === 'All') {

            sections.value.forEach(section => {
                selectedSubjectsForBatches.value.push({
                    batch_id: selectedGrade.value.id,
                    level_id: selectedGrade.value.level_id,
                    section: section,
                    subjects: items.items.value.map(i => i.id)
                })
            })
            return
        }

        selectedSubjectsForBatches.value.push({
            batch_id: selectedGrade.value.id,
            level_id: selectedGrade.value.level_id,
            section: selectedSection.value,
            subjects: items.items.value.map(i => i.id)
        })

    }

}

const subjects = computed(() => {
    return [...fakeSubjects].map(subject => {
        return {
            ...subject,
            selected: isSubjectSelected(subject)
        }
    })
})

const showLevels = ref(false)
const showGroupModal = ref(false)
const grades = allLevels(batches);
const selectedGrade = ref(grades[0])
const selectedSection = ref('All')
const sections = computed(() => sectionsOfLevel(batches, selectedGrade.value.level_id).map(batch => batch.section))

// level and section related functions
function selectGrade(grade) {
    showLevels.value = false
    selectedGrade.value = grade
    sections.value = sectionsOfLevel(batches, grade)
}

function selectSection(section) {
    selectedSection.value = section
}

function isSubjectSelected(subject) {

    if (selectedSection.value === 'All') {

        // from the selectedSubjectsForBatches,
        // filter the levels by selectedGrade

        return selectedSubjectsForBatches.value.filter(batch => batch?.level_id === selectedGrade.value.level_id).forEach(level => {
            if (!level?.subjects.includes(subject.id)) {
                return false
            }
        })

        // return selectedSubjectsForBatches.value.find(batch => {
        //
        //     // First, filter by the level
        //     // Second, find all sections of the selectedGrade
        //     // For each of the sections, check their subjects list
        //     // If the given subject if found, return true
        //     // else return false
        //
        //     return batch?.level_id === selectedGrade.value.level_id &&
        //         ((sectionsOfLevel(selectedSubjectsForBatches.value, selectedGrade.value.level_id).forEach(section => {
        //             if (!section.subjects.includes(section.subjects)) {
        //                 return false
        //             }
        //         })) === true)
        //
        // }) !== undefined

    }

    return selectedSubjectsForBatches.value.find(batch => {
        return batch?.level_id === selectedGrade.value.level_id &&
            batch?.section === selectedSection.value &&
            batch?.subjects.includes(subject.id)
    }) !== undefined

}

watch(selectedSubjectsForBatches, () => {

    selectedSubjectsForBatches.value.filter(batch => batch?.level_id === selectedGrade.value.level_id).forEach(level => {
        if (!level?.subjects.includes(2)) {
            return false
        }
    })

}, { deep: true })

</script>
