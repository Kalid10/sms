<template>
    <div
        class="flex h-full w-full flex-col items-center justify-evenly rounded-xl bg-black text-white"
    >
        <div
            v-if="nextClass"
            class="flex w-full flex-col justify-evenly divide-y-2 divide-neutral-800 py-2 lg:flex-row lg:divide-y-0 lg:py-0"
        >
            <NextClass />

            <!--          Line Separator for LG and XL screens-->
            <div
                class="hidden h-full w-0.5 bg-neutral-600 lg:inline-block"
            ></div>

            <LastAssessment />
        </div>

        <!--        Fall back message-->
        <div
            v-else
            class="flex h-32 w-11/12 flex-col justify-center text-center text-sm font-light leading-relaxed lg:w-10/12 lg:text-xl"
        >
            <div>
                No upcoming classes found! Please check your schedule or contact
                the
                <span
                    class="cursor-pointer underline underline-offset-2 hover:font-medium"
                    >admin</span
                >
                for assistance.
            </div>
        </div>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import NextClass from "@/Views/Teachers/NextClass/NextClass.vue";
import LastAssessment from "@/Views/Teachers/NextClass/LastAssessment.vue";

const nextClass = usePage().props.teacher.next_batch_session;
</script>

<style scoped></style>
