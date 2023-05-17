<template>

    <Card
        :title="`Week ${index}`"
        class="h-full !max-w-[26rem] !rounded-b-none !border-b-0 !bg-gray-50 !p-3 drop-shadow-sm"
    >

        <template #subtitle>
            <span v-if="batchSessions.length > 1">
                Class sessions for the week starting from
                <span class="inline-block font-semibold">
                    {{ moment(batchSessions[0]['date']).startOf('week').format('MMMM Do') }}
                </span>
                and ending on
                <span class="inline-block font-semibold">
                    {{ moment(batchSessions[0]['date']).endOf('week').format('MMMM Do') }}
                </span>
            </span>
        </template>

        <div class="scrollbar-hide mt-2 flex h-full max-h-full w-full flex-col gap-4 overflow-auto">

            <div v-if="batchSessions.length < 1" class="flex h-full flex-col items-center">

                <div class="mt-24 flex w-full flex-col items-center justify-center">
                    <ExclamationTriangleIcon class="mb-2 h-6 w-6 text-negative-50"/>
                    <h3 class="text-sm font-semibold text-black">No Class Sessions</h3>
                    <h3 class="max-w-[18rem] text-center text-sm text-gray-500">
                        There will be no class sessions
                        <span class="inline-block">during Week {{ w }}</span>.
                    </h3>
                </div>

            </div>

            <template v-else>

                <slot />

            </template>

        </div>

    </Card>

</template>

<script setup>
import moment from "moment";
import Card from "@/Components/Card.vue";
import {ExclamationTriangleIcon} from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
    index: {
        type: Number,
        required: true
    },
    batchSessions: {
        type: Array,
        required: true
    }
})
</script>

<style scoped>

</style>
