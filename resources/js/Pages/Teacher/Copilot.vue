<template>
    <div class="flex min-h-screen w-full flex-col space-y-2 bg-gray-50 p-5">
        <div class="flex w-full justify-between px-5">
            <Title class="w-6/12" title="Rigel Copilot" />
            <div
                class="flex w-5/12 space-x-4 rounded-sm bg-gradient-to-tl from-purple-500 to-violet-500 p-3"
            >
                <ExclamationCircleIcon class="w-5 text-white" />
                <p class="w-11/12 text-sm text-white shadow-sm">
                    Please note: Copilot's suggestions are not guaranteed to be
                    100% accurate. It's a tool for idea generation, not a
                    substitute for professional judgement. Always review and
                    verify AI-generated content before use. Thank you.
                </p>
            </div>
        </div>

        <div class="flex w-6/12 flex-col space-y-8 rounded-lg p-4">
            <TextInput
                v-model="searchText"
                placeholder="Ask a question about your lesson. Ex: Give me five questions about cell"
                class-style="h-10 rounded-2xl w-full text-sm focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                @keyup.enter="search"
            />

            <Loading
                v-if="showLoading"
                class="flex justify-center"
                color="secondary"
            />
            <div v-if="result" class="flex flex-col space-y-2">
                <div class="font-medium">
                    Result for
                    <span class="font-light"> "{{ lastSearch }}" </span>
                </div>
                <div
                    class="flex w-full flex-col rounded-lg bg-gray-100 p-3 text-sm leading-6"
                >
                    {{ result }}
                    <span v-if="isResultUpdating" class="animate-blink">|</span>
                    <div class="flex w-full justify-end">
                        <ClipboardDocumentIcon
                            class="w-4 cursor-pointer text-zinc-400 hover:text-black"
                            @click="copyToClipboard(result)"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import {
    ClipboardDocumentIcon,
    ExclamationCircleIcon,
} from "@heroicons/vue/20/solid";
import TextInput from "@/Components/TextInput.vue";
import { ref } from "vue";
import Loading from "@/Components/Loading.vue";
import { copyToClipboard } from "@/utils";

const searchText = ref();
const showLoading = ref(false);
const isResultUpdating = ref(false);
const result = ref("");
const lastSearch = ref("");

const search = () => {
    let es = new EventSource(
        "/teacher/copilot/search?prompt=" + searchText.value
    );
    result.value = "";
    showLoading.value = true;
    es.addEventListener(
        "update",
        (event) => {
            if (event.data) {
                isResultUpdating.value = true;
                lastSearch.value = searchText.value;
                showLoading.value = false;
                if (event.data === "<END_STREAMING_SSE>") {
                    showLoading.value = false;
                    isResultUpdating.value = false;
                    es.close();
                } else result.value += event.data;
            }
        },
        false
    );
    es.addEventListener(
        "error",
        (event) => {
            // TODO: Handle error
        },
        false
    );
};
</script>

<style scoped></style>
