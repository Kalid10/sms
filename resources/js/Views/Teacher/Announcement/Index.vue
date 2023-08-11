<template>
    <div
        v-if="announcements?.data?.length || selectedAnnouncement || searchKey"
        class="flex min-h-screen w-full flex-col px-1 py-2 lg:py-5 lg:px-10"
    >
        <div
            class="flex h-full w-full flex-col items-center space-y-2 px-0 py-3 lg:px-5"
        >
            <div
                class="flex w-full flex-col items-center justify-between px-5 lg:flex-row"
            >
                <Title
                    class="w-full lg:w-5/12"
                    :title="$t('announcementsIndex.announcementsTitle')"
                />
                <div
                    v-if="isAdmin()"
                    class="flex w-full flex-col justify-between space-y-3 pr-3 lg:w-6/12 lg:flex-row lg:space-y-0"
                >
                    <TextInput
                        v-model="searchKey"
                        :placeholder="
                            $t('announcementsIndex.searchAnnouncements')
                        "
                        class="w-full !rounded-xl"
                        :class="isAdmin() ? 'lg:w-6/12 xl:w-8/12' : 'lg:w-9/12'"
                        class-style="focus:ring-1 focus:ring-zinc-700 focus:border-none focus:outline-none rounded-2xl"
                    />
                    <div
                        class="flex w-full cursor-pointer items-center justify-center space-x-2 rounded-2xl bg-brand-450 px-4 py-1 text-xs text-white lg:w-fit"
                        @click="showAddAnnouncement = true"
                    >
                        <SquaresPlusIcon class="w-4 text-white" />
                        <span>
                            {{ $t("announcementsIndex.publishAnnouncement") }}
                        </span>
                    </div>
                </div>
            </div>

            <div
                v-if="announcements?.data?.length || selectedAnnouncement"
                class="flex w-full justify-between divide-gray-100 lg:space-x-4 lg:pr-5"
            >
                <div
                    class="hidden p-3 lg:flex"
                    :class="
                        announcements?.data.length
                            ? 'h-full flex-col w-6/12 min-h-[44rem] pr-6 justify-evenly space-y-6 '
                            : 'h-[22rem] flex-row w-full justify-between'
                    "
                >
                    <SelectedAnnouncementView
                        v-if="selectedAnnouncement"
                        class=""
                        :class="
                            announcements?.data.length
                                ? 'w-full h-2/5 !shadow-md'
                                : 'w-5/12'
                        "
                        :selected-announcement="selectedAnnouncement"
                        @continue-reading="setContinueReading"
                    />
                    <SelectedAnnouncementView
                        v-if="selectedAnnouncement2"
                        :class="
                            announcements?.data.length
                                ? 'w-full h-2/5'
                                : 'w-5/12'
                        "
                        :selected-announcement="selectedAnnouncement2"
                        @continue-reading="setContinueReading"
                    />
                </div>
                <div
                    v-if="announcements?.data?.length"
                    class="flex w-full flex-col rounded-lg bg-white p-3 px-4 lg:w-6/12"
                >
                    <Announcements
                        :conitinue-reading="continueReading"
                        :show-header="false"
                        class-style="d"
                    />
                </div>
            </div>
            <div
                v-else
                class="flex h-3/5 w-full flex-col items-center justify-center text-3xl font-semibold text-gray-700"
            >
                <div>
                    Could not find any announcement related to
                    <span class="font-normal italic">
                        ' {{ searchKey }} '
                    </span>
                </div>
            </div>
        </div>
    </div>
    <EmptyView v-else-if="!searchKey && !isLoading" :is-full-screen="true">
        <template #default>
            <p class="text-center text-xl font-semibold">
                Currently, there are no new announcements. We encourage you to
                revisit this space frequently for updates.
            </p>

            <div
                v-if="isAdmin()"
                class="flex w-full cursor-pointer items-center justify-center space-x-2 rounded-2xl bg-brand-450 px-4 py-2 text-xs text-white lg:w-fit"
                @click="showAddAnnouncement = true"
            >
                <SquaresPlusIcon class="w-4 text-white" />
                <span>
                    {{ $t("announcementsIndex.publishAnnouncement") }}
                </span>
            </div>
        </template>
    </EmptyView>

    <Modal v-model:view="showAddAnnouncement">
        <AddAnnouncement @success="showAddAnnouncement = false" />
    </Modal>

    <Modal v-model:view="viewAnnouncement">
        <ShowAnnouncementView :selected-announcement="continueReading" />
    </Modal>

    <Loading v-if="isLoading" :is-full-screen="true" />
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
import { isAdmin } from "@/utils";
import EmptyView from "@/Views/EmptyView.vue";
import Loading from "@/Components/Loading.vue";

const props = defineProps({
    url: {
        type: String,
        default: "/admin/announcements",
    },
});

const showAddAnnouncement = ref(false);
const viewAnnouncement = ref(false);
const continueReading = ref();
const isLoading = ref(false);

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

const searchKey = ref(usePage().props.filters?.searchKey);
const isSearchCleared = ref(false);

watch(searchKey, () => {
    isLoading.value = true;
    search();
});

const search = debounce(() => {
    router.get(
        props.url,
        { search: searchKey.value },
        {
            preserveState: true,
            replace: true,
            onFinish: () => {
                isLoading.value = false;
                isSearchCleared.value = false;
            },
        }
    );
}, 300);

const setContinueReading = (announcement) => {
    viewAnnouncement.value = true;
    continueReading.value = announcement;
};
</script>
<style scoped></style>
