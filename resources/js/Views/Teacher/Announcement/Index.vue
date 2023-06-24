<template>
    <div
        class="flex min-h-screen w-full flex-col space-y-5 bg-gray-50/50 px-10 py-5"
    >
        <div
            class="flex w-full flex-col items-center space-y-2 rounded-lg bg-white p-5 shadow-sm"
        >
            <div class="flex w-full items-center justify-between py-2 pr-5">
                <Title class="w-5/12" :title="$t('views.teacher.announcement.announcementIndex.announcements')" />
                <TextInput :placeholder="$t('views.teacher.announcement.announcementIndex.searchAnnouncements')" class="w-5/12" />
            </div>

            <div
                class="flex w-full justify-between divide-x divide-gray-100 py-6"
            >
                <SelectedAnnouncementView
                    class="h-fit w-5/12 !bg-gray-50/20 py-5 pr-5"
                    :selected-announcement="selectedAnnouncement"
                />
                <div class="w-7/12 px-10">
                    <Announcement
                        :show-header="false"
                        view="teacher"
                        class-style="sh"
                    />
                </div>
            </div>
        </div>

        <div
            class="flex w-full flex-col items-center space-y-2 rounded-lg bg-white p-5 shadow-sm"
        >
            <div class="flex w-full items-center justify-between py-2 pr-5">
                <Title class="w-full" :title="$t('views.teacher.announcement.announcementIndex.feedbacks')" />
            </div>

            <div
                class="flex w-full justify-between divide-x divide-gray-100 py-6"
            >
                <Feedbacks />
            </div>
        </div>
    </div>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import Title from "@/Views/Teacher/Views/Title.vue";
import Announcement from "@/Views/Announcements/Index.vue";
import { computed, ref } from "vue";
import SelectedAnnouncementView from "@/Views/Announcements/SelectedAnnouncement.vue";
import TextInput from "@/Components/TextInput.vue";
import Feedbacks from "@/Views/Teacher/Views/Home/Feedbacks.vue";

const announcements = computed(() => usePage().props.announcements);
const selectedAnnouncement = ref(announcements.value?.data[0]);

</script>
<style scoped></style>
