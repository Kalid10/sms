<template>
    <div
        class="flex w-full flex-col items-center space-y-5 text-center text-white lg:space-y-6 2xl:space-y-10"
    >
        <div
            v-for="(item, index) in items"
            :key="index"
            class="relative flex w-4/5 items-center lg:w-2/3"
            :class="isOpen ? 'justify-between' : 'justify-center'"
        >
            <component :is="item.icon" class="h-4 lg:h-5" />
            <div
                class="absolute inset-x-2 text-xs lg:text-sm 2xl:text-base"
                :class="{ hidden: !isOpen, 'lg:inline': isOpen }"
            >
                {{ item.name }}
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed } from "vue";
import { useSidebarStore } from "@/Store/sidebar";

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
});

const isOpen = computed(() => useSidebarStore().isOpen);
</script>
<style scoped></style>
