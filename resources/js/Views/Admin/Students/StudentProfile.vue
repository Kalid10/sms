<template>
    <div class="flex flex-col items-center gap-5">
        <div class="flex w-full flex-col gap-3">
            <img
                :src="`https://xsgames.co/randomusers/avatar.php?g=${student.user.gender}`"
                alt="avatar"
                class="mx-auto aspect-square w-16 rounded-full md:w-3/4"
            />

            <div class="mx-auto flex w-fit flex-col items-center">
                <Heading class="text-center !font-semibold" size="lg">{{
                    student.user.name
                }}</Heading>
                <h3 class="text-sm font-semibold text-brand-text-300">
                    {{ section["level"]["level_category"]["name"] }}
                </h3>
            </div>
        </div>
    </div>
</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const student = computed(() => usePage().props.student);
const section = computed(() => usePage().props.active_batch);
</script>

<style scoped></style>
