<template>
    <div>
        <label class="pb-5 text-xl font-bold" >{{ label }}</label>
        <div v-for="option in options" :key="option.id">
            <input
                :id="option.id"
                :checked="isChecked(option.value)"
                class="h-3.5 w-3.5 rounded-sm border border-neutral-300 bg-gray-100 text-black ring-offset-2 focus:ring-black"
                type="checkbox"
                @input="onInput(option.value)"
            >
            <label
                class="p-2 text-base " :for="option.id">{{ option.label }}</label>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import Checkbox from "@/Components/Checkbox.vue"
const props = defineProps({
    modelValue: {
        type: Array,
        required: true,
    },
    options: {
        type: Array,
        required: true,
    },
    label: {
        type: String,
        default: 'Grouped Checkboxes',
    },
});

const emit = defineEmits(['update:modelValue']);

const selectedOptions = ref(props.modelValue);

function isChecked(optionValue) {
    return selectedOptions.value.includes(optionValue);
}

function onInput(optionValue) {
    const index = selectedOptions.value.indexOf(optionValue);
    if (index === -1) {
        selectedOptions.value.push(optionValue);
    } else {
        selectedOptions.value.splice(index, 1);
    }
    emit('update:modelValue', selectedOptions.value);
}

watch(
    () => props.modelValue,
    (newValue) => {
        selectedOptions.value = newValue;
    },
);
</script>

<style scoped>

</style>
