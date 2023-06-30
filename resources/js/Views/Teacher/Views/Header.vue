<template>
    <div
        class="flex w-full items-center justify-between space-x-4 rounded-lg bg-gradient-to-bl from-neutral-700 to-zinc-800 py-6 pl-4 text-gray-200 shadow-sm"
    >
        <img
            v-if="image"
            :src="`https://xsgames.co/randomusers/avatar.php?g=male`"
            alt="avatar"
            class="w-20 rounded-md object-contain"
        />

        <Title :title="title" :class="showCurrentClass ? 'w-4/12' : 'w-6/12'" />

        <div
            class="flex h-full items-center justify-between divide-x divide-white lg:w-8/12"
        >
            <div
                class="flex justify-center"
                :class="showCurrentClass ? 'w-8/12' : 'w-9/12'"
            >
                <SelectInput
                    v-model="selectedInput"
                    class="w-10/12 text-black"
                    :options="selectInputOptions"
                    rounded="rounded-full"
                />
            </div>
            <div v-if="batchSubjectRank" class="w-4/12 px-1">
                <div
                    class="flex flex-col justify-center space-y-4 text-center text-4xl font-bold shadow-sm"
                >
                    <div v-if="batchSubjectRank">
                        {{ numberWithOrdinal(batchSubjectRank) }}
                    </div>
                    <div v-else>-</div>
                    <span class="text-xs font-light">
                        Class Rank From Total {{ totalBatchesCount ?? "-" }}
                    </span>
                </div>
            </div>
            <div v-else-if="showCurrentClass" class="w-4/12 px-1">
                <CurrentClass />
            </div>
        </div>

        <slot></slot>
    </div>
</template>
<script setup>
import SelectInput from "@/Components/SelectInput.vue";
import CurrentClass from "@/Views/Teacher/Views/Batches/CurrentClass.vue";
import Title from "@/Views/Teacher/Views/Title.vue";
import { ref, watch } from "vue";
import { numberWithOrdinal } from "@/utils";

const emit = defineEmits(["change"]);
const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    selectInputOptions: {
        type: Array,
        default: Array,
    },
    selectedInput: {
        type: String,
        default: "",
    },
    batchSubjectRank: {
        type: Number,
        default: null,
    },
    totalBatchesCount: {
        type: Number,
        default: null,
    },
    image: {
        type: String,
        default: null,
    },
    showCurrentClass: {
        type: Boolean,
        default: true,
    },
});

const selectedInput = ref(props.selectedInput);
watch(selectedInput, (value) => {
    emit("change", value, "");
});
</script>

<style scoped></style>
