<template>
    <div
        class="flex w-full flex-col items-center space-y-5 rounded-lg bg-white py-3 shadow-sm"
    >
        <div
            v-if="!feedbacks.data.length"
            class="px-3 py-5 text-center text-sm font-light"
        >
            {{ $t("common.noFeedbacks") }}
        </div>

        <div
            v-else
            class="flex w-full items-center justify-between gap-y-5 px-5"
        >
            <div v-for="(item, index) in feedbacks.data" :key="index">
                <div class="flex w-full flex-col items-start">
                    <div class="text-xs text-brand-text-300">
                        {{ moment(item.created_at).format("MMMM Do YYYY") }}
                    </div>
                    <div>
                        <p
                            class="line-clamp-3 mt-5 w-full text-sm leading-6 text-brand-text-350"
                        >
                            {{ item.feedback }}
                        </p>
                    </div>
                    <div class="relative mt-8 flex items-center gap-x-4">
                        <img
                            src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                            alt=""
                            class="h-10 w-10 rounded-full bg-brand-50"
                        />
                        <div class="text-sm leading-6">
                            <p class="font-semibold text-brand-text-500">
                                <a href="#">
                                    <span class="absolute inset-0"></span>
                                    {{ item.author.name }}
                                </a>
                            </p>
                            <p class="text-brand-text-350">
                                {{ item.author.admin?.position }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Pagination
            v-if="feedbacks?.links"
            :preserve-state="true"
            :links="feedbacks?.links"
            position="center"
        />
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import Pagination from "@/Components/Pagination.vue";
import moment from "moment";

const feedbacks = computed(() => usePage().props.feedbacks);
</script>

<style scoped></style>
