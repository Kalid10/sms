<template>
    <div class="relative overflow-x-auto">
        <div class="flex flex-col justify-center p-4 sm:grow">
            <h3 class="font-semibold capitalize">{{ title }}</h3>
            <h5 v-if="subtitle" class="text-sm text-gray-500">
                {{ subtitle }}
            </h5>
        </div>
        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th v-for="column in columns" :key="column.key" scope="col" class="px-6 py-3">
                    {{ column.name }}
                </th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
            <template v-for="(log, index) in logs" :key="index">
                <tr v-if="logs.length === 0">
                    <td colspan="100%" class="py-4 text-center">
                        <div class="text-gray-500">
                            No data found.
                        </div>
                    </td>
                </tr>

                <tr class="cursor-pointer" @click="toggleDetails(log.id)">
                    <td class="whitespace-nowrap px-6 py-2">
                        <div class="text-sm text-gray-900">{{ log.log_name }}</div>
                    </td>
                    <td class="whitespace-nowrap px-6 py-2">
                        <div class="text-sm text-gray-900">{{ log.description }}</div>
                    </td>
                    <td class="px-6 py-2">
                        <div class="text-sm text-gray-900">{{ log.event }}</div>
                    </td>
                    <td class="whitespace-nowrap px-6 py-2">
                        <div class="text-sm text-gray-500">{{ moment(log.updated_at).fromNow() }}</div>
                    </td>
                </tr>
                <tr v-if="expandedRow === log.id">
                    <td colspan="100%" class="bg-gray-100">
                        <Details
                            :log="expandedRow === log.id ? log : null"
                            label="Detail"/>

                        <div class="p-4">
                            {{ log.details }}
                        </div>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>

        <div v-if="footer" class="flex gap-3 bg-neutral-50 p-4">
            <slot name="footer">
                <div class="flex w-full items-center gap-3 lg:justify-end">
                    <TertiaryButton :click="() => {}" class="w-full lg:w-fit lg:text-right" title="Previous"/>
                    <TertiaryButton :click="() => {}" class="w-full lg:w-fit lg:text-right" title="Next"/>
                </div>
            </slot>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import Details from "@/Views/ActivityLogs/Details.vue";
import moment from "moment";
import TertiaryButton from "@/Components/TertiaryButton.vue";

const props = defineProps({
    title: {
        type: String,
        default: null
    },
    subtitle: {
        type: String,
        default: null
    },
    logs: {
        type: Array,
        required: true
    },
    columns: {
        type: Array,
        required: true
    },
    footer: {
        type: Boolean,
        default: true,
    },
});

let expandedRow = ref(null);

const toggleDetails = (id) => {
    expandedRow.value = expandedRow.value === id ? null : id;
};
</script>

<style scoped></style>
