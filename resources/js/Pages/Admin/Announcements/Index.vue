<template>
    <div class="flex min-h-screen w-full flex-col bg-gray-50/40 px-10 py-5">
        <div class="flex w-full flex-col items-center space-y-2 px-5 py-3">
            <div class="flex w-full items-center justify-between pr-5">
                <Title class="w-5/12" :title="$t('announcementsIndex.announcementsTitle')"/>

                <TextInput
                    v-model="searchKey"
                    :placeholder="$t('announcementsIndex.searchAnnouncements')"
                    class="w-5/12"
                    @keyup="search"
                />
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
                <div class="flex w-6/12 flex-col">
                    <div v-if="hideFilter" class="flex w-full justify-end">
                        <div
                            class="flex w-full cursor-pointer justify-end"
                            @click="handleFilter"
                        >
                            <div
                                class="flex items-center space-x-2 rounded-lg bg-zinc-900 px-3"
                            >
                                <AdjustmentsHorizontalIcon
                                    class="h-8 w-8 fill-white"
                                />

                                <span class="font-semibold text-white"
                                    >{{ $t('announcementsIndex.filterAssessments')}}</span
                                >
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full justify-center shadow-md">
                        <Filters
                            v-if="showFilter"
                            :title="$t('announcementsIndex.filterBySchoolYear')"
                            @filter="applyFilters"
                        />
                    </div>

                    <Announcements :show-header="false" class-style="d" />
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import Announcements from "@/Views/Announcements/Index.vue";
import { computed, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import Title from "@/Views/Teacher/Views/Title.vue";
import SelectedAnnouncementView from "@/Views/Announcements/SelectedAnnouncement.vue";
import TextInput from "@/Components/TextInput.vue";
import Filters from "@/Views/Filters.vue";
import { debounce } from "lodash";
import { AdjustmentsHorizontalIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    url: {
        type: String,
        default: "/admin/announcements",
    },
});

const showFilter = ref(false);

const hideFilter = ref(true);

function handleFilter() {
    showFilter.value = true;
    hideFilter.value = false;
}

const announcements = computed(() => usePage().props.announcements);
const selectedAnnouncement = ref(announcements.value?.data[0]);
const selectedAnnouncement2 = ref(announcements.value?.data[1]);

const searchKey = ref(usePage().props.searchKey);
const search = debounce(() => {
    router.get(
        props.url,
        { search: searchKey.value },
        { preserveState: true, replace: true }
    );
}, 300);

const schoolYears = computed(() => usePage().props.school_years);

function applyFilters(params) {
    router.get("/admin/announcements", params, {
        onSuccess: () => {
            showFilter.value = false;
            hideFilter.value = true;
        },
        preserveState: true,
    });
}
</script>
<style scoped></style>
