<template>
    <div
        class="scrollbar-hide mt-1 flex w-full flex-col justify-evenly overflow-y-scroll lg:mt-7 2xl:mt-10"
        :class="isOpen ? 'lg:space-y-1 space-y-3' : 'space-y-3'"
    >
        <Link
            v-for="(item, index) in items"
            :key="index"
            :href="`${item.route}`"
            class="flex h-12 w-full items-center justify-between lg:h-12 2xl:h-12"
            :class="[
                item.active
                    ? 'rounded-lg bg-brand-500 font-medium'
                    : 'font-normal',
                'transition-all duration-300 ease-in-out ',
            ]"
            @mouseenter="handleMouseEnter($event, index)"
            @mouseleave="handleMouseLeave()"
        >
            <div
                class="flex h-full w-full cursor-pointer items-center justify-center hover:bg-brand-500 hover:transition-all hover:duration-300 hover:ease-out"
            >
                <div
                    class="flex h-20 w-11/12 items-center text-center hover:cursor-pointer"
                    :class="isOpen ? 'justify-evenly' : ' justify-center'"
                >
                    <div class="flex items-center justify-center">
                        <component
                            :is="item.icon"
                            :class="isOpen ? 'lg:h-4' : 'lg:h-5'"
                        />
                    </div>
                    <Toast
                        v-if="!isOpen"
                        :show-toast="showToast"
                        class="!bg-purple-500 !text-white"
                        :event="toastEvent"
                        :value="toastValue"
                        :class-style="'ml-40'"
                        :show-icon="false"
                    />
                    <div
                        class="flex w-8/12 items-center justify-center whitespace-nowrap text-xs lg:text-sm 2xl:text-sm"
                        :class="{
                            hidden: !isOpen,
                            'lg:inline': isOpen,
                        }"
                    >
                        {{ item.name }}
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
import { computed, ref } from "vue";
import { useSidebarStore } from "@/Store/sidebar";
import Toast from "@/Components/Toast.vue";

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
});

const isOpen = computed(() => useSidebarStore().isOpen);

const showToast = ref(false);
const toastEvent = ref(null);
const hoverIndex = ref(null);
const toastValue = ref(null);
const handleMouseEnter = (event, index) => {
    toastEvent.value = event;
    hoverIndex.value = index;
    toastValue.value = props.items[index].name;
    showToast.value = true;
};

const handleMouseLeave = () => {
    showToast.value = false;
    toastEvent.value = null;
};
</script>
<style scoped></style>
