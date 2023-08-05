<template>
    <div class="flex w-full flex-col space-y-5 rounded-lg bg-white p-5">
        <div
            class="flex w-full items-center justify-center space-x-2 px-3 text-center text-2xl font-semibold uppercase text-gray-800"
        >
            <FlagIcon class="w-5 text-gray-800" />
            <span>{{ $t("flagInfo.understandingStudentFlags") }}</span>
        </div>
        <div
            class="flex flex-col space-y-4 px-4 py-3 text-sm font-medium text-gray-500"
        >
            <p>
                {{ $t("flagInfo.descriptionOne") }}
            </p>
            <p>
                {{ $t("flagInfo.descriptionTwo") }}
            </p>
            <p>
                {{ $t("flagInfo.descriptionThree") }}
            </p>
            <p>
                {{ $t("flagInfo.descriptionFour") }}
            </p>
        </div>
    </div>
</template>
<script setup>
import { FlagIcon } from "@heroicons/vue/20/solid";
</script>

<style scoped></style>
