<template>
    <div class="flex w-1/2 flex-col items-center space-y-5">
        <div class="flex space-x-2">
            <component :is="icon" class="w-5" :class="iconColor" />
            <div class="font-bold">{{ label }}</div>
        </div>
        <div
            v-for="(student, index) in students"
            :key="student.id"
            class="w-full"
            :class="index % 2 === 0 ? 'bg-brand-100/70' : ''"
            @click="$emit('student-clicked', student.id)"
        >
            <div class="flex h-10 w-full items-center justify-evenly">
                <div
                    class="flex h-full w-6/12 cursor-pointer items-center justify-center text-center text-[0.65rem] underline-offset-2 hover:font-semibold hover:underline"
                >
                    {{ student.name }}
                </div>
                <ResultCard
                    :score="student.score"
                    :total="student.total"
                    size="sm"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import ResultCard from "@/Views/Teacher/Views/Assessments/Details/Views/ResultCard.vue";

defineEmits(["student-clicked"]);
defineProps({
    label: {
        type: String,
        required: true,
    },
    icon: {
        type: Object,
        required: true,
    },
    iconColor: {
        type: String,
        default: "",
    },
    students: {
        type: Array,
        required: true,
    },
});
</script>
