<template>
    <div
        class="flex min-h-screen w-full flex-col space-y-8 bg-brand-50 py-2 lg:p-5 lg:px-1 2xl:px-10"
    >
        <div class="flex h-fit w-full flex-col justify-between lg:flex-row">
            <div class="flex w-full flex-col space-y-8 lg:w-7/12">
                <WelcomeHeader />

                <TabElement v-model:active="activeTab" :tabs="tabs">
                    <template #[announcementsTab]>
                        <Announcements
                            url="/admin/announcements"
                            class-style="h-fit w-full space-y-2 rounded-lg bg-white py-2 px-2 shadow-sm"
                        />
                    </template>

                    <template #[flagsTab]>
                        <Flags
                            view="admin"
                            :title="$t('adminIndex.recentFlags')"
                        />
                    </template>
                </TabElement>
            </div>

            <div class="flex w-full flex-col items-center space-y-8 lg:w-4/12">
                <StudentSearch />

                <AbsenteeStats class="w-full lg:w-11/12" />

                <Summary />
            </div>
        </div>
        <SchoolSchedule />
    </div>
</template>
<script setup>
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import WelcomeHeader from "@/Views/WelcomeHeader.vue";
import Announcements from "@/Views/Announcements/Index.vue";
import AbsenteeStats from "@/Views/Admin/Absentee/AbsenteeStats.vue";
import Flags from "@/Views/Flag/Index.vue";
import TabElement from "@/Components/TabElement.vue";
import SchoolSchedule from "@/Views/Admin/SchoolSchedule/Home.vue";
import Summary from "@/Views/Admin/Summary.vue";
import StudentSearch from "@/Views/Admin/StudentSearch.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const announcementsTab = t("common.announcements");
const flagsTab = t("common.flags");
const tabs = [announcementsTab, flagsTab];
const activeTab = ref(announcementsTab);
const schoolYear = computed(() => usePage().props.school_year);
</script>
