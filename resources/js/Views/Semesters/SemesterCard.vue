<template>
    <Card
        :icon="semester.status === 'active'" :subtitle="semester.school_year" :title="semester.name"
        class="!w-full !max-w-full">
        <div class="flex items-start justify-between">

            <div class="flex gap-4">
                <div class="flex items-center gap-1">
                    <div
                        :class="{
                                'animate-pulse bg-positive-50': semester.status === 'active',
                                'bg-blue-400': semester.status === 'upcoming',
                                'bg-neutral-200': semester.status === 'completed'
                            }"
                        class="h-3 w-3 rounded-full"
                    />
                    <span class="text-xs capitalize">{{ semester.status }}</span>
                </div>
                <span class="text-xs text-gray-500">Updated {{ moment(semester.last_updated).fromNow() }}</span>
            </div>

        </div>

        <template #icon>
            <CircularProgress
                v-if="semester.status === 'active'"
                :dasharray="75"
                :diameter="30"
                :percentage="74"
                color="positive"
            />
        </template>

    </Card>
</template>

<script setup>
import moment from "moment";
import Card from "@/Components/Card.vue";
import CircularProgress from "@/Components/CircularProgress.vue";

defineProps({
    semester: {
        type: Object,
        required: true
    }
})
</script>

<style scoped>

</style>
