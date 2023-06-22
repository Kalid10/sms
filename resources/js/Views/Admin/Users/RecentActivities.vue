<template>
    <div
        class="flex flex-col space-y-4 rounded-lg border border-gray-100 bg-white p-3 py-4 shadow-sm"
    >
        <div class="text-center text-xl font-semibold">Recent Activities</div>

        <div class="flex flex-col space-y-1">
            <div
                v-for="(item, index) in activityLogs.data"
                :key="index"
                class="w-full p-2 py-3 text-xs font-medium capitalize"
                :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'"
            >
                {{ item.properties.attributes.name }}
                {{ item.event }}
                by {{ item.causer ?? "System" }}
                <span class="font-light italic">
                    (
                    {{ item.properties.attributes.email }} )
                </span>
            </div>
        </div>
        <Pagination clas="pb-3" :links="activityLogs.links" position="center" />
    </div>
</template>
<script setup>
import Pagination from "@/Components/Pagination.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const activityLogs = computed(() => {
    return usePage().props.activity_log;
});
</script>
<style scoped></style>
