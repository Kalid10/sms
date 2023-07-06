<template>
    <div
        class="flex h-44 w-full flex-col items-center justify-evenly rounded-lg bg-gradient-to-b from-violet-500 to-purple-500 text-white shadow-sm"
    >
        <div
            class="-skew-x-3 bg-violet-300 px-3 text-xl font-bold uppercase text-zinc-800"
        >
            Artificial Intelligence
        </div>
        <div class="px-3 text-center text-purple-50">
            Empower your teaching with our AI Copilot, crafting unique questions
            and sparking enriching conversations that ignite curiosity.
        </div>
        <SecondaryButton
            class="w-6/12 !rounded-2xl bg-violet-300 font-semibold text-zinc-800"
            title="Explore More"
            @click="router.get('/teacher/copilot')"
        />
    </div>
</template>
<script setup>
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { router } from "@inertiajs/vue3";
</script>
<style scoped></style>
