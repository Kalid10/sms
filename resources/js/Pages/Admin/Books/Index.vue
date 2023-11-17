<template>
    <div class="w-full space-y-3 px-3 lg:w-11/12">
        <Title title="Books" />

        <div v-if="books?.data?.length" class="flex w-full flex-wrap space-x-5">
            <div
                v-for="(book, index) in books.data"
                :key="index"
                class="group h-52 w-44 cursor-pointer flex-col rounded-lg border-2 border-black text-center shadow-md"
                :style="
                    book?.cover_image
                        ? `--image-url: url('${book?.cover_image}');`
                        : 'hover:scale-105'
                "
                :class="
                    book?.cover_image
                        ? 'bg-[image:var(--image-url)] bg-cover z-10'
                        : 'bg-gradient-to-br from-violet-500 to-purple-500 text-white'
                "
                @click="router.get(`/admin/books/${book.id}/chapter`)"
            >
                <span
                    :class="book?.cover_image ? 'hidden' : 'flex'"
                    class="z-50 min-h-full min-w-full flex-col items-center justify-evenly space-y-2 from-violet-500 to-purple-500 text-white group-hover:flex group-hover:bg-gradient-to-br"
                >
                    <span class="px-2 text-lg font-bold">
                        {{ book.title }}
                    </span>
                    <span class="px-2 text-sm">
                        {{ book.subject.full_name }} ~
                        {{ book.level.name }}
                    </span>
                    <span class="px-2 text-sm font-medium">
                        {{ book?.chapters?.length }} Chapters
                    </span>
                    <PrimaryButton
                        title="View Book"
                        class="!bg-brand-150/90 !font-semibold !text-black"
                    />
                </span>
            </div>
            <AddCard
                class="h-full min-h-[13rem] !w-44"
                title="Add Book"
                @click="router.get('/admin/books/add')"
            />
        </div>
        <div v-else class="flex h-full items-center justify-center">
            <EmptyView title="No Books Found!">
                <template #default>
                    <PrimaryButton
                        title="Add Book"
                        class="bg-brand-400 text-white"
                        @click="router.get('/admin/books/add')"
                    />
                </template>
            </EmptyView>
        </div>
    </div>
</template>

<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import { computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import EmptyView from "@/Views/EmptyView.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AddCard from "@/Components/AddCard.vue";

const books = computed(() => usePage().props.books);
</script>

<style scoped></style>
