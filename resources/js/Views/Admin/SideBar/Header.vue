<template>
    <div
        v-if="header && isOpen"
        class="flex w-full flex-col items-center justify-center space-x-4 space-y-2 px-1 py-2 lg:space-y-1"
    >
        <div class="h-full w-fit lg:w-full">
            <img
                :src="
                    auth?.user?.profile_image ??
                    'https://avatars.dicebear.com/api/open-peeps/' +
                        auth.user.name +
                        '.svg'
                "
                alt="avatar"
                class="mx-auto h-16 w-16 rounded-full border-2 border-brand-300 object-cover"
            />
        </div>
        <div
            class="flex w-11/12 flex-col items-center justify-center space-y-2 text-xs lg:text-xs"
        >
            <Heading class="text-center !text-xs lg:text-base"
                >{{ header.user.name }}
            </Heading>
            <div
                class="flex w-full items-center justify-center space-x-2 lg:space-x-1.5"
            >
                {{ header.user.email }}
            </div>
            <div
                class="flex w-full items-center justify-center space-x-2 text-center lg:space-x-1.5"
            >
                <span> 0943104396</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import { useSidebarStore } from "@/Store/sidebar";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    header: {
        type: Object,
        required: true,
    },
});

const isOpen = computed(() => useSidebarStore().isOpen);
const auth = computed(() => usePage().props.auth);
</script>
<style scoped></style>
