<template>
    <div class="flex cursor-pointer flex-col">
        <label v-if="!!label && labelLocation === 'top'" class="">
            <span class="pl-0.5 text-sm font-semibold text-gray-500">{{
                label
            }}</span>
            <span v-if="required" class="pl-0.5 text-xs text-red-600">*</span>
        </label>
        <div
            :class="[labelLocation === 'inside' ? 'h-12' : 'h-10']"
            class="relative flex w-full rounded-md border border-gray-200 bg-white"
            tabindex="0"
        >
            <div class="flex h-full w-full" @click="toggleList">
                <span class="flex grow flex-col justify-center px-3">
                    <span
                        v-if="labelLocation === 'inside'"
                        class="text-[0.7rem] text-gray-500"
                        >{{ label }}</span
                    >
                    <span
                        :aria-placeholder="placeholder"
                        :class="[
                            !!selectedLabel ? 'text-black' : 'text-gray-500',
                        ]"
                        class="flex text-xs capitalize lg:text-sm"
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
                    direction === 'down'
                        ? 'bottom-0 -mb-1 translate-y-full'
                        : 'top-0 -mt-1 -translate-y-full',
                ]"
                class="absolute left-0 z-50 min-h-10 w-full rounded-md border bg-white drop-shadow"
            >
                <template v-for="(option, index) in options" :key="index">
                    <li
                        class="flex items-center justify-between py-2 px-3"
                        @click="selectOption(option)"
                    >
                        <span class="text-xs lg:text-sm">{{
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
        required: true,
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
