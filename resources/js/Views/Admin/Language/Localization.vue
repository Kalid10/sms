<template>
    <div>
        <button @click="changeLocale">
            {{ currentLocale }}
        </button>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    locale: {
        type: String,
        required: true,
    },
});

const currentLocale = ref("en");

const selectableLocale = computed(() => {
    return currentLocale.value === props.locale ? "en" : props.locale;
});

const changeLocale = () => {
    const newLocale = selectableLocale.value;
    router.post(
        "/set-locale/" + newLocale,
        {},
        {
            preserveState: true,
            onSuccess: () => {
                currentLocale.value = newLocale;
            },
        }
    );
};
</script>
