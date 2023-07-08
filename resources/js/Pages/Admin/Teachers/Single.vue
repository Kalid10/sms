<template>
    <div class="flex min-h-screen w-full flex-col pl-5">
        <Title
            class="w-full rounded-t-md bg-gradient-to-r from-violet-600 to-purple-700 px-3 !py-6 text-white shadow-sm"
            :title="teacher.user.name"
        />
        <TabElement
            v-model:active="activeTab"
            background-color="bg-gradient-to-r from-violet-600 to-purple-700"
            in-active-tab-text="text-gray-300"
            :tabs="tabs"
        >
            <template #[homeTab]>
                <Home
                    v-if="activeTab === homeTab && !showLoading"
                    class="bg-white py-3"
                />
            </template>

            <template #[classesTab]>
                <Batches
                    v-if="activeTab === classesTab && !showLoading"
                    class="bg-white p-4"
                />
            </template>

            <template #[studentsTab]>
                <Students
                    v-if="activeTab === studentsTab && !showLoading"
                    class="bg-white"
                />
            </template>

            <template #[lessonPlans]>
                <LessonPlans
                    v-if="activeTab === lessonPlans && !showLoading"
                />
            </template>

            <template #[assessmentsTab]>
                <Assessments
                    v-if="activeTab === assessmentsTab && !showLoading"
                    class="bg-white"
                    :teacher-id="teacher.id"
                />
            </template>

            <template #[homeroomTab]>
                <Homeroom
                    v-if="activeTab === homeroomTab && !showLoading"
                    class="bg-white"
                />
            </template>

            <template #[announcementsTab]>
                <Announcement
                    v-if="activeTab === announcementsTab && !showLoading"
                    class="bg-white"
                />
            </template>
        </TabElement>
        <Loading v-if="showLoading" is-full-screen />
    </div>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import { computed, onMounted, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import TabElement from "@/Components/TabElement.vue";
import Home from "@/Views/Teacher/Index.vue";
import Students from "@/Views/Teacher/Students.vue";
import Assessments from "@/Views/Teacher/Assessments/Index.vue";
import LessonPlans from "@/Views/Teacher/LessonPlans/Index.vue";
import Batches from "@/Views/Teacher/Batches.vue";
import Homeroom from "@/Views/Teacher/Homeroom.vue";
import Announcement from "@/Views/Teacher/Announcement/Index.vue";
import Loading from "@/Components/Loading.vue";
import {useI18n} from "vue-i18n";
const {t} = useI18n()
const showLoading = ref(false);
const teacher = computed(() => usePage().props.teacher);
const homeTab = t('common.home');
const classesTab =  t('common.classes');
const studentsTab = t('common.students');
const lessonPlans = t('common.lessonPlans');
const assessmentsTab = t('common.assessments');
const homeroomTab = t('common.homeroom');
const announcementsTab = t('common.announcements');

const activeTab = ref(homeTab);

const location = computed(() => usePage().props.ziggy.location);
watch(location, (location) => {
    checkUrl();
});

onMounted(() => {
    checkUrl();
});
watch(activeTab, (tab) => {
    handleTabClick(tab);
});

const tabs = [
    homeTab,
    classesTab,
    studentsTab,
    lessonPlans,
    assessmentsTab,
    homeroomTab,
    announcementsTab,
];

const handleTabClick = (tab) => {
    switch (tab) {
        case homeTab:
            fetchData(teacher.value.id, tab);
            break;
        case classesTab:
            fetchData("class", tab);
            break;
        case studentsTab:
            fetchData("students", tab);
            break;
        case lessonPlans:
            fetchData("lesson-plan", tab);
            break;
        case assessmentsTab:
            fetchData("assessments", tab);
            break;
        case homeroomTab:
            fetchData("homeroom", tab);
            break;
        case announcementsTab:
            fetchData("announcements", tab);
            break;
        default:
            break;
    }
};

const fetchData = (url, tab) => {
    showLoading.value = true;
    router.get(
        "/admin/teachers/" + url,
        {
            teacher_id: teacher.value.id,
        },
        {
            preserveState: true,
            onSuccess: () => {
                activeTab.value = tab;
            },
            onError: () => {
                showLoading.value = false;
                alert("Something went wrong");
                // TODO: Handle error
            },
            onFinish: () => {
                showLoading.value = false;
            },
        }
    );
};

function checkUrl() {
    const tab = location.value.split("/").pop();

    switch (tab) {
        case "home":
            activeTab.value = homeTab;
            break;
        case "class":
            activeTab.value = classesTab;
            break;
        case "students":
            activeTab.value = studentsTab;
            break;
        case "lesson-plan":
            activeTab.value = lessonPlans;
            break;
        case "assessments":
            activeTab.value = assessmentsTab;
            break;
        case "homeroom":
            activeTab.value = homeroomTab;
            break;
        case "announcements":
            activeTab.value = announcementsTab;
            break;
        default:
            break;
    }
}
</script>
<style scoped></style>
