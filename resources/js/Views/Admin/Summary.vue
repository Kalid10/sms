<template>
    <div class="flex w-11/12 flex-col space-y-6">
        <SummaryItem
            class-style="bg-sky-100 text-black"
            icon-style="bg-sky-500/20 text-white"
            :title="$t('common.chat')"
            :value="$t('adminSummary.chatValue')"
            :icon="PaperAirplaneIcon"
            url="/admin/chat"
        />
        <SummaryItem
            class-style="bg-fuchsia-100 text-black"
            icon-style="bg-fuchsia-500/20 text-white"
            :title="$t('common.students')"
            :value="$t('adminSummary.studentValue')"
            :icon="UserGroupIcon"
            url="/admin/students"
        />
        <SummaryItem
            class-style="bg-orange-100 text-black"
            icon-style="bg-orange-500/20 text-white"
            :title="$t('common.teachers')"
            :value="$t('adminSummary.teacherValue')"
            :icon="UserCircleIcon"
            url="/admin/teachers"
        />

        <SummaryItem
            class-style="bg-zinc-100 text-black"
            icon-style="bg-zinc-500/20 text-white"
            :title="$t('common.admins')"
            :value="$t('adminSummary.adminValue')"
            :icon="UsersIcon"
            url="/admin/admins "
        />
    </div>
</template>
<script setup>
import {
    PaperAirplaneIcon,
    UserCircleIcon,
    UserGroupIcon,
    UsersIcon,
} from "@heroicons/vue/24/solid";
import SummaryItem from "@/Views/Teacher/Views/SummaryItem.vue";
</script>

<style scoped></style>
