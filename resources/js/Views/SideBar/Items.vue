<template>
    <!-- Main Items -->
    <div
        class="mt-1 flex w-full flex-col justify-evenly lg:mt-7 lg:space-y-1 2xl:mt-10"
    >
        <Link
            v-for="(item, index) in items"
            :key="index"
            :href="`${item.route}`"
            class="flex h-14 w-full items-center justify-between lg:h-16 2xl:h-16"
            :class="[
                item.active
                    ? 'rounded-lg bg-zinc-700 font-medium'
                    : 'font-normal',
                'transition-all duration-300 ease-in-out ',
            ]"
        >
            <div
                class="flex h-full w-full cursor-pointer items-center justify-center hover:bg-zinc-700 hover:transition-all hover:duration-300 hover:ease-out"
            >
                <div
                    class="relative flex w-10/12 items-center text-center hover:cursor-pointer lg:w-2/3"
                >
                    <div
                        class="relative flex w-full items-center"
                        :class="isOpen ? 'justify-between' : 'justify-center'"
                    >
                        <component :is="item.icon" class="h-4 lg:h-6" />
                        <div
                            class="absolute inset-x-2 whitespace-nowrap text-xs lg:text-sm 2xl:text-base"
                            :class="{
                                hidden: !isOpen,
                                'lg:inline': isOpen,
                            }"
                        >
                            {{ item.name }}
                        </div>
                    </div>
                </div>
            </div>
            <div
                :class="
                    item.active
                        ? 'h-3/5 lg:h-full w-1 lg:w-2 bg-neutral-50 pr-0.5 rounded-l-md'
                        : ''
                "
            ></div>
        </Link>
    </div>
</template>
<script setup>
import { Link } from "@inertiajs/vue3";
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
