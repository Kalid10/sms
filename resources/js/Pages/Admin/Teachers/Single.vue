<template>
    <div class="flex min-h-screen w-full flex-col space-y-2 bg-gray-50">
        <Title :title="teacher.user.name" />
        <TabElement
            v-model:active="activeTab"
            :tabs="tabs"
            active-only
            @click="handleTabClick"
        >
            <Home v-if="activeTab === 'Home'" />

            <Batches v-if="activeTab === 'Classes'" />

            <Students v-if="activeTab === 'Students'" />

            <LessonPlans v-if="activeTab === 'LessonPlans'" />

            <Assessments
                v-if="activeTab === 'Assessments'"
                :teacher-id="teacher.id"
            />

            <Homeroom v-if="activeTab === 'Homerooms'" />

            <Announcement v-if="activeTab === 'Announcements'" />
        </TabElement>
        <Loading v-if="showLoading" is-full-screen />
    </div>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import { capitalize, computed, ref, watch } from "vue";
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

const showLoading = ref(false);
const teacher = computed(() => usePage().props.teacher);
const activeTab = ref("Home");
const location = computed(() => usePage().props.ziggy.location);
watch(location, (location) => {
    const tab = location.split("/").pop();
    if (tabs.includes(capitalize(tab))) {
        activeTab.value = capitalize(tab);
    }
});

const tabs = [
    "Home",
    "Classes",
    "Students",
    "LessonPlans",
    "Assessments",
    "Homerooms",
    "Announcements",
];

const handleTabClick = (tab) => {
    switch (tab) {
        case "Home":
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
</script>
<style scoped></style>
