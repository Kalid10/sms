<template>
    <div class="flex flex-col gap-2">
        <span class="text-md font-semibold">
            {{ $t("userProfile.changeLanguage") }}
        </span>
        <select class="rounded-lg" @change="changeLanguage($event)">
            <option value="en">English</option>
            <option value="am">Amharic</option>
        </select>
    </div>
</template>

<script setup>
import { useI18n } from "vue-i18n";

const { locale } = useI18n();

const changeLanguage = (event) => {
    locale.value = event.target.value;
};
</script>
