<template>
    <div
        :class="labelLocation === 'top' ? 'flex-col' : ''"
        class="flex h-10 items-center gap-2"
    >
        <span class="">
            <span class="text-brand-text-600 pl-0.5 text-sm font-semibold">{{
                label
            }}</span>
        </span>
        <label class="relative inline-flex cursor-pointer items-center">
            <input
                :checked="modelValue"
                type="checkbox"
                class="peer sr-only"
                @input="input"
            />
            <span
                class="peer h-6 w-11 rounded-full bg-brand-150 after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-brand-500 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none"
            ></span>
        </label>
    </div>
</template>
<script setup>
defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    label: {
        type: String,
        default: null,
    },
    labelLocation: {
        type: String,
        default: "left",
    },
});
const emits = defineEmits(["update:modelValue"]);

function input(input) {
    emits("update:modelValue", input.target.checked);
}
</script>
