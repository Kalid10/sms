<template>
    <div class="flex w-full flex-col space-y-5 rounded-lg bg-white p-5">
        <div class="w-fit bg-red-400 px-3 text-center text-2xl font-semibold uppercase text-white">
            <FlagIcon class="w-5 text-white" />
          {{ $t('flagInfo.understandingStudentFlags')}}
        </div>

        <div class="flex flex-col space-y-4 px-4 py-3 text-sm text-gray-700">
            <p>
                {{ $t('flagInfo.descriptionOne')}}
            </p>
            <p>
                {{ $t('flagInfo.descriptionTwo')}}
            </p>
            <p>
                {{ $t('flagInfo.descriptionThree')}}
            </p>
            <p>
                {{ $t('flagInfo.descriptionFour')}}
            </p>
        </div>
    </div>
</template>
<script setup>
import { FlagIcon } from "@heroicons/vue/20/solid";
</script>

<style scoped></style>
