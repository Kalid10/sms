<template>

    <div class="flex flex-col">
        <Heading>Register Subjects</Heading>
        <h3 class="text-sm text-gray-500">
            We have selected a set predefined subjects for you. You can remove the subjects you don't provide or
            add more subjects not listed here.
            <span class="inline-block">Click on the "Finish" to proceed, and "Next" to save and proceed.</span>
        </h3>
    </div>

    <div class="flex flex-wrap gap-2 md:gap-5">
        <span class="col-span-1 text-sm text-gray-500 sm:col-span-2 md:col-span-3 lg:col-span-3 xl:col-span-4">
            <span class="whitespace-nowrap font-semibold text-black">{{ updatedSubjects.length }}</span> Total Subjects
        </span>
        <span class="col-span-1 text-sm text-gray-500 sm:col-span-2 md:col-span-3 lg:col-span-3 xl:col-span-4">
            <span class="whitespace-nowrap font-semibold text-black">{{ selectedSubjects.length }}</span> Subjects Selected
        </span>
        <span v-if="newSubjects.length > 0" class="col-span-1 text-sm text-gray-500 sm:col-span-2 md:col-span-3 lg:col-span-3 xl:col-span-4">
            <span class="whitespace-nowrap font-semibold text-black">{{ newSubjects.length }}</span> New Subject{{ newSubjects.length > 1 ? 's' : '' }}
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
                    <Card :class="{ 'border-black': !! filteredLabel && subjectIncludesLabel(subject), 'opacity-20': !subjectIncludesLabel(subject), 'border-black shadow-md': subject.isNew }" class="group !min-w-full transition duration-150">

                        <div :class="subject.selected || subject.isNew ? 'opacity-100' : 'opacity-25'" class="flex flex-col justify-between gap-6">

                            <div class="flex items-center justify-between">

                                <h3 class="flex items-baseline gap-2">
                                    <span class="font-semibold">
                                        {{ subject.full_name }}
                                    </span>
                                    <span class="text-sm uppercase text-gray-500">
                                        {{ subject.short_name }}
                                    </span>
                                </h3>

                                <Checkbox
                                    v-if="!!!subject.isNew"
                                    v-model="subject.selected"
                                    class="h-5 w-5 !rounded-full border-none bg-transparent checked:ring-0 focus:ring-0"
                                />
                                <TrashIcon v-if="subject.isNew" class="h-5 w-5 cursor-pointer stroke-black stroke-2" @click="removeSubject(subject)"></TrashIcon>

                            </div>

                            <span class="flex origin-bottom-left scale-[.85] flex-wrap gap-1">

                                <span
                                    v-for="(label, l) in subject.tags"
                                    :key="l"
                                    :class="{ 'bg-gray-300' : filteredLabel === label }"
                                    class="cursor-pointer whitespace-nowrap rounded-xl px-1.5
                                    py-0.5 text-xs font-semibold leading-none transition
                                    duration-300 hover:scale-110 hover:bg-gray-300"
                                    @click="($e) => { $e.preventDefault(); }"
                                    @mouseout="resetFilteredLabel"
                                    @mouseover="filterTags(label)">
                                    {{ toHashTag(label) }}
                                </span>

                            </span>
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
        <TertiaryButton v-if="selectedSubjects.length < updatedSubjects.length" @click="resetSubjects">Reset</TertiaryButton>

    </div>

    <Modal v-model:view="isNewSubjectFormOpened">

        <FormElement title="New Subject" subtitle="Create a new subject and assign it to a category" @submit="addToSubjectsList">

            <TextInput v-model="newSubject.full_name" required placeholder="Name of the new Subject" label="Subject Name"/>
            <TextInput v-model="newSubject.short_name" required placeholder="Short name for Subject" label="Subject Short Name"/>
            <TextInput v-model="tags" placeholder="Assign tags (separate multiple tags with comma)" label="Subject Tags"/>
            <SelectInput v-if="!customCategory" v-model="newSubject.category" :options="categoryOptions" required placeholder="Category of the new Subject" label="Subject Category"/>
            <TextInput v-else v-model="newSubject.category" required placeholder="Category of the new Subject" label="Subject Category"/>

        </FormElement>

    </Modal>

</template>

<script setup>
import { ref, watch, computed } from "vue"
import { subjects as initialSubjects } from "@/fake.js";
import { toHashTag } from "@/utils.js";
import { PlusCircleIcon, PlusIcon, TrashIcon } from "@heroicons/vue/24/outline"
import Heading from "@/Components/Heading.vue"
import Card from "@/Components/Card.vue"
import Checkbox from "@/Components/Checkbox.vue"
import Modal from "@/Components/Modal.vue"
import FormElement from "@/Components/FormElement.vue"
import PrimaryButton from "@/Components/PrimaryButton.vue"
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";
import {router} from "@inertiajs/vue3";

const subjects = computed(() => initialSubjects)
const categories = computed(() => {
    return updatedSubjects.value.reduce((acc, subject) => {
        if (!acc.includes(subject['category'])) {
            acc.push(subject['category'])
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
    "bg-blue-500",
    "bg-yellow-500",
    "bg-green-500",
    "bg-red-500",
    "bg-purple-500",
    "bg-pink-500",
    "bg-indigo-500",
    "bg-gray-500",
]

function submitSubjects() {

    router.post('/subjects/create-bulk', {
        subjects: updatedSubjects.value.map(subject => {
            return {
                full_name: subject['full_name'],
                short_name: subject['short_name'],
                category: subject['category'],
                tags: subject['tags']
            }
        })
    }, {
        onSuccess: () => {
            console.log('Success')
        },
        onError: () => {
            console.log('Error')
        }
    })

}


const newSubject = ref({
    full_name: "",
    category: "",
    isNew: true
})

const tags = ref('')
const computedShortName = computed(() => newSubject.value.full_name.substring(0, Math.min(3, newSubject.value.full_name.length)).toUpperCase())
const formShortName = ref(null)

watch(tags, (value) => {
    console.log(value.split(','))
    newSubject.value.tags = value.split(',')
})

watch([computedShortName, formShortName], () => {
    newSubject.value.short_name = formShortName.value ?? computedShortName.value
})

// const categoryOptions = computed(() => {
//
//     return {
//         ...categories.value.map(category => {
//             return {
//                 label: category,
//                 value: category
//             }
//         }),
//         ...{
//             label: "Create new Category",
//             value: "custom"
//         }
//     }
//
// })


const categoryOptions = computed(() => {
    const options = categories.value.map(category => {
        return {
            label: category,
            value: category
        }
    })
    options.push({
        label: "+ Add new Category",
        value: "Custom"
    })

    return options
})

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

function resetSubjects() {
    updatedSubjects.value = subjects.value.map(subject => {
        return {
            ...subject,
            selected: true
        }
    })
}

function subjectIncludesLabel(subject) {
    if (filteredLabel.value === null) return true
    return subject.tags.includes(filteredLabel.value)
}

const filteredLabel = ref(null)
function filterTags(label) {
    filteredLabel.value = label
}

function resetFilteredLabel() {
    filteredLabel.value = null
}

const customCategory = ref(false)
watch(newSubject, () => {
    if (newSubject.value.category === 'Custom') {
        customCategory.value = true
    }
}, {
    deep: true
})
</script>
