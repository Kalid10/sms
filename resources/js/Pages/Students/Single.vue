<template>

    <div class="mx-auto grid w-full grid-cols-12 gap-4">

        <div class="col-span-12 flex h-full flex-col p-2 md:col-span-2">

            <StudentProfile />

        </div>

        <div class="col-span-12 flex h-full flex-col gap-6 overflow-auto p-2 md:col-span-10">

            <StudentOverview />

            <div class="flex w-full gap-4">

                <StudentNotesPreview />

                <StudentSemesterSchedule />

            </div>

        </div>

    </div>

</template>

<script setup>
import {computed} from "vue"
import {usePage} from "@inertiajs/vue3";
import StudentProfile from "@/Views/Students/StudentProfile.vue";
import StudentNotesPreview from "@/Views/Students/StudentNotesPreview.vue";
import StudentSemesterSchedule from "@/Views/Students/StudentSemesterSchedule.vue";
import StudentOverview from "@/Views/Students/StudentOverview/Index.vue";

const student = computed(() => usePage().props.student)
const section = computed(() => usePage().props.active_batch)

</script>

<style scoped>

</style>
