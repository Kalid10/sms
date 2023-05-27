<template>
    <div>
        <button @click="changeLocale">
            {{ currentLocale }}
        </button>
    </div>
</template>

<script setup>
import {computed, ref} from 'vue'
import {router} from '@inertiajs/vue3'

const props = defineProps({
    currentLocale: {
        type: String,
        required: true
    }
})

const currentLocale = ref('en')

const selectable_locale = computed(() => {
    return currentLocale.value === props.currentLocale ? 'en' : props.currentLocale;
})

const changeLocale = () => {
    const newLocale = selectable_locale.value;
    router.post('/set-locale/' + newLocale, {}, {
        preserveState: true,
        onSuccess: () => {
            currentLocale.value = newLocale;
        }
    })
};
</script>
