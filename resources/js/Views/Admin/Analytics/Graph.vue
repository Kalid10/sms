<template>
    <h3 class="mx-2 text-sm text-brand-text-250">
        Graphical Reports based on statistics and student information.
    </h3>
    <div class="mx-auto my-4 flex items-center rounded-xl px-6">
        <div class="flex-1">
            <svg
                class="h-32 w-full border-b-2 border-gray-200 py-3"
                :viewBox="`0 -10 ${data.length * 100} 120`"
            >
                <g transform="scale(1,-1) ">
                    <rect
                        v-for="(item, index) in data"
                        :key="index"
                        :x="index * 100"
                        y="-100"
                        width="50"
                        :height="item"
                        :class="getColor(item)"
                    />
                    <text
                        v-for="(item, index) in data"
                        :key="`label-${index}`"
                        :x="index * 100 + 40"
                        y="-110"
                        text-anchor="middle"
                        transform="scale(1,-1)"
                        class="text-xs"
                    >
                        Data {{ index + 1 }}
                    </text>
                    <text
                        v-for="(item, index) in data"
                        :key="`value-${index}`"
                        :x="index * 100 + 40"
                        y="5"
                        text-anchor="middle"
                        transform="scale(1,-1)"
                        class="text-xs"
                    >
                        {{ item }} %
                    </text>
                </g>
            </svg>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const data = ref([10, 50, 30, 70, 40]); // your data here

const getColor = (value) => {
    if (value < 30) {
        return "fill-current text-blue-200";
    } else if (value < 60) {
        return "fill-current text-blue-500";
    } else {
        return "fill-current text-blue-800";
    }
};
</script>

<style scoped></style>
