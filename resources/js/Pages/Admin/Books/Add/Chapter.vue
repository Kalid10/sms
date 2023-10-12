<template>
    <div class="flex w-11/12 flex-col items-center justify-center space-y-5">
        <Title :title="book?.title + ' Chapters'" class="w-full" />
        <div class="flex w-full flex-col space-y-5">
            <div class="flex flex-wrap items-center space-x-8">
                <div v-for="(chapter, index) in book?.chapters" :key="index">
                    <div
                        class="flex h-52 w-44 flex-col items-center justify-evenly space-y-2 rounded-xl border-2 border-black bg-brand-400 px-2 text-center text-white shadow-md"
                    >
                        <span class="font-medium">
                            {{ chapter.title }}
                        </span>
                        <span
                            class="flex flex-col space-y-1 text-sm font-light"
                        >
                            <span class="font-medium"> PAGES </span>
                            <span>
                                ( {{ chapter.start_page }} -
                                {{ chapter.end_page }} )
                            </span>
                        </span>
                        <PrimaryButton
                            title="Add Pages"
                            class="!bg-brand-150/90 !font-semibold !text-black"
                        />
                    </div>
                </div>
                <AddCard
                    class="h-full min-h-[13rem] !w-44"
                    title="Add Chapter"
                    @click="showAddChapterModal = true"
                />
            </div>

            <Modal v-model:view="showAddChapterModal">
                <ChapterForm @success="showAddChapterModal = false" />
            </Modal>
        </div>
    </div>
</template>

<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import AddCard from "@/Components/AddCard.vue";
import Modal from "@/Components/Modal.vue";
import ChapterForm from "@/Views/Admin/Books/ChapterForm.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const book = computed(() => usePage().props.book);
const showAddChapterModal = ref(false);
</script>

<style scoped></style>
