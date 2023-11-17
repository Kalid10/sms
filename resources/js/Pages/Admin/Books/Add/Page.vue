<template>
    <div
        class="flex min-h-[33.3%] w-full flex-col items-center justify-center space-y-2"
    >
        <FileInput
            :is-multiple="true"
            max-file-size="105000000"
            accepted-types="file/pdf"
            @file-uploaded="uploadBookPages"
        >
            <template #header>
                <div class="text-xl font-bold uppercase text-brand-text-200">
                    Upload Book Pages
                </div>
            </template>

            <template #description>
                <div class="text-sm font-bold uppercase text-brand-text-250">
                    * Remember the file names should be the page numbers
                </div>
            </template>
        </FileInput>
    </div>
</template>

<script setup>
import FileInput from "@/Components/FileInput.vue";
import { router } from "@inertiajs/vue3";
import { inject } from "vue";

const showNotification = inject("showNotification");
const props = defineProps({
    bookId: {
        type: Number,
        required: true,
    },
});
const uploadBookPages = (files) => {
    router.post(
        `/admin/books/${props.bookId}/upload-book-pages`,
        {
            files: files,
        },
        {
            onError: (error) => {
                showNotification({
                    type: "error",
                    message: error.files,
                });
            },
        }
    );
};
</script>

<style scoped></style>
