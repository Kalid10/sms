<template>

    <div class="flex h-full flex-col items-center justify-center gap-4">

        <BellAlertIcon class="stroke h-7 w-7 stroke-gray-500" />

        <h3 class="font-semibold">{{ $t('batchScheduler.header') }}</h3>

        <p class="flex max-w-lg flex-col gap-3 p-8 text-center text-sm text-gray-500">
            <span>
                {{ $t('batchScheduler.generateScheduleMessage') }}
            </span>

            <span>
                {{ $t('batchScheduler.descriptionOne') }}
            </span>

            <span>
                {{ $t('batchScheduler.descriptionTwo') }}
            </span>

            <span>
                {{ $t('batchScheduler.descriptionThree') }}
            </span>

            <span>
                {{ $t('batchScheduler.descriptionFour')}}
            </span>

        </p>

        <PrimaryButton @click="generateSchedule"> {{ $t('batchScheduler.runTheScheduler') }}</PrimaryButton>

    </div>

</template>

<script setup>
import {BellAlertIcon} from "@heroicons/vue/24/outline/index.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {router} from "@inertiajs/vue3";

function generateSchedule() {
    router.post('/batch-schedules/create', {}, {
        onSuccess: () => {
            router.get("/getting-started/school-schedule");
        },
    })
}

</script>

<style scoped>

</style>
