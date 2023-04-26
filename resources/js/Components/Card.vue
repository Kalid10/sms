<template>

    <div
        class="flex w-full flex-col justify-between gap-3 rounded-md border bg-white p-4 shadow-sm md:w-1/2 md:max-w-xl">

        <div
            v-if="header"
            :class="[ header && !!! title || header && !!! subtitle ? 'items-center' : 'items-start' ]"
            class="flex justify-between"
        >

            <div class="flex flex-col">
                <h3 class="font-semibold">
                    <slot name="title">
                        {{ title }}
                    </slot>
                </h3>
                <h3 class="text-sm text-gray-500">
                    <slot name="subtitle">
                        {{ subtitle }}
                    </slot>
                </h3>
            </div>

            <div v-if="icon" class="stroke-1.5 my-0.5 grid h-6 w-6 place-items-center">
                <slot name="icon">
                    <UsersIcon/>
                </slot>
            </div>

        </div>

        <slot/>

    </div>

</template>

<script setup>
import {computed} from "vue"
import {UsersIcon} from "@heroicons/vue/24/outline"

const header = computed(() => {
    return (!!props.title || !!props.subtitle)
})

const props = defineProps({
    title: {
        type: String,
        default: null
    },
    subtitle: {
        type: String,
        default: null
    },
    icon: {
        type: Boolean,
        default: false
    }
})
</script>

<style scoped>

</style>
