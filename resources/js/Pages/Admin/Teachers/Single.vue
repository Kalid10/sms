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
            <template #home>
                <Home
                    v-if="activeTab === t('common.home') && !showLoading"
                    class="bg-white py-3"
                />
            </template>

            <template #classes>
                <Batches
                    v-if="activeTab === 'Classes' && !showLoading"
                    class="bg-white p-4"
                />
            </template>

            <template #students>
                <Students
                    v-if="activeTab === 'Students' && !showLoading"
                    class="bg-white"
                />
            </template>

            <template #lessonplans>
                <LessonPlans
                    v-if="activeTab === 'LessonPlans' && !showLoading"
                />
            </template>

            <template #assessments>
                <Assessments
                    v-if="activeTab === 'Assessments' && !showLoading"
                    class="bg-white"
                    :teacher-id="teacher.id"
                />
            </template>

            <template #homerooms>
                <Homeroom
                    v-if="activeTab === 'Homerooms' && !showLoading"
                    class="bg-white"
                />
            </template>

            <template #announcements>
                <Announcement
                    v-if="activeTab === 'Announcements' && !showLoading"
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
const activeTab = ref("Home");

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
    t('common.home'),
    t('common.classes'),
    t('common.students'),
    t('common.lessonPlans'),
    t('common.assessments'),
    t('common.homeroom'),
    t('common.announcements'),
];

const handleTabClick = (tab) => {
    switch (tab) {
        case t('common.home'):
            fetchData(teacher.value.id, tab);
            break;
        case "Classes":
            fetchData("class", tab);
            break;
        case "Students":
            fetchData("students", tab);
            break;
        case "LessonPlans":
            fetchData("lesson-plan", tab);
            break;
        case "Assessments":
            fetchData("assessments", tab);
            break;
        case "Homerooms":
            fetchData("homeroom", tab);
            break;
        case "Announcements":
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
            activeTab.value = "Home";
            break;
        case "class":
            activeTab.value = "Classes";
            break;
        case "students":
            activeTab.value = "Students";
            break;
        case "lesson-plan":
            activeTab.value = "LessonPlans";
            break;
        case "assessments":
            activeTab.value = "Assessments";
            break;
        case "homeroom":
            activeTab.value = "Homerooms";
            break;
        case "announcements":
            activeTab.value = "Announcements";
            break;
        default:
            break;
    }
}
</script>
<style scoped></style>
