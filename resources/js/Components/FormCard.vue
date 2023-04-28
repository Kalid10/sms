<template>
    <div class="card h-full w-full">
        <div
            class="card-inner"
            :class="{ 'is-flipped': isFlipped }"
        >
            <div class="card-face card-face--front flex min-h-10 w-full flex-col rounded-lg border bg-white p-5">
                <slot name="form"></slot>
                <slot name="form-actions">
                    <PrimaryButton class="my-4" title="Submit" @click="submitForm"/>
                </slot>
            </div>
            <Card class="card-face card-face--back flex items-center justify-center rounded  p-4">
                <slot name="card">
                    <Card title="Submitted lesson"></Card>
                </slot>
            </Card>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Card from "@/Components/Card.vue";

const isFlipped = ref(false)

function submitForm() {
    isFlipped.value = true

    function emit(submit) {

    }

    emit('submit')
}
</script>

<style scoped>
.card {
    perspective: 1000px;
    width: 300px;
    height: 400px;
}

.card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    transition: transform 0.6s;
    transform-style: preserve-3d;
    cursor: pointer;
}

.card-inner.is-flipped {
    transform: rotateY(180deg);
}

.card-face {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
}

.card-face--back {
    transform: rotateY(180deg);
}
</style>
