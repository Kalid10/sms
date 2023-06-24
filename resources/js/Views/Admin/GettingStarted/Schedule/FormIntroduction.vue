<template>

    <div class="flex h-full flex-col items-center justify-center gap-4">

        <BellAlertIcon class="stroke h-7 w-7 stroke-gray-500" />

        <h3 class="font-semibold">
            {{ $t('formIntroduction.header') }}
        </h3>

        <p class="flex max-w-lg flex-col gap-3 p-8 text-center text-sm text-gray-500">
                <span>
            {{ $t('formIntroduction.descriptionOne') }}
                </span>

            <span>
            {{ $t('formIntroduction.descriptionTwo') }}
                </span>

            <span>
            {{ $t('formIntroduction.descriptionThree') }}

                </span>
        </p>

        <PrimaryButton @click="$emit('submit')">
            {{ $t('formIntroduction.getStarted') }}
        </PrimaryButton>

    </div>

</template>

<script setup>
import {BellAlertIcon} from "@heroicons/vue/24/outline/index.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const emits = defineEmits(['submit'])
</script>

<style scoped>

</style>
