<template>
    <div
        class="group flex w-full cursor-pointer flex-col justify-evenly space-y-2 rounded-lg border border-black p-3 text-center shadow-sm hover:border-zinc-700 hover:bg-purple-600 hover:text-white lg:w-1/2"
        :class="selected ? 'bg-purple-600 text-white' : ''"
        @click="handleClick"
    >
        <span class="font-medium">{{ title }}</span>
        <p
            class="text-xs"
            :class="
                selected
                    ? 'text-gray-100'
                    : 'text-gray-600 group-hover:text-gray-200'
            "
        >
            {{ description }}
        </p>
    </div>
</template>
<script setup>
import { computed } from "vue";

const emit = defineEmits(["click"]);

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
    source: {
        type: String,
        required: true,
    },
    selectedSource: {
        type: String,
        required: true,
    },
});

const selected = computed(() => props.source === props.selectedSource);

const handleClick = () => {
    emit("click", props.source);
};
</script>

<style scoped></style>
