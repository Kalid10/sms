<template>

    <div class="flex w-full flex-col space-y-3 px-5 py-3 lg:space-y-10 lg:px-12">

        <!-- Header-->
        <div
            class="flex items-center rounded-lg py-5 text-xl font-light lg:py-7 lg:pl-0 lg:text-4xl"
        >
            <span class="ml-1 font-semibold lg:ml-2">
                {{ student.user.name }}
            </span>
        </div>

        <div class="grid w-full grid-cols-12">

            <div class="col-span-6 h-fit w-full rounded-lg">
                <div class="flex w-full justify-between">
                    <div class="font-medium lg:text-2xl">Recent Assessments for {{ student.user.name }}</div>
                    <div
                        class="flex w-fit items-center justify-center space-x-1 rounded-md px-3 text-xs font-medium underline underline-offset-2 hover:scale-105 hover:cursor-pointer lg:text-sm"
                    >
                        <div>SEE ALL</div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</template>

<script setup>
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import CircularProgress from "@/Components/CircularProgress.vue";

const student = computed(() => usePage().props.student)
const assessments = computed(() => usePage().props.assessments)

</script>

<style scoped>

</style>
