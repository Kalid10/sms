<template>
    <div class="flex min-h-screen w-full flex-col bg-gray-50/50 px-10">
        <Title class="w-full" title="Announcements" />
        <div
            class="flex w-full justify-between divide-gray-100 rounded-lg py-6 pr-5"
        >
            <div class="flex h-full w-5/12 flex-col space-y-6 p-3">
                <SelectedAnnouncementView
                    class="h-2/5"
                    :selected-announcement="selectedAnnouncement"
                />
                <SelectedAnnouncementView
                    class="h-2/5"
                    :selected-announcement="selectedAnnouncement2"
                />
            </div>
            <div class="w-6/12">
                <Announcements
                    :show-header="false"
                    class-style="bg-gray-50/50 rounded-lg py-3"
                />
            </div>
        </div>
    </div>
</template>
<script setup>
import Announcements from "@/Views/Announcements/Index.vue";

import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import Title from "@/Views/Teacher/Views/Title.vue";
import SelectedAnnouncementView from "@/Views/Announcements/SelectedAnnouncement.vue";

const announcements = computed(() => usePage().props.announcements);
const selectedAnnouncement = ref(announcements.value?.data[0]);
const selectedAnnouncement2 = ref(announcements.value?.data[1]);
</script>
<style scoped></style>
