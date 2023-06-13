<template>
    <div class="flex w-full flex-col space-y-2">
        <div class="flex w-full justify-between space-x-6 bg-gray-50">
            <!--        Announcements -->
            <div class="flex w-8/12 flex-col space-y-2 py-5 pl-5">
                <Announcement
                    url="/teacher/announcements/"
                    title="Recent Announcements"
                    :show-tab="true"
                />
            </div>

            <!--      Feedbacks -->
            <div
                class="flex w-4/12 flex-col items-center space-y-6 border-l bg-gray-50 px-3 py-5 pl-5"
            >
                <Feedbacks />
            </div>
        </div>
    </div>
</template>
<script setup>
import Feedbacks from "@/Views/Teacher/Home/Feedbacks.vue";
import { usePage } from "@inertiajs/vue3";
import Announcement from "@/Pages/Admin/Announcements/Index.vue";

const feedbacks = usePage().props.feedbacks;
</script>
<style scoped></style>
