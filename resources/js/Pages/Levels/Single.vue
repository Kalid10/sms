<template>
    <div class="body-font text-gray-600">
        <div class="container mx-auto px-5">
            <div class="mb-5 flex w-full flex-col flex-wrap items-center text-center">
                <h1 class="title-font mb-2 text-2xl font-medium text-gray-900 sm:text-3xl">{{ level.name }} Details</h1>
                <span class="mt-1 text-sm text-gray-500">Category : {{
                        level.level_category.name
                    }}</span>
            </div>

            <div class="grid w-full grid-cols-1 gap-y-3 md:grid-cols-3  md:gap-2">
                <LevelCard
                    v-for="(batch, index) in  level.batches" :key="index"
                    class="min-w-full">
                    <BuildingOfficeIcon class="h-6 w-6"/>
                    <h2 class="text-md cursor-pointer font-medium text-gray-900">{{
                            batch.school_year.name
                        }}</h2>
                    <span class="mb-1 text-sm text-gray-500">Section {{ batch.section }} <br>
                    </span>
                    <div class="border-t border-gray-200 dark:border-gray-600">
                        <div class="rounded-lg bg-white dark:bg-gray-800">
                            <dl class="mx-auto grid max-w-screen-xl grid-cols-2 gap-8 p-4 text-gray-900 dark:text-white sm:grid-cols-3 sm:p-8 xl:grid-cols-6">
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-bold">{{
                                            batch.students.length
                                        }}+
                                    </dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Students</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-bold">{{ batch.subjects.length }}+</dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Subjects</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </LevelCard>
            </div>
        </div>
    </div>
</template>
<script setup>
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import LevelCard from "@/Components/Card.vue";
import {BuildingOfficeIcon} from "@heroicons/vue/24/solid";

const level = computed(() => usePage().props.level);
</script>
