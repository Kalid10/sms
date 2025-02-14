<template>
    <label class="flex flex-col gap-1">
        <span v-if="label" class="">
            <span class="pl-0.5 text-xs font-semibold text-brand-text-300">{{
                label
            }}</span>
            <span v-if="required" class="pl-0.5 text-xs text-red-600">*</span>
        </span>
        <input
            type="time"
            :disabled="disabled"
            :placeholder="placeholder"
            :required="required"
            :value="modelValue"
            class="h-10 w-full rounded-md border border-gray-200 placeholder:text-sm placeholder:text-brand-text-300"
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
            *{{ error }}
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
