<template>

    <Modal>

        <FormElement :title="`Assign Teacher for ${parseLevel(level.name)} ${selectedBatch.section} ${subject.full_name}`" @submit="assignTeacher">

            <label v-if="selectedTeacher" class="flex flex-col gap-1">
                <span class="pl-0.5 text-xs font-semibold text-gray-500">Selected Teacher</span>
                <span class="flex h-9 w-full items-center justify-between rounded-md border border-gray-200 px-3 text-sm">
                    <span class="text-sm">{{ selectedTeacher.name }}</span>
                    <XCircleIcon class="h-5 w-5 cursor-pointer fill-negative-100" @click="selectedTeacher = null" />
                </span>
            </label>

            <TextInput v-else v-model="query" label="Find Teacher" placeholder="Search for Teacher by name" />

            <div v-if="!selectedTeacher" class="flex w-full flex-col">
                <div v-if="teachers.length < 1" class="flex h-48 w-full flex-col items-center justify-center gap-2 text-center">

                    <p class="text-sm font-semibold">No teachers found!</p>
                    <p class="text-sm text-gray-500">You can either register a new teacher <span class="block">or update your search query</span></p>

                </div>
                <button v-for="(teacher, t) in teachers" :key="t" type="button" class="flex h-12 w-full items-center justify-between rounded-md px-4 odd:bg-gray-100" @click="selectTeacher(teacher)">
                    <span class="text-sm">{{ teacher.name }}</span>
                    <span class="text-sm">
                        <span class="text-xs font-semibold">{{ teacher.teacher.active_weekly_sessions }} weekly sessions</span>
                    </span>
                </button>
            </div>

        </FormElement>

    </Modal>

</template>

<script setup>
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue";
import {computed, inject, onMounted, ref, watch} from "vue";
import {router, usePage} from "@inertiajs/vue3";
import {parseLevel} from "@/utils.js";
import { XCircleIcon } from "@heroicons/vue/20/solid";
import TextInput from "@/Components/TextInput.vue";

const showNotification = inject("showNotification");
const batchSubject = computed(() => usePage().props.batchSubject)
const subject = computed(() => usePage().props.subject)
const selectedBatch = computed(() => usePage().props.batch)
const level = computed(() => usePage().props.batch.level)
const teachers = computed(() => usePage().props.teachers ?? [])

const emits = defineEmits(['success'])

const query = ref('');
watch(query, () => {

    router.visit(`/batches/subjects/${selectedBatch.value.id}/${subject.value.id}?teacher=${query.value.toLowerCase()}`, {
        only: ['teachers'],
        preserveState: true
    })

})

const selectedTeacher = ref(null)

function selectTeacher(teacher) {
    query.value = ""
    selectedTeacher.value = teacher
}

function assignTeacher() {
    router.post(`/batches/subjects/assign/${batchSubject.value.id}/assign-teacher`, {
        teacher_id: selectedTeacher.value.teacher.id
    }, {
        onSuccess() {
            emits('success')
        },
        onError() {
            Object.keys(usePage().props.errors).forEach((error) => {
                showNotification({
                    type: "error",
                    message: usePage().props.errors[error],
                    position: "top-center",
                })
            })
            selectedTeacher.value = null
            loadTeachers()
        }
    }, {
        preserveState: true
    })
}

function loadTeachers() {
    router.reload({
        only: ['teachers'],
        preserveState: true
    })
}

onMounted(() => {
    loadTeachers()
})
</script>

<style scoped>

</style>
