<template>
    <div class="flex min-h-screen w-full flex-col bg-gray-50/40 px-10 py-5">
        <div class="flex w-full flex-col items-center space-y-2 px-5 py-3">
            <div class="flex w-full items-center justify-between pr-5">
                <Title class="w-5/12" title="Announcements" />
                <TextInput
                    v-model="searchKey"
                    placeholder="Search Announcements"
                    class="w-5/12"
                    @keyup="search"
                />
            </div>

            <div class="flex w-full justify-end">
                <SecondaryButton
                    title="Filter"
                    class="!rounded-2xl bg-zinc-800 text-white"
                    @click="showFilter = true"
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
                <div class="w-6/12">
                    <Announcements :show-header="false" class-style="d" />
                </div>
            </div>
        </div>
    </div>

    <Filters
        v-if="showFilter"
        :school-years="schoolYears"
        @filter="applyFilters"
    />
</template>
<script setup>
import Announcements from "@/Views/Announcements/Index.vue";
import { computed, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import Title from "@/Views/Teacher/Views/Title.vue";
import SelectedAnnouncementView from "@/Views/Announcements/SelectedAnnouncement.vue";
import TextInput from "@/Components/TextInput.vue";
import Filters from "@/Views/Filters.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { debounce } from "lodash";

const props = defineProps({
    url: {
        type: String,
        default: "/admin/announcements",
    },
});

const showFilter = ref(false);

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
        },
        preserveState: true,
    });
}
</script>
<style scoped></style>
