<template>
    <div class="col-span-9 grid h-full w-full grid-rows-[1fr] gap-4 p-4">
        <div
            class="grid max-h-full w-full grid-cols-[repeat(5,1fr)_auto] grid-rows-[auto_repeat(8,1fr)] gap-2 rounded-t-md"
        >
            <div
                v-for="(time, i) in [
                    '',
                    '08:45',
                    '09:30',
                    '10:15',
                    '11:00',
                    '11:45',
                    '12:30',
                    '13:15',
                    '18:00',
                ]"
                :key="i"
                :style="{ gridRowStart: i + 1 }"
                class="col-span-1 col-start-6 row-span-1 flex flex-col items-center justify-center"
            >
                <span
                    v-if="i !== 0"
                    class="text-brand-text-600 -rotate-90 text-sm font-semibold"
                >
                    {{ time }}
                </span>
            </div>

            <div
                v-for="(day, d) in [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                ]"
                :key="d"
                class="text-brand-text-600 grid h-10 place-items-center text-sm font-semibold"
            >
                {{ day }}
            </div>

            <div
                v-for="i in 40"
                :key="i"
                :class="
                    randomNumbers.includes(i - 1)
                        ? `${
                              colors[
                                  categories.indexOf(
                                      shuffledSubjects[i - 1]?.category
                                  )
                              ]
                          } rounded-md`
                        : ''
                "
                class="flex flex-col items-center justify-center border transition duration-300"
            >
                <span
                    class="transition-all duration-500"
                    :class="
                        randomNumbers.includes(i - 1)
                            ? 'scale-125 text-xs font-semibold animate-fade-in'
                            : 'mt-4 scale-100 text-black/50 text-xs'
                    "
                >
                    {{ shuffledSubjects[i - 1]?.full_name }}
                </span>
                <span
                    :class="[
                        textColors[
                            categories.indexOf(
                                shuffledSubjects[i - 1]?.category
                            )
                        ],
                        randomNumbers.includes(i - 1)
                            ? 'scale-100 opacity-100'
                            : 'scale-75 opacity-0',
                    ]"
                    class="text-xs transition duration-500"
                >
                    {{ shuffledSubjects[i - 1]?.category }}
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { subjects } from "@/fake.js";

const shuffledSubjects = ref(Array.from(Array(40)));
const randomNumbers = ref(
    getRandomNumbers(Math.min(4 + Math.floor(Math.random() * 8), 8), 39) || []
);
const categories = computed(() =>
    subjects.reduce((acc, subject) => {
        if (!acc.includes(subject.category)) {
            acc.push(subject.category);
        }
        return acc;
    }, [])
);

setInterval(() => {
    randomNumbers.value = getRandomNumbers(
        Math.min(4 + Math.floor(Math.random() * 8), 8),
        39
    );
    randomNumbers.value.forEach((index) => {
        shuffledSubjects.value[index] =
            subjects[Math.floor(Math.random() * subjects.length)];
    });

    setTimeout(() => {
        randomNumbers.value = [];
    }, 1000);
}, 2000);

function getRandomNumbers(n, N) {
    const numbers = [];

    for (let i = 0; i <= N; i++) {
        numbers.push(i);
    }

    const result = [];

    for (let i = 0; i < n; i++) {
        const randomIndex = Math.floor(Math.random() * numbers.length);
        const randomNumber = numbers[randomIndex];
        result.push(randomNumber);
        numbers.splice(randomIndex, 1);
    }

    return result;
}

const colors = [
    "bg-red-100 text-red-600 border-red-600",
    "bg-yellow-100 text-yellow-600 border-yellow-600",
    "bg-green-100 text-green-600 border-green-600",
    "bg-blue-100 text-blue-600 border-blue-600",
    "bg-indigo-100 text-indigo-600 border-indigo-600",
    "bg-purple-100 text-purple-600 border-purple-600",
    "bg-pink-100 text-pink-600 border-pink-600",
];

const textColors = [
    "text-red-600",
    "text-yellow-600",
    "text-green-600",
    "text-blue-600",
    "text-indigo-600",
    "text-purple-600",
    "text-pink-600",
];
</script>

<style scoped></style>
