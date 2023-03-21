<template>

    <div class="flex flex-col gap-2 md:grid md:grid-cols-2 md:grid-rows-2 lg:flex lg:flex-row">
        <StatisticsCard data="12" subtitle="Admins registered in the system" title="Admins">
            <template #icon>
                <UserCircleIcon/>
            </template>
        </StatisticsCard>
        <StatisticsCard data="12,059" subtitle="Students registered into the school (all-time)" title="Students">
            <template #icon>
                <UserIcon/>
            </template>
        </StatisticsCard>
        <StatisticsCard data="322" subtitle="Teachers registered into the school (all-time)" title="Teachers">
            <template #icon>
                <UsersIcon/>
            </template>
        </StatisticsCard>
        <StatisticsCard data="11" subtitle="New Users (last 30 days)" title="New Users">
            <template #icon>
                <UserPlusIcon/>
            </template>
        </StatisticsCard>
    </div>

</template>

<script setup>
import StatisticsCard from "@/Views/StatisticsCard.vue"
import {UserIcon, UsersIcon, UserPlusIcon, UserCircleIcon} from "@heroicons/vue/24/outline"
</script>

<style scoped>

</style>
