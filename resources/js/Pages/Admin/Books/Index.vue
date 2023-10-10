<template>
    <div class="w-full space-y-3 px-3 lg:w-11/12">
        <Title title="Books" />

        <div v-if="books?.data?.length" class="flex w-full flex-wrap">
            <div v-for="(book, index) in books" :key="index">
                {{ book }}
            </div>
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

const books = computed(() => usePage().props.books);
</script>

<style scoped></style>
