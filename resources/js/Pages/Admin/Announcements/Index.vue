<template>
    <div
        class="flex min-h-screen w-full flex-col bg-gray-50 px-1 py-2 lg:py-5 lg:px-10"
    >
        <div
            class="flex w-full flex-col items-center space-y-2 px-0 py-3 lg:px-5"
        >
            <div
                class="flex w-full flex-col items-center justify-between px-5 lg:flex-row"
            >
                <Title
                    class="w-full lg:w-5/12"
                    :title="$t('announcementsIndex.announcementsTitle')"
                />
                <div
                    class="flex w-full flex-col justify-between space-y-3 pr-3 lg:w-6/12 lg:flex-row lg:space-y-0"
                >
                    <TextInput
                        v-model="searchKey"
                        :placeholder="
                            $t('announcementsIndex.searchAnnouncements')
                        "
                        class="w-full !rounded-xl lg:w-6/12"
                        class-style="focus:ring-1 focus:ring-zinc-700 focus:border-none focus:outline-none rounded-2xl"
                        @keyup="search"
                    />
                    <div
                        class="flex w-full cursor-pointer items-center justify-center space-x-2 rounded-2xl bg-zinc-800 px-4 py-1 text-xs text-white lg:w-fit"
                        @click="showAddAnnouncement = true"
                    >
                        <SquaresPlusIcon class="w-4 text-white" />
                        <span> Publish Announcement </span>
                    </div>
                </div>
            </div>

            <div
                class="flex w-full justify-between divide-gray-100 lg:space-x-4 lg:pr-5"
            >
                <div
                    class="hidden h-full w-6/12 flex-col justify-evenly space-y-6 p-3 pr-6 lg:block"
                >
                    <SelectedAnnouncementView
                        class="h-2/5"
                        :selected-announcement="selectedAnnouncement"
                        @continue-reading="setContinueReading"
                    />
                    <SelectedAnnouncementView
                        class="h-2/5"
                        :selected-announcement="selectedAnnouncement2"
                        @continue-reading="setContinueReading"
                    />
                </div>
                <div
                    class="flex w-full flex-col rounded-lg bg-white p-3 px-4 lg:w-6/12"
                >
                    <Announcements
                        :conitinue-reading="continueReading"
                        :show-header="false"
                        class-style="d"
                    />
                </div>
            </div>
        </div>
    </div>

    <Modal v-model:view="showAddAnnouncement">
        <AddAnnouncement @success="showAddAnnouncement = false" />
    </Modal>

    <Modal v-model:view="viewAnnouncement">
        <ShowAnnouncementView :selected-announcement="continueReading" />
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
import ShowAnnouncementView from "@/Views/Announcements/ShowAnnouncement.vue";

const props = defineProps({
    url: {
        type: String,
        default: "/admin/announcements",
    },
});

const showAddAnnouncement = ref(false);
const viewAnnouncement = ref(false);
const continueReading = ref();

const announcements = computed(() => usePage().props.announcements);
const schoolYears = computed(() => usePage().props.school_years);

const selectedAnnouncement = ref(announcements.value?.data[0]);
const selectedAnnouncement2 = ref(announcements.value?.data[1]);

// Splice the latest two announcements
announcements.value?.data.splice(0, 2);

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

const setContinueReading = (announcement) => {
    viewAnnouncement.value = true;
    continueReading.value = announcement;
};
</script>
<style scoped></style>
