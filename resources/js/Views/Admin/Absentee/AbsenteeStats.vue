<template>
    <div
        class="flex flex-col space-y-3 divide-y divide-gray-600 rounded-lg bg-zinc-800 p-3 text-white shadow-sm"
    >
        <div class="flex items-center space-x-2.5 pl-3">
            <UserMinusIcon class="w-6" />
            <div class="text-sm font-medium">{{ $t('absenteeStats.todayAbsentees')}}</div>
        </div>
        <div
            class="flex w-full items-center justify-between divide-x divide-gray-600 pt-4"
        >
            <AbsenteeStatItem class="w-1/3" value="4" :title="$t('common.students')"/>
            <AbsenteeStatItem class="w-1/3" value="2" :title="$t('common.teachers')"/>
            <AbsenteeStatItem class="w-1/3" value="1" :title="$t('common.admins')"/>
        </div>
    </div>
</template>
<script setup>
import AbsenteeStatItem from "@/Views/Admin/Absentee/AbsenteeStatItem.vue";
import { UserMinusIcon } from "@heroicons/vue/24/outline";
</script>
<style scoped></style>
