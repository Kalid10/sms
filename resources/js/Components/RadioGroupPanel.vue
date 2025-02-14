<template>
    <div class="flex flex-col gap-1">
        <span v-if="!!label">
            <span class="text-brand-text-600 pl-0.5 text-sm font-semibold">{{
                label
            }}</span>
            <span v-if="required" class="pl-0.5 text-xs text-red-600">*</span>
        </span>

        <fieldset class="flex w-full flex-col">
            <label
                v-for="(option, index) in options"
                :key="index"
                :class="{
                    'border-b border-brand-150 bg-brand-50  [&+label]:border-t-0':
                        modelValue === option.value,
                }"
                :for="option.id"
                class="flex cursor-pointer items-start justify-start gap-3 border-x border-t p-3 first:rounded-t-md last:rounded-b-md last:border-b hover:bg-brand-100"
            >
                <span class="mt-0.5 flex items-baseline gap-2">
                    <input
                        :id="option.id"
                        :checked="option.value === modelValue"
                        :name="name"
                        :value="option.value"
                        class="h-3 w-3 border-neutral-300 bg-brand-100 pt-1.5 text-brand-450 checked:outline-0 focus:border-0 focus:outline-0 focus:ring-0"
                        type="radio"
                        @change="changeSelection"
                    />
                </span>
                <span class="flex flex-col gap-0.5">
                    <span
                        :class="{ '': modelValue === option.value }"
                        class="text-sm font-semibold"
                        >{{ option.label }}</span
                    >
                    <span
                        :class="{ '': modelValue === option.value }"
                        class="text-brand-text-600 text-sm"
                        >{{ option.description }}</span
                    >
                </span>
            </label>
        </fieldset>
    </div>
</template>

<script setup>
defineProps({
    label: {
        type: String,
        default: null,
    },
    required: {
        type: Boolean,
        default: true,
    },
    modelValue: {
        type: [String, Number],
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
    options: {
        type: Array, // Array of object type { id: int, value: string, label: string, description: string }
        required: true,
    },
});

const emits = defineEmits(["update:modelValue"]);

function changeSelection(event) {
    emits("update:modelValue", event.target.value);
}
</script>

<style scoped></style>
