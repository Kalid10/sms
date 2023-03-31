<template>

    <div class="flex flex-col">
        <Heading>Register Subjects</Heading>
        <h3 class="text-sm text-gray-500">
            We have selected a set predefined subjects for you. You can remove the subjects you don't provide or
            add more subjects not listed here.
            <span class="inline-block">Click on the "Finish" to proceed, and "Next" to save and proceed.</span>
        </h3>
    </div>

    <div class="flex gap-5">
        <span class="col-span-1 text-sm text-gray-500 sm:col-span-2 md:col-span-3 lg:col-span-3 xl:col-span-4">
            <span class="font-semibold text-black">{{ updatedSubjects.length }}</span> Total Subjects
        </span>
        <span class="col-span-1 text-sm text-gray-500 sm:col-span-2 md:col-span-3 lg:col-span-3 xl:col-span-4">
            <span class="font-semibold text-black">{{ selectedSubjects.length }}</span> Subjects Selected
        </span>
        <span v-if="newSubjects.length > 0" class="col-span-1 text-sm text-gray-500 sm:col-span-2 md:col-span-3 lg:col-span-3 xl:col-span-4">
            <span class="font-semibold text-black">{{ newSubjects.length }}</span> New Subject{{ newSubjects.length > 1 ? 's' : '' }}
        </span>
    </div>

    <div class="flex flex-col gap-4">

        <div v-for="(category, c) in categories" :key="c" class="flex flex-col gap-2">
            <div class="flex items-center gap-2">
                <div class="z-10 h-3.5 w-3.5 rounded-full" :class="colors[c]" />
                <Heading size="sm" class="font-normal text-gray-500">{{ category }}</Heading>
            </div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4">

                <label v-for="(subject, s) in updatedSubjects.filter(sub => sub.category === category)" :key="s">
                    <Card class="group !min-w-full">

                        <div :class="subject.selected || subject.isNew ? 'opacity-100' : 'opacity-25'" class="flex flex-col gap-6">

                            <div class="flex items-center justify-between">

                                <h3 class="font-semibold">
                                    {{ subject.name }}
                                </h3>

                                <Checkbox
                                    v-if="!!!subject.isNew"
                                    v-model="subject.selected"
                                    class="h-5 w-5 !rounded-full border-none bg-transparent checked:ring-0 focus:ring-0"
                                />
                                <TrashIcon v-if="subject.isNew" class="h-5 w-5 cursor-pointer stroke-black stroke-2" @click="removeSubject(subject)"></TrashIcon>

                            </div>

                            <div class="flex items-baseline gap-1">

                            </div>
                        </div>

                    </Card>
                </label>

            </div>
        </div>

        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2">
                <div class="z-10 h-3.5 w-3.5 rounded-full bg-gray-300" />
                <Heading size="sm" class="font-normal text-gray-500">Other</Heading>
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4">
                <button class="flex min-h-[82px] flex-col items-center justify-center gap-2 rounded-md border-2 border-dashed bg-white/75" @click="openNewSubjectForm">

                    <PlusCircleIcon class="h-5 w-5 stroke-gray-500 stroke-2"/>
                    <span class="text-sm font-semibold text-gray-500">Create new Subject</span>

                </button>
                <button class="flex min-h-[82px] flex-col items-center justify-center gap-2 rounded-md border-2 border-dashed bg-white/75" @click="openNewSubjectForm">

                    <span class="flex">
                        <span class="z-0 h-3.5 w-3.5 translate-x-1/3 rounded-full bg-blue-400" />
                        <span class="z-10 h-3.5 w-3.5 rounded-full bg-yellow-400" />
                        <span class="relative z-20 grid h-3.5 w-3.5 -translate-x-1/3 place-items-center rounded-full border-2 border-gray-500 bg-white leading-none">
                            <PlusIcon class="h-2 w-2 stroke-gray-500 stroke-[5]" />
                        </span>
                    </span>
                    <span class="text-sm font-semibold text-gray-500">Create new Category</span>

                </button>
            </div>
        </div>

    </div>

    <div class="flex items-center gap-3">

        <PrimaryButton @click="submitSubjects">Finish</PrimaryButton>

    </div>

    <Modal v-model:view="isNewSubjectFormOpened">

        <FormElement title="New Subject" subtitle="Create a new subject and assign it to a category" @submit="addToSubjectsList">

            <TextInput v-model="newSubject.name" required placeholder="Name of the new Subject" label="Subject Name"/>
            <SelectInput v-model="newSubject.category" :options="categoryOptions" required placeholder="Category of the new Subject" label="Subject Category"/>

        </FormElement>

    </Modal>

</template>

<script setup>
import { ref, computed } from "vue"
import {gradesFromBatch, batches, subjects as fakeSubjects, sectionsOfLevel} from "@/fake"
import { PlusCircleIcon, PlusIcon, TrashIcon } from "@heroicons/vue/24/outline"
import Heading from "@/Components/Heading.vue"
import Card from "@/Components/Card.vue"
import Checkbox from "@/Components/Checkbox.vue"
import Modal from "@/Components/Modal.vue"
import FormElement from "@/Components/FormElement.vue"
import PrimaryButton from "@/Components/PrimaryButton.vue"
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";

const subjects = computed(() => fakeSubjects)
const categories = computed(() => {
    return subjects.value.reduce((acc, subject) => {
        if (!acc.includes(subject.category)) {
            acc.push(subject.category)
        }
        return acc
    }, [])
})

const updatedSubjects = ref(subjects.value.map(subject => {
    return {
        ...subject,
        selected: true
    }
}))
const newSubjects = computed(() => updatedSubjects.value.filter(subject => subject.isNew))
const selectedSubjects = computed(() => updatedSubjects.value.filter(subject => subject.selected || subject.isNew))

const colors = [
    "bg-blue-400",
    "bg-yellow-400",
    "bg-green-400",
    "bg-red-400",
    "bg-purple-400",
    "bg-pink-400",
    "bg-indigo-400",
    "bg-gray-400",
]

function submitSubjects() {

}

const newSubject = ref({
    name: "",
    category: "",
    isNew: true
})

const categoryOptions = computed(() =>
    categories.value.map(category => {
        return {
            label: category,
            value: category
        }
}))

function addToSubjectsList() {
    updatedSubjects.value.push(newSubject.value)
    isNewSubjectFormOpened.value = false
}

const isNewSubjectFormOpened = ref(false)
function openNewSubjectForm() {
    isNewSubjectFormOpened.value = true
}

function removeSubject(subject) {
    updatedSubjects.value = updatedSubjects.value.filter(sub => sub !== subject)
}
</script>
