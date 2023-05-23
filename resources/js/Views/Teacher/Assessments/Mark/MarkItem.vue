<template>
    <div class="flex w-full flex-col space-y-2 pb-6">
        <div
            v-for="(item, index) in assessment.students"
            :key="index"
            class="flex w-full cursor-pointer justify-between rounded-md pl-2 text-sm hover:bg-gray-50"
            :class="{
                'blur-effect hover:bg-white':
                    isInputFocused && focusedInputIndex !== index,

                'focus-effect bg-black text-white': focusedInputIndex === index,
                'bg-zinc-100 ': index % 2 === 0 && !isInputFocused,
                'bg-black text-white hover:bg-black':
                    isInputFocused && focusedInputIndex === index,
            }"
            @click.stop="handleRowClick(index, item.student.id)"
        >
            <div
                class="p-3 underline-offset-2 hover:underline"
                :class="{
                    'text-xs font-light':
                        isInputFocused && focusedInputIndex !== index,
                    ' text-sm font-semibold': focusedInputIndex === index,
                }"
            >
                <span class="font-semibold"> {{ index + 1 }}.</span>
                <span>
                    {{ item.student.user.name }}
                    {{ item.student.id }}
                </span>
            </div>

            <div class="flex items-center justify-evenly text-xl font-semibold">
                <input
                    :ref="(el) => (inputRefs[index] = el)"
                    v-model.number="points[index].point"
                    :max="assessment.maximum_point"
                    type="number"
                    class="mr-2 w-16 rounded-md border-none bg-white text-xs text-black transition-transform focus:ring-black"
                    :class="{
                        'bg-zinc-100 ': index % 2 === 0,
                    }"
                    :placeholder="assessment.maximum_point"
                    @focusin="handleFocusIn(index)"
                    @focusout="handleFocusOut()"
                    @keydown="onKeyDown($event, index, item)"
                    @change="$emit('updatePoints', points)"
                />
            </div>
        </div>
    </div>
</template>
<script setup>
import { nextTick, reactive, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const assessment = usePage().props.assessment;
const focusedInputIndex = ref(null);
const points = reactive(
    assessment.students.map((item) => ({
        student_id: item.student.id,
        point: item.point || null, // If there's a previously set point, use that. Otherwise, default to null
    }))
);

const emit = defineEmits(["click", "updatePoints"]);

const inputRefs = reactive([]);
const onKeyDown = (event, index, item) => {
    switch (event.key) {
        case "Enter":
        case "Tab":
        case "ArrowDown":
            event.preventDefault();
            moveFocus(index, "down");
            nextTick(() => inputRefs[focusedInputIndex.value].focus());
            break;
        case "ArrowUp":
            event.preventDefault();
            moveFocus(index, "up");
            nextTick(() => inputRefs[focusedInputIndex.value].focus());
            break;
        default:
            break;
    }
};
const moveFocus = (currentIndex, direction) => {
    if (direction === "down" && currentIndex < assessment.students.length - 1) {
        focusedInputIndex.value = currentIndex + 1;
    } else if (direction === "up" && currentIndex > 0) {
        focusedInputIndex.value = currentIndex - 1;
    }
};
const isInputFocused = ref(false);

const handleFocusIn = (index) => {
    focusedInputIndex.value = index;
    isInputFocused.value = true;
};

const handleFocusOut = () => {
    isInputFocused.value = false;
    focusedInputIndex.value = null;
};
const handleRowClick = (index, studentId) => {
    focusedInputIndex.value = index;
    nextTick(() => inputRefs[focusedInputIndex.value].focus());
    emit("click", studentId);
};
</script>
<style scoped>
.blur-effect *:not(.focus-effect) {
    filter: blur(1.1px);
}

input:focus {
    transform: scale(1.2);
    z-index: 10;
}

.focus-effect {
    z-index: 10;
}
</style>
