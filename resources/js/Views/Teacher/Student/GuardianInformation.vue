<template>
    <div
        class="flex w-full flex-col items-center justify-center space-y-2 py-2"
    >
        <div
            class="flex space-x-2 text-center text-xl font-semibold underline-offset-4"
        >
            <UserGroupIcon class="w-6" />
            <span>Guardian Info</span>
        </div>

        <div class="flex w-9/12 flex-col space-y-2 pt-3 text-xs 2xl:text-sm">
            <div
                v-for="item in info"
                :key="item.label"
                class="flex h-8 w-full items-center space-x-2"
            >
                <div class="font-semibold text-gray-500">{{ item.label }}</div>
                <div class="text-gray-700">{{ item.value }}</div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { UserGroupIcon } from "@heroicons/vue/24/outline";

const student = computed(() => usePage().props.student);

const info = [
    {
        label: "Name",
        value: "John Doe",
    },
    {
        label: "Email",
        value: "john@gmail.com",
    },
    {
        label: "Phone",
        value: "1234567890",
    },
    {
        label: "Address",
        value: "123, Lorem Ipsumit Amet",
    },
    {
        label: "Interaction",
        value: "Moderate",
    },
];
</script>

<style scoped></style>
