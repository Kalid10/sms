<template>
    <div class="flex w-full flex-col space-y-2 pb-6">
        <div
            v-for="(item, index) in assessment.students"
            :key="index"
            class="flex w-full cursor-pointer flex-col justify-between rounded-md pl-2 text-sm hover:bg-gray-50"
            :class="{
                'blur-effect hover:bg-white':
                    isInputFocused && focusedInputIndex !== index,

                'focus-effect bg-black text-white': focusedInputIndex === index,
                'bg-zinc-100 ': index % 2 === 0 && !isInputFocused,

                'bg-black text-white hover:bg-black':
                    isInputFocused && focusedInputIndex === index,

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
                >
                    <span class="font-semibold"> {{ index + 1 }}.</span>
                    <span>
                        {{ item.student.user.name }}
                        {{ item.student.id }}
                    </span>
                </div>

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
                            'border-red-500': errors[`points.${index}.point`],
                        }"
                        placeholder="-"
                        @focusin="handleFocusIn(index)"
                        @focusout="handleFocusOut()"
                        @keydown="onKeyDown($event, index, item)"
                        @change="$emit('updatePoints', points)"
                    />
                </div>
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

const assessment = usePage().props.assessment;
const focusedInputIndex = ref(null);
const points = reactive(
    assessment.students.map((item) => ({
        student_id: item.student.id,
        point: item.point || null, // If there's a previously set point, use that. Otherwise, default to null
    }))
);
const errors = computed(() => {
    return usePage().props.errors;
});
const emit = defineEmits(["click", "updatePoints"]);

const inputRefs = reactive([]);
const rowRefs = reactive([]);
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
