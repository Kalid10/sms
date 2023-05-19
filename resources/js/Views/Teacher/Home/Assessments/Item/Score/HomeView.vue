<template>
    <div class="flex w-full flex-col items-center space-y-2">
        <div
            class="flex w-full flex-col justify-center text-center font-light uppercase lg:flex-row"
        >
            <div class="w-4/12 text-2xl font-bold lg:text-2xl">
                {{ item.maximum_point }}
            </div>
            <div
                class="flex w-3/12 flex-col space-y-0.5 text-[0.6rem] font-light lg:font-basic"
            >
                <div>MAX</div>
                <div>POINTS</div>
            </div>
        </div>
        <div
            class="inline-block text-xs text-neutral-600 underline-offset-1 hover:cursor-pointer hover:text-black hover:underline lg:text-[0.65rem]"
        >
            LessonPlan #{{ item.id }}
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue";

defineProps({
    item: {
        type: Object,
        required: true,
    },
});
</script>
