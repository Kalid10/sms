<template>
    <div
        class="flex flex-col space-y-3 rounded-lg bg-brand-100 p-3 text-black shadow-sm"
    >
        <div class="flex items-center space-x-2.5 pl-3">
            <UserMinusIcon class="w-5" />
            <div class="grow text-sm font-medium">
                {{ $t("absenteeStats.todayAbsentees") }}
            </div>
            <EyeIcon
                class="w-4 cursor-pointer text-black hover:scale-125 hover:text-violet-700"
                @click="absenteeLink"
            />
        </div>
        <div
            class="flex w-full items-center justify-between divide-x divide-brand-500 pt-4"
        >
            <AbsenteeStatItem
                class="w-1/3"
                :value="absenteeRecords"
                :title="$t('common.students')"
            />
            <AbsenteeStatItem
                class="w-1/3"
                :value="teacherAbsenteeRecords"
                :title="$t('common.teachers')"
            />
            <AbsenteeStatItem
                class="w-1/3"
                :value="adminAbsenteeRecords"
                :title="$t('common.admins')"
            />
        </div>
    </div>
</template>
<script setup>
import AbsenteeStatItem from "@/Views/Admin/Absentee/AbsenteeStatItem.vue";
import { EyeIcon, UserMinusIcon } from "@heroicons/vue/24/outline";
import { computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";

const absenteeRecords = computed(() => usePage().props.absentee_records);
const teacherAbsenteeRecords = computed(
    () => usePage().props.teacher_absentee_records
);
const adminAbsenteeRecords = computed(
    () => usePage().props.admin_absentee_records
);

function absenteeLink() {
    router.get("/admin/absentees");
}
</script>
<style scoped></style>
