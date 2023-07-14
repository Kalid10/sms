<template>
    <transition name="fade">
        <div
            v-if="show"
            class="flex w-24 items-center gap-1 rounded-lg bg-brand-150 px-2 text-brand-text-300 shadow transition-all duration-300 ease-out"
        >
            <div
                class="inline-flex h-8 w-5 shrink-0 items-center justify-center rounded-lg"
            >
                <DocumentDuplicateIcon class="w-5" />
            </div>
            <div class="text-sm font-normal">Copied!</div>
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
});

watch(
    () => props.showToast,
    () => {
        if (props.showToast) {
            show.value = true;
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
