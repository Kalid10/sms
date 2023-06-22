<template>
    <div class="flex min-h-screen w-11/12 flex-col space-y-6">
        <div class="p-3">
            <UsersStatistics />
        </div>

        <div class="flex w-full flex-col gap-4 lg:flex-row lg:justify-between">
            <div class="h-fit w-full lg:w-7/12">
                <UsersTable />
            </div>
            <div class="flex h-fit w-full flex-col space-y-4 lg:w-4/12">
                <div class="flex w-full flex-col justify-evenly space-y-4">
                    <SummaryItem
                        class-style="bg-orange-100 text-black"
                        icon-style="bg-orange-500/20 text-white"
                        title="Admins"
                        value="Sign up administrators with ease"
                        :icon="ClipboardIcon"
                        url="/register/admin"
                    />
                    <SummaryItem
                        class-style="bg-zinc-100 text-black"
                        icon-style="bg-zinc-500/20 text-white"
                        title="Students"
                        value="Register Students With there Guardians"
                        :icon="UsersIcon"
                        url="/register/student"
                    />
                    <SummaryItem
                        class-style="bg-fuchsia-100 text-black"
                        icon-style="bg-fuchsia-500/20 text-white"
                        title="Teachers"
                        value="Register Students With there Guardians"
                        :icon="CalendarIcon"
                        url="/register/teacher"
                    />
                </div>
                <RecentActivities />
            </div>
        </div>
    </div>
</template>

<script setup>
import UsersStatistics from "@/Views/Admin/UsersStatistics.vue";
import UsersTable from "@/Views/Admin/Users/UsersTable.vue";
import {
    CalendarIcon,
    ClipboardIcon,
    UsersIcon,
} from "@heroicons/vue/24/solid";
import SummaryItem from "@/Views/Teacher/Views/SummaryItem.vue";
import RecentActivities from "@/Views/Admin/Users/RecentActivities.vue";
</script>

<style scoped></style>
