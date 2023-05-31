<template>
    <div class="flex w-full flex-col items-center space-y-2 pb-6">
        <div v-if="errors.assessment" class="w-11/12 py-3">
            <Error :error="errors.assessment" />
        </div>
        <div
            v-for="(item, index) in assessment.students"
            :key="index"
            class="flex w-full cursor-pointer flex-col justify-between rounded-md pl-2 text-sm text-black"
            :class="{
                'blur-effect hover:bg-white':
                    isInputFocused && focusedInputIndex !== index,

                'focus-effect bg-black text-white':
                    focusedInputIndex === index &&
                    selectedCommentInput === null,
                'bg-zinc-100 hover:bg-gray-50':
                    index % 2 === 0 && !isInputFocused,

                'bg-black text-white hover:bg-black':
                    isInputFocused &&
                    focusedInputIndex === index &&
                    selectedCommentInput === null,

                'border-2 border-red-500': errors[`points.${index}.point`],
            }"
            @click.stop="handleRowClick(index, item.student.id)"
        >
            <div class="flex w-full justify-between">
                <div
                    class="p-3 underline-offset-2 hover:underline"
                    :class="{
                        'text-xs font-light':
                            isInputFocused && focusedInputIndex !== index,
                        ' text-sm font-semibold': focusedInputIndex === index,
                    }"
                    @click="
                        selectedCommentInput === index
                            ? (selectedCommentInput = null)
                            : (selectedCommentInput = index)
                    "
                >
                    <span class="font-semibold"> {{ index + 1 }}.</span>
                    <span>
                        {{ item.student.user.name }}
                    </span>
                </div>

                <div class="flex w-4/12 justify-end space-x-2 pr-2">
                    <div
                        class="flex flex-col items-center justify-evenly text-xl font-semibold"
                    >
                        <input
                            :ref="
                                (el) => {
                                    inputRefs[index] = el;
                                    rowRefs[index] = el;
                                }
                            "
                            v-model.number="points[index].point"
                            :max="Number(assessment.maximum_point)"
                            type="number"
                            class="mr-2 w-20 rounded-md border-none bg-white text-xs text-black transition-transform focus:ring-black"
                            :class="{
                                'bg-zinc-100 ': index % 2 === 0,
                                'border-red-500':
                                    errors[`points.${index}.point`],
                            }"
                            placeholder="-"
                            @focusin="handleFocusIn(index)"
                            @focusout="handleFocusOut()"
                            @keydown="onKeyDown($event, index)"
                            @change="$emit('updatePoints', points)"
                        />
                    </div>
                    <ChatBubbleBottomCenterIcon
                        class="w-5 hover:scale-125"
                        :class="
                            points[index].comment
                                ? 'text-zinc-800'
                                : 'text-gray-300'
                        "
                        @click="
                            selectedCommentInput === index
                                ? (selectedCommentInput = null)
                                : (selectedCommentInput = index)
                        "
                    />
                </div>
            </div>
            <div v-if="selectedCommentInput === index" class="p-4">
                <TextArea
                    v-model="points[index].comment"
                    rows="4"
                    label="Comment"
                    :placeholder="
                        'Please provide your assessment feedback for ' +
                        item.student.user.name +
                        '.\nNote: This feedback will be accessible to parents, teachers, and principals.'
                    "
                    @focusout="selectedCommentInput = null"
                />
            </div>
            <p
                v-if="errors[`points.${index}.point`]"
                class="pb-1 text-[0.6rem] font-medium text-red-500"
            >
                {{ errors[`points.${index}.point`] }}
            </p>
        </div>
    </div>
</template>
<script setup>
import { computed, nextTick, reactive, ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import { ChatBubbleBottomCenterIcon } from "@heroicons/vue/20/solid/index.js";
import TextArea from "@/Components/TextArea.vue";
import Error from "@/Components/Error.vue";

const assessment = usePage().props.assessment;
const focusedInputIndex = ref(null);
const points = reactive(
    assessment.students.map((item) => ({
        student_id: item.student.id,
        point: item.point || null, // If there's a previously set point, use that. Otherwise, default to null
        comment: item.comment,
    }))
);
const errors = computed(() => {
    return usePage().props.errors;
});
const emit = defineEmits(["click", "updatePoints"]);
const selectedCommentInput = ref(null);
const inputRefs = reactive([]);
const rowRefs = reactive([]);
const onKeyDown = (event, index) => {
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

watch(errors, (newErrors) => {
    for (let key in newErrors) {
        if (key.startsWith("points.") && key.endsWith(".point")) {
            const index = parseInt(key.split(".")[1]);
            if (!isNaN(index) && rowRefs[index]) {
                nextTick(() => {
                    rowRefs[index].scrollIntoView({ behavior: "smooth" });
                });
                break;
            }
        }
    }
});

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
