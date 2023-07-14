<template>
    <div>
        <Heading size="lg">{{ $t('homeroomClasses.homerooms')}}</Heading>
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
                <span v-else-if="index === teacher.homeroom.length - 2"> {{ $t('common.and')}} </span>
            </template>
        </div>
        <div v-else class="flex h-24 items-center justify-center">
            <div>{{ $t('homeroomClasses.noHomeroomClassAttached')}}</div>
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
