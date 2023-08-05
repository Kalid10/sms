<template>
    <div
        class="flex w-full flex-col items-center space-y-5 text-center text-white lg:space-y-6 2xl:space-y-10"
    >
        <div
            v-for="(item, index) in items"
            :key="index"
            class="relative flex w-4/5 cursor-pointer items-center lg:w-11/12"
            :class="isOpen ? 'justify-evenly' : 'justify-center'"
            @click.stop="handleClick(item)"
        >
            <div class="flex items-center justify-center">
                <component
                    :is="item.icon"
                    :class="isOpen ? 'lg:h-4' : 'lg:h-5'"
                />
            </div>
            <div
                class="flex w-8/12 items-center justify-center whitespace-nowrap text-xs lg:text-sm 2xl:text-sm"
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
import { router } from "@inertiajs/vue3";

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
});

const isOpen = computed(() => useSidebarStore().isOpen);

function handleClick(item) {
    if (!item.method) return router.get(item.route);

    if (item.method === "POST") return router.post(item.route);
}
</script>
<style scoped></style>
