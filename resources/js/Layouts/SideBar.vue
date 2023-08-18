<template>
    <div
        ref="parentDiv"
        class="z-50 max-h-screen overflow-auto bg-brand-550 text-white"
        :class="[
            isOpen ? 'min-w-[12rem] lg:w-60 lg:min-w-0' : 'min-w-8 lg:w-16',
            'transition-all duration-300 ease-in-out ',
        ]"
        @click="toggleSidebar"
    >
        <div
            class="flex min-h-screen w-full flex-col items-center justify-between pt-1 pb-4 text-white lg:pt-5"
        >
            <div class="flex w-full flex-col items-center justify-center">
                <Header :header="header" />
                <Items :items="sideBarItems" @click.stop />
            </div>

            <div class="flex h-fit w-full flex-col justify-between">
                <div class="flex w-full justify-center py-2">
                    <ChangeLanguage @click.stop />
                </div>
                <Footer
                    :items="footerItems"
                    @show-logout-confirmation="
                        $emit('show-logout-confirmation')
                    "
                />
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
import ChangeLanguage from "@/Components/ChangeLanguage.vue";

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

const emits = defineEmits(["update:open", "show-logout-confirmation"]);

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
    // check and close sidebar if it is open
    if (isOpen.value) {
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

<style scoped>
/* Customize scrollbar styles */
::-webkit-scrollbar {
    width: 1px;
    height: 3px;
    background: white;
}

::-webkit-scrollbar-thumb {
    background-color: #a0aec0;
}
</style>
