<template>
    <div
        ref="parentDiv"
        class="max-h-screen bg-zinc-800 text-white"
        :class="[
            isOpen
                ? 'min-w-[16rem] lg:w-80 lg:min-w-0 2xl:w-96'
                : 'min-w-8 lg:w-16',
            'transition-all duration-300 ease-in-out ',
        ]"
        @click="toggleSidebar"
        @mouseover="handleMouseover"
        @mouseleave="handleMouseleave"
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
import { computed, ref } from "vue";
import Header from "@/Views/Admin/SideBar/Header.vue";
import Items from "@/Views/Admin/SideBar/Items.vue";
import Footer from "@/Views/Admin/SideBar/Footer.vue";
import { useSidebarStore } from "@/Store/sidebar";
import { onClickOutside } from "@vueuse/core";

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
    if (window.innerWidth <= 768) {
        sidebarStore.close();
    } else {
        sidebarStore.open();
    }
}

function handleMouseover() {
    if (window.innerWidth > 768) {
        sidebarStore.open();
    }
}

function handleMouseleave() {
    if (window.innerWidth > 768) {
        sidebarStore.close();
    }
}

// Close sidebar when clicking outside of it
const parentDiv = ref(null);
onClickOutside(parentDiv, () => {
    sidebarStore.close();
});
</script>
