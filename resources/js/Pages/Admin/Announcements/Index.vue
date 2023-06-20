<template>
    <div class="flex min-h-screen w-full flex-col bg-gray-50/40 px-10 py-5">
        <div class="flex w-full flex-col items-center space-y-2 px-5 py-3">
            <div class="flex w-full items-center justify-between pr-5">
                <Title class="w-5/12" :title="$t('pages.admin.announcements.announcements')" />
                <TextInput :placeholder="$t('pages.admin.announcements.searchAnnouncements')"  class="w-5/12" />
            </div>

            <div class="flex w-full justify-between divide-gray-100 py-6 pr-5">
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
                    <Announcements :show-header="false" class-style="d" />
                </div>
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
import TextInput from "@/Components/TextInput.vue";

const announcements = computed(() => usePage().props.announcements);
const selectedAnnouncement = ref(announcements.value?.data[0]);
const selectedAnnouncement2 = ref(announcements.value?.data[1]);
</script>
<style scoped></style>
