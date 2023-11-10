<template>
    <div class="flex h-3/5 w-11/12 flex-col justify-evenly space-y-3">
        <SummaryItem
            class-style="bg-fuchsia-100 text-black"
            icon-style="bg-fuchsia-500/20 text-white"
            :title="$t('common.students')"
            :value="$t('adminSummary.studentValue')"
            :icon="UsersIcon"
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
            class-style="bg-brand-100 text-black"
            icon-style="bg-brand-300/20 text-white"
            :title="$t('common.users')"
            :value="$t('adminSummary.userValue')"
            :icon="UserGroupIcon"
            url="/admin/users "
        />
    </div>
</template>
<script setup>
import {
    UserCircleIcon,
    UserGroupIcon,
    UsersIcon,
} from "@heroicons/vue/24/solid";
import SummaryItem from "@/Views/Teacher/Views/SummaryItem.vue";
</script>

<style scoped></style>
