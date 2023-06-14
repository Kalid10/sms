<template>
    <div
        class="flex w-full flex-col items-center space-y-5 rounded-lg bg-white py-3 shadow-sm"
    >
        <div class="flex w-full justify-between px-5">
            <div
                class="flex grow justify-center space-x-2 text-center text-xl font-semibold underline-offset-4"
            >
                <BookmarkSquareIcon class="w-6" />
                <span>Feedbacks</span>
            </div>
        </div>
        <div
            class="flex w-11/12 flex-col items-center justify-center space-y-6 px-1"
        >
            <div
                v-if="!feedbacks.data"
                class="py-5 px-3 text-center text-sm font-light"
            >
                No Feedbacks
            </div>
            <div
                v-for="(item, index) in feedbacks.data"
                :key="index"
                class="flex w-full cursor-pointer justify-center space-x-3"
            >
                <div
                    class="min-h-full w-[0.01rem] rounded-t-lg rounded-b-md py-2"
                    :class="colors[Math.floor(Math.random() * colors.length)]"
                ></div>

                <div class="relative flex w-full flex-col space-y-1">
                    <div class="text-xs font-medium hover:font-semibold">
                        {{ item.feedback }}
                    </div>
                    <div
                        class="flex w-full justify-between text-[0.6rem] 2xl:text-xs"
                    >
                        <div class="font-light">
                            {{ moment(item.created_at).fromNow() }}
                        </div>
                        <div>{{ item.author.name }}</div>
                    </div>
                </div>
            </div>
        </div>

        <Pagination
            :preserve-state="true"
            :links="feedbacks.links"
            position="center"
        />
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import moment from "moment/moment";
import { BookmarkSquareIcon } from "@heroicons/vue/24/outline";
import Pagination from "@/Components/Pagination.vue";
import { computed } from "vue";

const feedbacks = computed(() => usePage().props.feedbacks);

const colors = [
    "bg-pink-500",
    "bg-yellow-500",
    "bg-green-500",
    "bg-blue-500",
    "bg-indigo-500",
    "bg-purple-500",
    "bg-violet-500",
    "bg-red-500",
];
</script>

<style scoped></style>
