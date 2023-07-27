<template>
    <Modal v-model:view="view" place-items-center>
        <div
            class="flex min-h-10 w-[32rem] animate-scale-up flex-col gap-3 rounded-lg bg-white p-6"
        >
            <div class="flex gap-4">
                <div class="min-w-fit">
                    <div
                        :class="accent || types[type].accent"
                        class="icon-wrapper grid h-10 w-10 place-items-center rounded-full"
                    >
                        <span class="h-4 w-4">
                            <slot name="icon">
                                <component
                                    :is="types[type].icon"
                                    class="icon"
                                />
                            </slot>
                        </span>
                    </div>
                </div>

                <div class="flex grow flex-col gap-0.5">
                    <h3 class="font-medium">
                        <slot name="title">
                            {{ title || types[type].title }}
                        </slot>
                    </h3>
                    <h3 class="text-sm text-brand-text-300">
                        <slot name="description">
                            {{ description || types[type].description }}
                        </slot>
                    </h3>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <slot name="content" />
            </div>

            <div
                :class="accent || types[type].accent"
                class="flex w-full items-center justify-end gap-3"
            >
                <TertiaryButton v-if="abortAction" @click="abort">
                    {{ $t("dialogBox.cancel") }}
                </TertiaryButton>
                <PrimaryButton class="action-button" @click="confirm">
                    <slot name="action">
                        {{ types[type].actionTitle || types[type].title }}
                    </slot>
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import Modal from "@/Components/Modal.vue";
import { computed, defineAsyncComponent } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";

const props = defineProps({
    open: {
        type: Boolean,
        required: true,
    },
    title: {
        type: String,
        default: null,
    },
    description: {
        type: String,
        default: null,
    },
    type: {
        type: String,
        default: "delete",
    },
    accent: {
        type: String,
        default: null,
    },
    abortAction: {
        type: Boolean,
        default: true,
    },
});

const emits = defineEmits(["update:open", "abort", "confirm"]);

function abort() {
    emits("abort");
    emits("update:open", false);
}

function confirm() {
    emits("confirm");
    emits("update:open", false);
}

const view = computed({
    get() {
        return props.open;
    },
    set(value) {
        emits("update:open", value);
    },
});

const types = {
    delete: {
        title: "Delete item",
        description:
            "You are about to permanently delete this item. Are you sure? This action cannot be reversed and the data will be lost forever.",
        actionTitle: "Delete item",
        icon: defineAsyncComponent(() =>
            import("@heroicons/vue/24/outline/TrashIcon.js")
        ),
        accent: "red",
    },
    archive: {
        title: "Archive item",
        description:
            "You are about to archive this item. Are you sure? Do not worry, you can always restore it later.",
        actionTitle: "Archive",
        icon: defineAsyncComponent(() =>
            import("@heroicons/vue/24/outline/ArchiveBoxArrowDownIcon.js")
        ),
        accent: "gray",
    },
    update: {
        title: "Update item",
        description: "You are about to update this item. Are you sure?",
        actionTitle: "Update",
        icon: defineAsyncComponent(() =>
            import("@heroicons/vue/24/outline/ArrowPathIcon.js")
        ),
        accent: "gray",
    },
};
</script>

<style scoped>
.icon {
    @apply stroke-2;
}

.gray.icon-wrapper {
    @apply bg-brand-150;
}

.gray .icon {
    @apply stroke-gray-800;
}

.red.icon-wrapper {
    @apply bg-red-100;
}

.red .icon {
    @apply stroke-red-500;
}

.red .action-button {
    @apply bg-red-600 border-red-600;
}

.gray .action-button {
    @apply bg-black border-black;
}
</style>
