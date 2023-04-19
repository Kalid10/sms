<template>

    <div class="mx-auto grid w-full grid-cols-12 gap-4">

        <div class="col-span-12 flex h-full flex-col p-2 md:col-span-2">

            <div class="flex flex-col items-center gap-5">

                <div class="flex w-full flex-col gap-3">

                    <img
                        :src="`https://xsgames.co/randomusers/avatar.php?g=${student.user.gender}`"
                        alt="avatar"
                        class="mx-auto aspect-square w-16 rounded-full md:w-3/4"
                    />

                    <div class="mx-auto flex w-fit flex-col items-center">
                        <Heading class="!font-semibold" size="lg">{{ student.user.name }}</Heading>
                        <div class="flex w-full min-w-[28px] justify-start gap-2 leading-none">
                            <h3 class="text-gray-500" size="">12B</h3>
                        </div>
                    </div>


                </div>

            </div>

        </div>

        <div class="col-span-12 h-full overflow-auto p-2 md:col-span-10">

            <div class="flex flex-col gap-4">

                <TabElement :tabs="tabs">

                    <template #personal_details>

                        <StudentPersonalDetail/>

                    </template>

                    <template #guardian>

                        <StudentGuardian/>

                    </template>

                    <template #notes>

                        <NotesAboutStudent/>

                    </template>

                </TabElement>

            </div>

        </div>

    </div>

</template>

<script setup>
import {computed} from "vue"
import {usePage} from "@inertiajs/vue3";
import Heading from "@/Components/Heading.vue";
import TabElement from "@/Components/TabElement.vue";
import StudentPersonalDetail from "@/Views/Students/StudentPersonalDetail.vue"
import StudentGuardian from "@/Views/Students/StudentGuardian.vue"
import NotesAboutStudent from "@/Views/Students/NotesAboutStudent.vue";

const student = computed(() => usePage().props.student)

const tabs = [
    'Personal Details',
    'Guardian',
    'Classes',
    'Notes'
]

</script>

<style scoped>

</style>
