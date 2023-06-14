<template>
    <div
        class="flex w-full flex-col items-center justify-center space-y-2 rounded-lg bg-white py-3 shadow-sm"
    >
        <div
            class="flex space-x-2 text-center text-xl font-semibold underline-offset-4"
        >
            <UserGroupIcon class="w-6" />
            <span>Guardian Info</span>
        </div>

        <div
            class="flex w-11/12 flex-col items-center space-y-2 pt-3 text-center text-xs 2xl:text-xs"
        >
            <div class="flex h-8 w-full items-center justify-center space-x-2">
                <div class="font-semibold text-gray-500">Name:</div>
                <div class="text-gray-700">{{ guardian.user.name }}</div>
            </div>
            <div class="flex h-8 w-full items-center justify-center space-x-2">
                <div class="font-semibold text-gray-500">Ralation:</div>
                <div class="text-gray-700">{{ guardianRelation }}</div>
            </div>
            <div class="flex h-8 w-full items-center justify-center space-x-2">
                <div class="font-semibold text-gray-500">Phone Number:</div>
                <div class="text-gray-700">
                    {{ guardian.user.phone_number }}
                </div>
            </div>
            <div class="flex h-8 w-full items-center justify-center space-x-2">
                <div class="font-semibold text-gray-500">Email:</div>
                <div class="text-gray-700">{{ guardian.user.email }}</div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { UserGroupIcon } from "@heroicons/vue/24/outline";

const guardian = computed(() => usePage().props.student.guardian);
const guardianRelation = computed(
    () => usePage().props.student.guardian_relation
);

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
