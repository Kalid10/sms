<template>
    <div
        v-if="showGettingStarted"
        class="mt-5 hidden h-fit w-4/12 flex-col space-y-6 rounded-lg border-4 border-white p-5 text-center text-sm lg:block"
    >
        <div>
            <h2 class="text-2xl font-bold">{{ $t("chat.gettingStarted") }}</h2>
            <p class="py-2">
                {{
                    $t("chat.helloWeWant", {
                        name: usePage().props.auth.user.name,
                    })
                }}
            </p>
        </div>
        <div>
            <h3 class="text-xl font-semibold">
                {{ $t("chat.askSpecificQuestions") }}
            </h3>
            <p class="font-light">
                {{ $t("chat.theAiChat") }}
            </p>
        </div>

        <div>
            <h3 class="text-xl font-semibold">
                {{ $t("chat.experimentWithDifferent") }}
            </h3>
            <p class="font-light">
                {{ $t("chat.feelFree") }}
            </p>
        </div>

        <div>
            <h3 class="text-xl font-semibold">
                {{ $t("chat.useItAsResource") }}
            </h3>
            <p class="font-normal">
                {{ $t("chat.needHelpFinding") }}
            </p>
        </div>

        <div>
            <h3 class="text-xl font-semibold">
                {{ $t("chat.seekClarification") }}
            </h3>
            <p class="font-light">
                {{ $t("chat.ifYourAreDealing") }}
            </p>
        </div>

        <div>
            <h3 class="text-xl font-semibold">
                {{ $t("chat.exploreCreativeIdeas") }}
            </h3>
            <p class="font-light">
                {{ $t("chat.theAIChat") }}
            </p>
        </div>

        <p class="py-4 italic text-brand-400">
            {{ $t("chat.RememberWhile") }}
        </p>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";

defineProps({
    showGettingStarted: {
        type: Boolean,
        default: false,
    },
});
</script>
<style scoped></style>
