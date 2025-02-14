<template>
    <div class="flex w-full flex-col items-center space-y-2 pb-6">
        <Toast
            v-if="!isOpen"
            :show-toast="showToast"
            :class="toastStyle"
            :event="toastEvent"
            :value="toastValue"
            :class-style="'ml-40'"
            :show-icon="false"
            :side-bar-style="true"
        />
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
                'bg-brand-50 hover:bg-brand-100':
                    index % 2 === 0 && !isInputFocused && item.status === null,

                'bg-brand-400 text-white hover:bg-brand-400':
                    isInputFocused &&
                    focusedInputIndex === index &&
                    selectedCommentInput === null,

                'border-2 border-red-500': errors[`points.${index}.point`],

                'bg-gradient-to-bl from-red-600 to-orange-500 text-white':
                    points[index].status === 'misconduct',
                'bg-gradient-to-br from-yellow-400 to-orange-500 text-white':
                    points[index].status === 'disqualified',
                'bg-gradient-to-bl from-purple-400 to-fuchsia-400  text-white':
                    points[index].status === 'valid_reassessment',
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
                    <span class="font-semibold"> {{ index + 1 }}. </span>
                    <span class="font-medium">
                        {{ item.student.user.name }}
                        <span class="text-[0.7rem]">
                            ({{ item.student?.user?.username }})
                        </span>
                    </span>
                </div>

                <div class="flex w-5/12 justify-end space-x-5 pr-2">
                    <div
                        class="flex flex-col items-center justify-evenly text-xl font-semibold"
                    >
                        <input
                            v-if="points[index].status === null"
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
                                'bg-brand-100 ': index % 2 === 0,
                                'border-red-600':
                                    errors[`points.${index}.point`],
                                'bg-white opacity-20':
                                    points[index].status !== null,
                            }"
                            placeholder="-"
                            :disabled="
                                points[index].status !== null || !isTeacher()
                            "
                            @focusin="handleFocusIn(index)"
                            @focusout="handleFocusOut()"
                            @keydown="onKeyDown($event, index)"
                            @change="$emit('updatePoints', points)"
                        />
                    </div>
                    <ArrowPathRoundedSquareIcon
                        class="w-5 hover:scale-125"
                        :class="
                            points[index].status === 'valid_reassessment'
                                ? 'text-black'
                                : 'text-gray-400 hover:text-purple-500'
                        "
                        @click="handleStatusClick(index, 'valid_reassessment')"
                        @mouseenter="
                            handleMouseEnter(
                                $event,
                                'Valid Absentee',
                                '!bg-purple-500 !text-white'
                            )
                        "
                        @mouseleave="handleMouseLeave()"
                    />
                    <ArchiveBoxXMarkIcon
                        class="w-5 hover:scale-125"
                        :class="
                            points[index].status === 'disqualified'
                                ? 'text-black'
                                : 'text-gray-400 hover:text-orange-500'
                        "
                        @click="handleStatusClick(index, 'disqualified')"
                        @mouseenter="
                            handleMouseEnter(
                                $event,
                                'Disqualify',
                                '!bg-orange-500 !text-white'
                            )
                        "
                        @mouseleave="handleMouseLeave()"
                    />
                    <BookmarkSlashIcon
                        class="w-5 hover:scale-125"
                        :class="
                            points[index].status === 'misconduct'
                                ? 'text-black'
                                : 'text-gray-400 hover:text-red-600'
                        "
                        @click="handleStatusClick(index, 'misconduct')"
                        @mouseenter="
                            handleMouseEnter(
                                $event,
                                'MisConduct',
                                '!bg-red-600 !text-white'
                            )
                        "
                        @mouseleave="handleMouseLeave()"
                    />
                    <ChatBubbleBottomCenterIcon
                        class="w-5 hover:scale-125"
                        :class="
                            points[index].comment
                                ? 'text-black'
                                : 'text-gray-400 hover:text-black'
                        "
                        @click="
                            selectedCommentInput === index
                                ? (selectedCommentInput = null)
                                : (selectedCommentInput = index);
                            $emit('updatePoints', points);
                        "
                        @mouseenter="
                            handleMouseEnter(
                                $event,
                                'Comment',
                                '!bg-gray-700 !text-white'
                            )
                        "
                        @mouseleave="handleMouseLeave()"
                    />
                    <XMarkIcon
                        v-if="points[index].status"
                        class="w-5 cursor-pointer hover:scale-125"
                        @click="points[index].status = null"
                    />
                    {{}}
                </div>
            </div>
            <div v-if="selectedCommentInput === index" class="p-4">
                <TextArea
                    v-model="points[index].comment"
                    label-style="pl-0.5 text-sm font-semibold text-black"
                    rows="4"
                    label="Comment"
                    :placeholder="
                        'Please provide your assessment feedback for ' +
                        item.student.user.name +
                        '.\nNote: This feedback will be accessible to parents, teachers, and principals.'
                    "
                    class="text-black"
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
import {
    ArchiveBoxXMarkIcon,
    ArrowPathRoundedSquareIcon,
    BookmarkSlashIcon,
    ChatBubbleBottomCenterIcon,
    XMarkIcon,
} from "@heroicons/vue/20/solid/index.js";
import TextArea from "@/Components/TextArea.vue";
import Error from "@/Components/Error.vue";
import { isTeacher } from "@/utils";
import Toast from "@/Components/Toast.vue";
import { useSidebarStore } from "@/Store/sidebar";

const assessment = usePage().props.assessment;
const focusedInputIndex = ref(null);
const points = reactive(
    assessment.students.map((item) => ({
        student_id: item.student.id,
        point: item.point || "", // If there's a previously set point, use that. Otherwise, default to null
        comment: item.comment,
        status: item.status,
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
    selectedCommentInput.value = null;
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
const handleStatusClick = (index, status) => {
    if (isTeacher()) {
        points[index].status = status;
        emit("updatePoints", points);
        points[index].point = 0;
    }
};

// Toast logic
const isOpen = computed(() => useSidebarStore().isOpen);
const showToast = ref(false);
const toastEvent = ref(null);
const toastStyle = ref(null);
const toastValue = ref(null);
const handleMouseEnter = (event, value, style) => {
    toastEvent.value = event;
    toastValue.value = value;
    showToast.value = true;
    toastStyle.value = style;
};

const handleMouseLeave = () => {
    showToast.value = false;
    toastEvent.value = null;
    toastValue.value = null;
    toastStyle.value = null;
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
