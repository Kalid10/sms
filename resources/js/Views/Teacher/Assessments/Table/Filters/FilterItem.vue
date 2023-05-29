<template>
    <div class="flex h-fit w-fit space-x-1.5">
        <div
            class="flex space-x-1 rounded-md px-2 py-1"
            :class="classStyle ? classStyle : 'bg-yellow-400 '"
        >
            <div class="w-fit text-xs font-semibold">
                {{ value }}
            </div>
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
