<template>


    <div class="flex w-full flex-col gap-4">

        <Heading size="md">Teacher</Heading>

        <template v-if="!!!teacher">

            <button
                class="flex h-52 w-72 flex-col items-center justify-center gap-2 rounded-lg border border-dashed border-gray-500 bg-gray-100"
                @click="openAssignTeacherModal = true"
            >

                <UserIcon class="h-6 w-6 fill-gray-800" />
                <Heading size="sm">Assign teacher</Heading>
                <Heading size="xs" class="px-4 text-center !font-normal normal-case text-gray-500">
                    <span class="font-semibold text-negative-100">Currently, no teacher is assigned</span> to this class.
                    Click here to assign a teacher. Otherwise this class cannot be scheduled for students.
                </Heading>


            </button>

        </template>

    </div>

    <AssignTeacherToBatchSubjectModal v-model:view="openAssignTeacherModal" @success="openAssignTeacherModal = false" />

</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import {computed, ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import {
    UserIcon,
} from "@heroicons/vue/20/solid";
import AssignTeacherToBatchSubjectModal from "@/Views/Admin/BatchSubjects/AssignTeacherToBatchSubjectModal.vue";

const batchSubject = computed(() => usePage().props.batchSubject)
const teacher = computed(() => batchSubject.value.teacher)

const openAssignTeacherModal = ref(false)
</script>

<style scoped>

</style>
