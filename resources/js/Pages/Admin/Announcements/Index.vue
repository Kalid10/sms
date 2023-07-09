<template>
    <div class="flex min-h-screen w-full flex-col bg-gray-50 px-10 py-5">
        <div class="flex w-full flex-col items-center space-y-2 px-5 py-3">
            <div class="flex w-full items-center justify-between px-5">
                <Title class="w-5/12" title="Announcements" />
                <div class="flex w-6/12 justify-between pr-3">
                    <TextInput
                        v-model="searchKey"
                        placeholder="Search Announcements"
                        class="w-6/12 !rounded-xl"
                        class-style="focus:ring-1 focus:ring-zinc-700 focus:border-none focus:outline-none rounded-2xl"
                        @keyup="search"
                    />
                    <div
                        class="flex w-fit cursor-pointer items-center justify-center space-x-2 rounded-2xl bg-zinc-800 px-4 py-1 text-xs text-white"
                        @click="showAddAnnouncement = true"
                    >
                        <SquaresPlusIcon class="w-4 text-white" />
                        <span> Publish Announcement </span>
                    </div>
                </div>
            </div>

            <div
                class="flex w-full justify-between space-x-4 divide-gray-100 pr-5"
            >
                <div
                    class="flex h-full w-6/12 flex-col justify-evenly space-y-6 p-3 pr-6"
                >
                    <SelectedAnnouncementView
                        class="h-2/5"
                        :selected-announcement="selectedAnnouncement"
                    />
                    <SelectedAnnouncementView
                        class="h-2/5"
                        :selected-announcement="selectedAnnouncement2"
                    />
                </div>
                <div class="flex w-6/12 flex-col rounded-lg bg-white p-3 px-4">
                    <Announcements :show-header="false" class-style="d" />
                </div>
            </div>
        </div>
    </div>
    <Modal v-model:view="showAddAnnouncement">
        <AddAnnouncement @success="showAddAnnouncement = false" />
    </Modal>
</template>
<script setup>
import Announcements from "@/Views/Announcements/Index.vue";
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import Title from "@/Views/Teacher/Views/Title.vue";
import SelectedAnnouncementView from "@/Views/Announcements/SelectedAnnouncement.vue";
import TextInput from "@/Components/TextInput.vue";
import { debounce } from "lodash";
import { SquaresPlusIcon } from "@heroicons/vue/20/solid";
import Modal from "@/Components/Modal.vue";
import AddAnnouncement from "@/Views/Announcements/AddAnnouncement.vue";

const props = defineProps({
    url: {
        type: String,
        default: "/admin/announcements",
    },
});

const showAddAnnouncement = ref(false);
const announcements = computed(() => usePage().props.announcements);

const selectedAnnouncement = ref(announcements.value?.data[0]);
const selectedAnnouncement2 = ref(announcements.value?.data[1]);
announcements.value?.data.splice(0, 2);

// watch for changes in the announcements and update the selected announcements and splice the first two announcements
watch(announcements, () => {
    selectedAnnouncement.value = announcements.value?.data[0];
    selectedAnnouncement2.value = announcements.value?.data[1];
    announcements.value?.data.splice(0, 2);
});

const searchKey = ref(usePage().props.searchKey);
const search = debounce(() => {
    router.get(
        props.url,
        { search: searchKey.value },
        { preserveState: true, replace: true }
    );
}, 300);

const schoolYears = computed(() => usePage().props.school_years);
</script>
<style scoped></style>
