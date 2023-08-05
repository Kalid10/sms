<template>
    <transition name="fade">
        <div
            v-if="show"
            class="fixed flex w-fit items-center rounded-lg bg-brand-150 px-2 text-brand-text-300 shadow transition-all duration-300 ease-out"
            :class="!showIcon ? 'px-2 py-1' : ''"
            :style="{
                top: sideBarStyle ? `${y - 30}px` : `${y - 30}px`,
                left: sideBarStyle ? `${x}px` : `${x - 50}px`,
            }"
        >
            <div
                v-show="showIcon"
                class="inline-flex h-8 w-5 shrink-0 items-center justify-center rounded-lg"
            >
                <DocumentDuplicateIcon class="w-3" />
            </div>
            <div class="text-xs font-normal">{{ value }}</div>
        </div>
    </transition>
</template>

<script setup>
import { DocumentDuplicateIcon } from "@heroicons/vue/24/solid";
import { ref, watch } from "vue";

const emit = defineEmits(["copied"]);

const show = ref(false);
const props = defineProps({
    showToast: {
        type: Boolean,
        default: false,
    },
    event: {
        type: Object,
        default: null,
    },
    value: {
        type: String,
        default: "Copied!",
    },
    showIcon: {
        type: Boolean,
        default: true,
    },
    sideBarStyle: {
        type: Boolean,
        default: true,
    },
});

const x = ref(0);
const y = ref(0);
watch(
    () => props.showToast,
    () => {
        if (props.showToast) {
            show.value = true;
            if (props.event) {
                x.value = props.event.clientX;
                y.value = props.event.clientY;
            }
            setTimeout(() => (show.value = false), 3000);
            emit("copied");
        }
    }
);
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>
