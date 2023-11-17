<template>
    <div
        class="flex min-h-screen w-11/12 flex-col items-center justify-center space-y-5"
    >
        <Title :title="book?.title + ' Chapters'" class="w-full" />
        <div class="flex h-full w-full flex-col items-center space-y-10">
            <div class="flex w-full flex-wrap items-center space-x-8">
                <div v-for="(chapter, index) in book?.chapters" :key="index">
                    <div
                        class="flex h-52 w-44 flex-col items-center justify-evenly space-y-3 rounded-xl border-2 border-black bg-gradient-to-br from-violet-500 to-purple-500 px-2 text-center text-white shadow-md"
                    >
                        <span class="font-semibold">
                            {{ chapter.title }}
                        </span>

                        <span
                            class="flex flex-col space-y-1 font-medium italic"
                        >
                            <span class="skew-x-5 bg-violet-800 px-3">
                                {{ chapter.start_page }} -
                                {{ chapter.end_page }}
                            </span>
                            <span class="text-xs uppercase">Pages</span>
                        </span>
                        <PrimaryButton
                            class="flex h-fit items-center justify-center space-x-1 !border-brand-200 !bg-violet-800"
                        >
                            <PencilSquareIcon class="w-4 fill-brand-100" />
                            <span>Edit Chapter</span>
                        </PrimaryButton>
                    </div>
                </div>
                <AddCard
                    class="h-full min-h-[13rem] !w-44"
                    title="Add Chapter"
                    @click="showAddChapterModal = true"
                />
            </div>

            <Page :book-id="book.id" />

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
import Page from "@/Pages/Admin/Books/Add/Page.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { PencilSquareIcon } from "@heroicons/vue/20/solid";

const book = computed(() => usePage().props.book);
const showAddChapterModal = ref(false);
</script>

<style scoped></style>
