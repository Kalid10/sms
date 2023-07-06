<template>
    <div
        class="space flex w-full flex-col space-y-5 rounded-lg bg-white py-5 px-3 shadow-sm"
    >
        <div class="px-2 text-xl font-medium lg:text-2xl">
            Upcoming Schedules
        </div>
        <div class="flex w-full flex-col justify-center">
            <div
                v-for="(item, index) in schoolSchedule"
                :key="index"
                class="rounded-lg px-1"
                :class="index % 2 === 0 ? 'bg-gray-50/50 ' : ''"
            >
                <SchoolScheduleItem
                    class="!py-2"
                    :school-schedule="item"
                    view="teacher"
                />
            </div>
        </div>
        <LinkCell
            class="flex w-full items-center justify-center"
            value="View All Schedules"
            :href="isAdmin() ? '/admin/schedules' : '/teacher/school-schedule'"
        />
    </div>
</template>

<script setup>
import { isAdmin } from "@/utils";
import SchoolScheduleItem from "@/Views/Admin/Schedule/SchoolScheduleItem.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import LinkCell from "@/Components/LinkCell.vue";

const schoolSchedule = computed(() => usePage().props.school_schedule);
</script>
<style scoped></style>
