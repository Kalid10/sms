<template>
    <div
        class="max-h-screen bg-zinc-800 text-white"
        :class="[
            isOpen
                ? 'min-w-[16rem] lg:w-80 lg:min-w-0 2xl:w-96'
                : 'w-3/12 min-w-0 lg:w-16',
            'transition-all duration-300 ease-in-out ',
        ]"
        @click="toggleSidebar"
    >
        <div
            class="flex min-h-screen w-full flex-col items-center justify-between pt-1 pb-4 text-white lg:pt-5"
        >
            <div class="flex w-full flex-col items-center justify-center">
                <Header :header="header"></Header>
                <Items :items="sideBarItems" />
            </div>

            <div class="flex h-fit w-full flex-col justify-between">
                <Footer :items="footerItems" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import Header from "@/Views/SideBar/Header.vue";
import Items from "@/Views/SideBar/Items.vue";
import Footer from "@/Views/SideBar/Footer.vue";
import { useSidebarStore } from "@/Store/sidebar";

const props = defineProps({
    header: {
        type: Object,
        required: true,
    },
    mainItems: {
        type: Array,
        required: true,
    },
    footerItems: {
        type: Array,
        required: true,
    },
});

const emits = defineEmits(["update:open"]);

const sideBarItems = computed(() => props.mainItems);

const view = computed({
    get() {
        return props.open;
    },
    set(value) {
        emits("update:open", value);
    },
});

const sidebarStore = useSidebarStore();
const isOpen = computed(() => sidebarStore.isOpen);

function toggleSidebar() {
    sidebarStore.toggle();
}
</script>
