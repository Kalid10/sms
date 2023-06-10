<template>
    <div class="my-2 flex w-full border-t-2">
        <div class="flex w-4/12 flex-col items-start p-8">
            <h1 class="text-center text-xl font-medium text-gray-700"> Section A details </h1>
            <p class="text-center text-lg font-light text-gray-500">
                <span class="text-sm ">
                    Homeroom teacher:
                    <span class="text-sm text-gray-700">
                        {{ batch.home_room_teacher.teacher.user.name }}
                    </span>
                </span>
            </p>
        </div>
        <div class="w-8/12">
            <div class="m-2 grid grid-cols-1 rounded-md border-2 border-gray-100 py-3 md:grid-cols-2 lg:grid-cols-4">
                <div class="relative mb-12 px-3 lg:mb-0">
                    <div class="mb-2 flex justify-center">
                          <span class="">
                         <BookOpenIcon class="h-10 w-10"/>
                          </span>
                    </div>
                    <h5 class="mb-6 flex justify-center text-sm font-bold">A+</h5>
                    <h6 class="mb-0 flex justify-center text-xs text-black">Conduct</h6>
                    <div
                        class="absolute right-0 top-0 hidden h-full min-h-[1em] w-px self-stretch border-t-0 bg-gradient-to-tr from-transparent via-neutral-500 to-transparent opacity-25 dark:opacity-100 lg:block"></div>
                </div>
                <div class="relative mb-12 px-3 lg:mb-0">
                    <div class="mb-2 flex justify-center">
                          <span class="">
                         <BookOpenIcon class="h-10 w-10"/>
                          </span>
                    </div>
                    <h5 class="mb-6 flex justify-center text-sm font-bold">Biology test</h5>
                    <h6 class="mb-0 flex justify-center text-xs text-black">Upcoming Assessment</h6>
                    <div
                        class="absolute right-0 top-0 hidden h-full min-h-[1em] w-px self-stretch border-t-0 bg-gradient-to-tr from-transparent via-neutral-500 to-transparent opacity-25 dark:opacity-100 lg:block"></div>
                </div>
                <div class="relative mb-12 px-3 lg:mb-0">
                    <div class="mb-2 flex justify-center">
                          <span class="">
                         <AcademicCapIcon class="h-10 w-10"/>
                          </span>
                    </div>
                    <h5 class="mb-6 flex justify-center text-sm font-bold">Biniyam Lemma </h5>
                    <h6 class="mb-0 flex justify-center text-xs text-black">Section's Best</h6>
                    <div
                        class="absolute right-0 top-0 hidden h-full min-h-[1em] w-px self-stretch border-t-0 bg-gradient-to-tr from-transparent via-neutral-500 to-transparent opacity-25 dark:opacity-100 lg:block"></div>
                </div>
                <div class="relative mb-12 px-3 lg:mb-0">
                    <div class="mb-2 flex justify-center">
                          <span class="">
                         <AcademicCapIcon class="h-10 w-10"/>
                          </span>
                    </div>
                    <h5 class="mb-6 flex justify-center text-sm font-bold">Biniyam Lemma </h5>
                    <h6 class="mb-0 flex justify-center text-xs text-black">Section's Best</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3">
        <div v-if="activeSession !== null && activeSession.session.length > 0" class="p-8">
            <div
                class="flex h-16 w-16 items-center justify-center rounded-full bg-indigo-100 text-indigo-500 shadow-2xl">
                <BookOpenIcon class="h-8 w-8"/>
            </div>
            <h2 class="mt-6 mb-3 font-medium">
                Active Session
            </h2>
            <p
                v-if="activeSession.session.length > 0"
                class="my-2 mb-3 text-sm font-light text-gray-500">
                Subject: {{ activeSession.session[0].subject }}
            </p>
            <p
                v-if="activeSession.session.length > 0"
                class="my-2 mb-3 text-sm font-light text-gray-500">
                Status: {{ activeSession.session[0].status }}
            </p>

            <p
                v-if="activeSession.session.length > 0"
                class="my-2 mb-3 text-sm font-light text-gray-500">
                Teacher: {{ activeSession.session[0].teacher }}
            </p>

            <p
                v-if="activeSession.session.length > 0"
                class="my-2 mb-3 text-sm font-light text-gray-500">
                Period: {{ activeSession.session[0].period }}
            </p>

        </div>

        <div v-else class="p-8">
            <div
                class="flex h-16 w-16 items-center justify-center rounded-full bg-indigo-100 text-indigo-500 shadow-2xl">
                <BookOpenIcon class="h-8 w-8"/>
                <XMarkIcon class="h-8 w-8"/>
            </div>
            <h2 class="mt-6 mb-3 font-medium">
                No Active Sessions
            </h2>
            <p class="my-2 mb-3 text-sm font-light text-gray-500">
                There are currently no active sessions.
            </p>
        </div>
        <div class="p-8">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-500 shadow-2xl">
                <ClipboardDocumentCheckIcon class="h-8 w-8"/>

            </div>
            <h2 class="mt-6 mb-3 font-medium "> Notes </h2>
            <p class="mb-3 text-sm font-light text-gray-500">
                Notes for this section...
            </p>    <a
            class="flex items-center text-green-500 hover:text-green-600" href="/public"> More

        </a></div>
        <div class="p-8">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-500 shadow-2xl">
                <Cog6ToothIcon class="h-8 w-8"/>

            </div>
            <h2 class="mt-6 mb-3 font-medium"> Social conversations </h2>
            <p class="mb-3 text-sm font-light text-gray-500"> We get .......</p>    <a
            class="flex items-center text-red-500 hover:text-red-600" href="/public"> More

        </a></div>
    </div>
</template>
<script setup>
import {
    AcademicCapIcon,
    BookOpenIcon,
    ClipboardDocumentCheckIcon,
    Cog6ToothIcon,
    XMarkIcon
} from "@heroicons/vue/20/solid/index.js";
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";

const props = defineProps({
    batch: {
        type: Object,
        required: true,
    },
})

const level = computed(() => {
    return usePage().props.level;
});

const activeSession = computed(() => {
    if (props.batch.active_session) {
        return {
            session: props.batch.active_session.map(s => {
                return {
                    id: s.id,
                    name: s.name,
                    status: s.status,
                    subject: s.batch_subject.subject.full_name,
                    teacher: s.teacher.user.name,
                    period: s.school_period.name,
                }
            })
        }
    }
    return null;
})

</script>
<style scoped></style>
