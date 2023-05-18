<template>
    <div class="flex h-12 w-11/12 items-center space-x-2">
        <!--                 Image-->
        <div class="h-12 w-2/12">
            <img
                :src="`https://xsgames.co/randomusers/avatar.php?g=male`"
                alt="avatar"
                class="mx-auto h-full w-10 rounded-md object-contain"
            />
        </div>

        <!--                Name and Progress section-->
        <div class="flex h-11 w-5/12 flex-col justify-evenly text-start">
            <div class="grow text-xs">
                {{ name }}
            </div>

            <div class="flex items-center space-x-3">
                <div class="flex space-x-0.5">
                    <ArrowTrendingUpIcon
                        v-if="progressType === 'up'"
                        class="w-4 text-positive-100"
                    />
                    <ArrowTrendingDownIcon
                        v-else
                        class="w-4 text-negative-100"
                    />
                    <div class="text-medium grow text-[0.7rem]">
                        {{ progress }}%
                    </div>
                </div>

                <div
                    class="flex items-center justify-center text-center text-[0.65rem]"
                >
                    {{ attendance }} % Att
                </div>
            </div>
        </div>

        <!--                Grade section-->
        <div class="flex h-11 w-3/12 flex-col justify-evenly">
            <div class="flex w-full items-center justify-center text-[0.7rem]">
                {{ grade }}
            </div>
            <div class="text-center text-[0.55rem] text-neutral-600">Grade</div>
        </div>

        <!--                Rank section-->
        <div
            class="flex h-11 w-3/12 items-center justify-center space-y-1 text-white"
        >
            <div
                class="flex rounded-sm bg-black px-1.5 py-1 text-3xl font-semibold italic"
            >
                {{ rank }} <span class="text-sm font-light">rd</span>
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
