<template>
    <div class="flex w-full items-center justify-between">
        <div class="flex w-6/12">
            <img
                alt="Under Construction"
                src="/assets/under-construction.svg"
            />
        </div>
        <div class="flex min-h-full w-5/12 flex-col justify-center text-center">
            <h1 class="mb-4 text-3xl font-extrabold md:text-5xl">
                {{ title }}
            </h1>
            <p class="mb-8 text-lg">
                {{ subTitle }}
            </p>
            <div class="flex justify-center">
                <SecondaryButton
                    title="Go Back"
                    class="max-w-fit !rounded-lg bg-brand-400 text-white"
                    @click="goBack()"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import SecondaryButton from "@/Components/SecondaryButton.vue";

defineProps({
    title: {
        type: String,
        default: "Under Construction",
    },
    subTitle: {
        type: String,
        default:
            "We're working hard to bring you this feature. Check back soon!",
    },
});
const goBack = () => {
    window.history.back();
};
</script>

<style scoped></style>
