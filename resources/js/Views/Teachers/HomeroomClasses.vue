<template>
    <div>
        <Heading size="lg">Homerooms</Heading>
        <div
            v-if="teacher.homeroom.length > 0"
            class="flex h-full w-full flex-col flex-wrap space-y-3 md:flex-row md:space-y-0"
        >
            <div
                v-for="(item, index) in teacher.homeroom" :key="index"
                class="w-full"
                :class=" teacher.homeroom.length > 3 ? 'md:w-1/4':'md:w-1/2 lg:w-1/3'">
                <Card :title="item.batch.level.name + item.batch.section" subtitle="Class" :icon="true">
                    <template #icon>
                        <AcademicCapIcon/>
                    </template>
                </Card>
            </div>
        </div>

        <div v-else class="flex h-24 items-center justify-center">
            <div>No hoomeroom class attached yet!</div>
        </div>
    </div>
</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import {AcademicCapIcon} from "@heroicons/vue/20/solid";
import {usePage} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";


const teacher = usePage().props.teacher;

</script>

<style scoped>

</style>
