<template>
    <div class="inline-flex space-x-1.5 lg:my-1">
        <div
            class="inline-flex items-center space-x-1 rounded-md px-2 py-1"
            :class="classStyle ? classStyle : 'bg-yellow-400'"
        >
            <span class="truncate text-xs font-semibold">
                {{ value }}
            </span>
            <XMarkIcon
                v-if="showRemoveIcon"
                class="hover:w-4.5 w-4 cursor-pointer"
                :class="
                    iconStyle ? iconStyle : 'text-zinc-600 hover:text-black'
                "
                @click="$emit('remove')"
            />
        </div>
    </div>
</template>
<script setup>
import { XMarkIcon } from "@heroicons/vue/20/solid";

defineEmits(["remove"]);
defineProps({
    value: {
        type: String,
        required: true,
    },
    classStyle: {
        type: String,
        default: null,
    },
    iconStyle: {
        type: String,
        default: null,
    },
    showRemoveIcon: {
        type: Boolean,
        default: true,
    },
});
</script>

<style scoped></style>
