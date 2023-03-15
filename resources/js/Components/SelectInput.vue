<template>
    <div class="flex flex-col">
        <label class="">
            <span class="pl-2 text-sm font-medium text-gray-500">{{ label }}</span>
            <span v-if="required" class="pl-1 text-red-600">*</span>
        </label>
        <div class="relative flex h-10 w-full rounded-md border border-gray-200" @click="toggleList">
            <span
                :aria-placeholder="placeholder"
                :class="[ !! selectedLabel ? 'text-black' : 'text-gray-500' ]"
                class="grid h-full place-items-center px-3 text-sm">{{
                    selectedLabel ?? placeholder
                }}</span>
            <div class="absolute right-0 grid h-full place-items-center px-2">
                <ChevronDownIcon class="h-4 w-4 stroke-[3] text-gray-500"/>
            </div>

            <input :disabled="disabled" :required="required" type="hidden"/>
            <ul
                v-if="displayList"
                ref="list"
                :class="[ direction === 'down' ? 'bottom-0 -mb-1 translate-y-full' : 'top-0 -mt-1 -translate-y-full' ]"
                class="absolute left-0 min-h-10 w-full rounded-md border bg-white drop-shadow"
            >
                <template v-for="(option, index) in options" :key="index">
                    <li class="flex items-center justify-between py-2 px-3" @click="selectOption(option)">
                        <span class="text-sm">{{ option.label }}</span>
                        <CheckCircleIcon v-if="option.value === modelValue" class="h-4 w-4"/>
                    </li>
                </template>
            </ul>

        </div>
    </div>
</template>

<script setup>
import {ref} from "vue"
import {onClickOutside} from "@vueuse/core";
import {ChevronDownIcon} from "@heroicons/vue/24/outline"
import {CheckCircleIcon} from "@heroicons/vue/24/solid"

defineProps({
    label: {
        type: String,
        required: true
    },
    placeholder: {
        type: String,
        required: true
    },
    required: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    },
    options: {
        type: Array, // Array of object type {value: string, label: string}
        required: true
    },
    modelValue: {
        type: String,
        required: true,
    },
    direction: {
        type: String,
        default: 'down'
    }
})

const emits = defineEmits(['update:modelValue'])

const displayList = ref(false)
const selectedLabel = ref(null)
const list = ref(null)

function selectOption(option) {
    displayList.value = false
    emits('update:modelValue', option.value)
    selectedLabel.value = option.label
}

function toggleList() {
    if (!displayList.value) {
        displayList.value = true
    }
}

onClickOutside(list, () => {
    displayList.value = false
})
</script>

<style scoped>

</style>
