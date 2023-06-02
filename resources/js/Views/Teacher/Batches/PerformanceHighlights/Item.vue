<template>
    <div class="flex h-12 w-11/12 items-center space-x-2">
        <!--                 Image-->
        <div class="h-12 w-2/12">
            <img
                :src="`https://xsgames.co/randomusers/avatar.php?g=male`"
                alt="avatar"
                class="mx-auto h-11 w-11 rounded-full object-contain"
            />
        </div>

        <!--                Name and Progress section-->
        <div class="flex h-11 w-5/12 flex-col justify-evenly text-start">
            <div class="grow text-[0.65rem] font-medium">
                {{ name }}
            </div>

            <div class="flex items-center space-x-3 font-light">
                <div class="flex space-x-0.5">
                    <ArrowTrendingUpIcon
                        v-if="progressType === 'up'"
                        class="w-3 text-positive-100"
                    />
                    <ArrowTrendingDownIcon
                        v-else
                        class="w-3 text-negative-100"
                    />
                    <div class="text-medium grow text-[0.6rem]">
                        {{ progress }}%
                    </div>
                </div>

                <div
                    class="flex items-center justify-center text-center text-[0.6rem]"
                >
                    {{ attendance }} % Att
                </div>
            </div>
        </div>

        <!--                Grade section-->
        <div class="flex h-11 w-3/12 flex-col justify-evenly">
            <div
                class="flex w-full items-center justify-center py-1.5 text-[0.6rem]"
            >
                {{ grade }}
            </div>
            <div class="py-1.5 text-center text-[0.55rem] text-neutral-600">
                Grade
            </div>
        </div>

        <!--                Rank section-->
        <div
            class="flex h-11 w-2/12 items-center justify-center space-y-1 text-white"
        >
            <div
                class="flex w-full justify-center px-1 py-0.5 font-semibold italic shadow-sm"
                :class="progressType === 'up' ? 'bg-emerald-600' : 'bg-red-600'"
            >
                {{ rank }}
            </div>
        </div>
    </div>
</template>
<script setup>
import {
    ArrowTrendingDownIcon,
    ArrowTrendingUpIcon,
} from "@heroicons/vue/24/outline";

defineProps({
    name: {
        type: String,
        required: true,
    },
    progress: {
        type: Number,
        required: true,
    },
    attendance: {
        type: Number,
        required: true,
    },
    grade: {
        type: String,
        required: true,
    },
    rank: {
        type: Number,
        required: true,
    },
    progressType: {
        type: String,
        required: true,
    },
});
</script>
<style scoped></style>
