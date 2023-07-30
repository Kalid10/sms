<template>
    <label class="flex flex-col gap-1">
        <span v-if="label">
            <span class="pl-0.5 text-xs font-semibold text-black">{{
                label
            }}</span>
            <span v-if="required" class="pl-0.5 text-xs text-red-600">*</span>
        </span>
        <input
            :disabled="disabled"
            :placeholder="placeholder"
            :required="required"
            :type="type"
            :value="modelValue"
            class="h-9 w-full outline-none placeholder:text-xs placeholder:text-gray-400"
            :class="
                disabled
                    ? 'blur-0 cursor-not-allowed rounded-2xl'
                    : classStyle
                    ? classStyle
                    : 'border border-gray-200 text-sm focus:ring-1 focus:ring-zinc-700 focus:border-none focus:outline-none rounded-md '
            "
            @input="$emit('update:modelValue', $event.target.value)"
            @focusin="toggleSubtext ? (showSubText = true) : null"
            @focusout="toggleSubtext ? (showSubText = false) : null"
        />
        <span v-if="subtext && showSubText" class="text-xs text-brand-text-300">
            <component
                :is="descriptionIcon"
                class="inline-block h-4 w-4 stroke-2"
            />
            {{ subtext }}
        </span>
        <span v-if="error" class="text-xs text-negative-50">
            * {{ error }}
        </span>
    </label>
</template>

<script setup>
import { computed, defineAsyncComponent } from "vue";

const props = defineProps({
    label: {
        type: String,
        default: null,
    },
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    placeholder: {
        type: String,
        required: true,
    },
    modelValue: {
        type: [String, Number],
        default: null,
    },
    error: {
        type: String,
        default: null,
    },
    type: {
        type: String,
        default: "text",
    },
    subtext: {
        type: String,
        default: null,
    },
    showSubText: {
        type: Boolean,
        default: true,
    },
    toggleSubtext: {
        type: Boolean,
        default: false,
    },
    classStyle: {
        type: String,
        default: null,
    },
});

defineEmits(["update:modelValue"]);

const descriptionIcon = computed(() => {
    if (props.subtext) {
        return defineAsyncComponent(() =>
            import("@heroicons/vue/24/outline/InformationCircleIcon.js")
        );
    }
    return "span";
});
</script>

<style scoped></style>
