<template>
    <div class="flex cursor-pointer flex-col">
        <label v-if="!!label && labelLocation === 'top'" class="">
            <span class="pl-0.5 text-sm font-semibold text-gray-700">{{
                label
            }}</span>
            <span v-if="required" class="pl-0.5 text-xs text-red-600">*</span>
        </label>
        <div
            :class="[labelLocation === 'inside' ? 'h-12' : 'h-9', rounded]"
            class="relative flex w-full border border-gray-200 bg-white"
            tabindex="0"
        >
            <div
                class="flex h-full w-full"
                :class="[disabled ? 'opacity-25' : 'opacity-100']"
                @click="toggleList"
            >
                <span class="flex grow flex-col justify-center px-3">
                    <span
                        v-if="labelLocation === 'inside'"
                        class="text-brand-text-600 text-[0.7rem]"
                        >{{ label }}</span
                    >
                    <span
                        :aria-placeholder="placeholder"
                        :class="[
                            !!selectedLabel ? 'text-black' : 'text-gray-500',
                        ]"
                        class="flex text-xs capitalize 2xl:text-sm"
                        >{{ selectedLabel ?? placeholder }}
                    </span>
                </span>
                <div
                    class="absolute right-0 grid h-full place-items-center px-2"
                >
                    <ChevronDownIcon class="h-4 w-4 stroke-gray-500 stroke-2" />
                </div>
            </div>

            <input :disabled="disabled" :required="required" type="hidden" />
            <ul
                v-if="displayList"
                ref="list"
                :class="[
                    disabled ? 'hidden' : 'opacit',
                    direction === 'down'
                        ? 'bottom-0 -mb-1 translate-y-full'
                        : 'top-0 -mt-1 -translate-y-full',
                ]"
                class="scrollbar-hide absolute left-0 z-50 max-h-[500px] min-h-10 w-full overflow-y-scroll rounded-md border bg-white drop-shadow"
            >
                <template v-for="(option, index) in options" :key="index">
                    <li
                        class="flex items-center justify-between px-3 py-2"
                        @click="selectOption(option)"
                    >
                        <span class="text-xs 2xl:text-sm">{{
                            option.label
                        }}</span>
                        <CheckCircleIcon
                            v-if="option.value === modelValue"
                            class="h-4 w-4"
                        />
                    </li>
                </template>
            </ul>
        </div>
        <span v-if="error" class="text-xs text-negative-50">
            * {{ error }}
        </span>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { onClickOutside } from "@vueuse/core";
import { ChevronDownIcon } from "@heroicons/vue/24/outline";
import { CheckCircleIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    label: {
        type: String,
        default: null,
    },
    labelLocation: {
        type: String,
        default: "top",
    },
    placeholder: {
        type: String,
        default: "Select an option",
    },
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    options: {
        type: Array, // Array of object type {value: string, label: string}
        required: true,
    },
    modelValue: {
        type: [String, Number],
        required: true,
    },
    direction: {
        type: String,
        default: "down",
    },
    error: {
        type: String,
        default: null,
    },
    rounded: {
        type: String,
        default: "rounded-md",
    },
});

const emits = defineEmits(["update:modelValue"]);

const displayList = ref(false);
const selectedLabel = computed(
    () =>
        props.options.find((option) => option.value === props.modelValue)
            ?.label ?? null
);
const list = ref(null);

function selectOption(option) {
    displayList.value = false;
    emits("update:modelValue", option.value);
    selectedLabel.value = option.label;
}

function toggleList() {
    if (!displayList.value) {
        displayList.value = true;
    }
}

onClickOutside(list, () => {
    displayList.value = false;
});
</script>

<style scoped></style>
