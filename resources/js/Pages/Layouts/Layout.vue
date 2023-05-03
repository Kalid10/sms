<template>
    <div id="top-view"></div>

    <div class="relative flex h-screen w-full flex-col">
        <Header @open-drawer="drawerVisible = true"/>
        <div
            class="hide-scrollbar w-full grow overflow-y-auto bg-white p-2 md:p-2"
            :class="teacherLessonPlanRoute ? 'w-full' : 'container mx-auto flex flex-col gap-12'"
        >
            <slot/>
        </div>
        <Notification/>
    </div>
</template>

<script setup>
import {computed, ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import Header from "@/Views/Header.vue";
import Notification from "@/Components/Notification.vue";

const drawerVisible = ref(false);
const page = usePage();

const teacherLessonPlanRoute = computed(() =>
    page.url.startsWith("/teacher/lesson-plan")
);
</script>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
    display: none; /* for Chrome, Safari, and Opera */
}

.hide-scrollbar {
    -ms-overflow-style: none; /* for Internet Explorer and Edge */
    scrollbar-width: none; /* for Firefox */
}
</style>
