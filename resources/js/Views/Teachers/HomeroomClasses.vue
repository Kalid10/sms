<template>
    <div>
        <Heading size="lg">Homerooms</Heading>
        <div
            v-if="teacher.homeroom.length > 0"
            class="flex w-fit flex-wrap space-x-1.5 "
        >
            <template v-for="(item, index) in teacher.homeroom" :key="index">
                <a
                    :href="`/batches/${item.batch.id}`"
                    class="font-medium hover:scale-105 hover:font-bold hover:underline">
                    {{ item.batch.level.name + item.batch.section }}
                </a>
                <span v-if="index < teacher.homeroom.length - 2">, </span>
                <span v-else-if="index === teacher.homeroom.length - 2"> and </span>
            </template>
        </div>
        <div v-else class="flex h-24 items-center justify-center">
            <div>No homeroom class attached yet!</div>
        </div>
    </div>
</template>


<script setup>
import Heading from "@/Components/Heading.vue";
import {usePage} from "@inertiajs/vue3";


const teacher = usePage().props.teacher;
</script>

<style scoped>

</style>
