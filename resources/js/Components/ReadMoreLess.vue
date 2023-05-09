<template>
    <div :class="clamp">
        <span>{{ text }}</span>
    </div>
    <button v-if="!readMoreActivated" class="" @click="activateReadMore">
        Read more...
    </button>
    <span v-if="readMoreActivated">{{ text }}</span>
    <button v-if="readMoreActivated" class="" @click="activateReadLess">
        Read less...
    </button>
</template>

<script setup>
import { computed, defineProps, ref } from "vue";

const props = defineProps({
    text: {
        type: String,
        required: true,
    },
    lines: {
        type: Number,
        default: 3,
    },
});

const readMoreActivated = ref(false);

function activateReadMore() {
    readMoreActivated.value = true;
}

function activateReadLess() {
    readMoreActivated.value = false;
}

const clamp = computed(() => {
    return `line-clamp-${props.lines}`;
});
</script>

<style scoped>
.line-clamp-3 {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
}

.line-clamp-4 {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 4;
    line-clamp: 4;
}
</style>
