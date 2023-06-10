<template>
    <div
        v-if="links.length > 3"
        class="flex w-full"
        :class="paginationPosition"
    >
        <div class="flex flex-wrap items-center space-x-2">
            <template v-for="(link, p) in links" :key="p">
                <Link
                    v-if="link.url !== null"
                    :href="link.url"
                    :preserve-state="preserveState"
                    class="flex h-6 w-6 cursor-pointer flex-wrap items-center justify-center rounded-full text-center text-xs font-light hover:bg-zinc-800 hover:font-semibold hover:text-white"
                    :class="{
                        'bg-black font-semibold text-white': link.active,
                    }"
                >
                    <ChevronLeftIcon
                        v-if="link.label.includes('Previous')"
                        class="w-4"
                    />
                    <ChevronRightIcon
                        v-if="link.label.includes('Next')"
                        class="w-4"
                    />
                    <span
                        v-if="
                            !link.label.includes('Previous') &&
                            !link.label.includes('Next')
                        "
                        >{{ link.label }}</span
                    >
                </Link>
            </template>
        </div>
    </div>
</template>
<script setup>
import { Link } from "@inertiajs/vue3";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/20/solid";
import { computed } from "vue";

const props = defineProps({
    links: {
        type: Array,
        default: null,
    },
    preserveState: {
        type: Boolean,
        default: false,
    },
    position: {
        type: String,
        default: "right",
        validator: (value) => ["left", "center", "right"].includes(value),
    },
});

const paginationPosition = computed(() => {
    switch (props.position) {
        case "left":
            return "justify-start";
        case "center":
            return "justify-center";
        case "right":
            return "justify-end";
        default:
            return "justify-end";
    }
});
</script>
