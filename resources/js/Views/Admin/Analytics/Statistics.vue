<template>
    <transition name="fade" mode="out-in">
        <Card
            v-if="cards[currentIndex]"
            :key="currentIndex"
            subtitle="subtitle"
            :title=" cards[currentIndex].title "
            class="relative min-w-full p-10 hover:bg-gray-100 md:col-span-1 md:row-span-1"
            icon
        >
            <h3 class=" flex flex-col">
                <slot>
                    <span class="text-2xl font-semibold">
                        {{ cards[currentIndex].content }}
                    </span>
                    <ChevronLeftIcon
                        :disabled="currentIndex === 0"
                        class="absolute bottom-0 left-0 mb-16 h-7 w-7 cursor-pointer rounded fill-black stroke-black text-white hover:bg-blue-500"
                        @click="previous"
                    >Previous
                    </ChevronLeftIcon>
                    <ChevronRightIcon
                        :disabled="currentIndex === cards.length - 1"
                        class="absolute bottom-0 right-0 mb-16 h-7 w-7 cursor-pointer rounded fill-black stroke-black text-white hover:bg-blue-500"
                        @click="next"
                    >Next
                    </ChevronRightIcon>


                    <ArrowRightOnRectangleIcon
                        class="absolute right-0 bottom-0 mr-10 mb-4 h-7 w-7 cursor-pointer rounded-lg hover:bg-gray-200"
                        @click="usersLink">
                    </ArrowRightOnRectangleIcon>
                </slot>
            </h3>
            <template #icon>
                <component :is="cards[currentIndex].icon"/>
            </template>
        </Card>
    </transition>
</template>
<script setup>
import Card from "@/Components/Card.vue";
import {
    AcademicCapIcon,
    ArrowRightOnRectangleIcon,
    BookOpenIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    UserIcon,
    UsersIcon
} from "@heroicons/vue/24/outline";
import {onMounted, onUnmounted, ref} from "vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    teachersCount: {
        type: Number,
        required: true,
    },
    studentsCount: {
        type: Number,
        required: true,
    },
    subjectsCount: {
        type: Number,
        required: true,
    },
    adminsCount: {
        type: Number,
        required: true,
    },
});

function usersLink() {
    router.get('/admin/users')
}

let intervalId = null;

const currentIndex = ref(0)

const cards = ref([
    {title: 'Teachers', content: props.teachersCount, icon: UsersIcon},
    {title: 'Students', content: props.studentsCount, icon: AcademicCapIcon},
    {title: 'Subjects', content: props.subjectsCount, icon: BookOpenIcon},
    {title: 'Administrators', content: props.adminsCount, icon: UserIcon},
])

const next = () => {
    currentIndex.value = (currentIndex.value + 1) % cards.value.length
}

const previous = () => {
    if (currentIndex.value > 0) {
        currentIndex.value -= 1
    }
}

onMounted(() => {
    intervalId = setInterval(next, 5000);
});

onUnmounted(() => {
    clearInterval(intervalId);  // Stop the interval when the component is unmounted
});


</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.fade-enter-to,
.fade-leave-from {
    opacity: 1;
}
</style>
