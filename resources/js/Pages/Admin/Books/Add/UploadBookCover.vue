<template>
    <div class="flex w-11/12 flex-col items-center justify-center space-y-2">
        <Title title="Add Book" class="w-full" />
        <div
            class="flex w-full flex-col items-center justify-center space-y-5 rounded-lg bg-white p-4"
        >
            <div class="w-full text-center text-xl font-semibold">
                Book Cover Image
            </div>

            <ImageUpload
                finish-button-text="Finish Upload"
                :upload-url="'/admin/books/' + book.id + '/upload-book-cover'"
                :upload-image-button-text="$t('book.uploadBookCover')"
                :show-response-notification="true"
                :image-preview="book.cover_image"
            />

            <div class="flex w-full justify-end space-x-2">
                <PrimaryButton
                    title="Skip Upload"
                    class="bg-brand-400 text-white"
                    @click="router.get('/admin/books/' + book.id + '/chapter')"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Title from "@/Views/Teacher/Views/Title.vue";
import ImageUpload from "@/Components/ImageUpload.vue";

const book = computed(() => usePage().props.book);
</script>

<style scoped></style>
