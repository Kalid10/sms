<template>
    <div class="my-4 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
        <Card
            subtitle="subtitle"
            title="Teachers" class="cursor-pointer hover:bg-gray-100 md:col-span-1 md:row-span-1 md:w-full"
            icon
            @click="teachersLink"
        >
            <h3 class="flex flex-col">
                <slot>
                         <span class="text-2xl font-semibold">
                            {{ teachersCount }}
                         </span>

                </slot>
            </h3>
            <template #icon>
                <UserIcon/>
            </template>
        </Card>
        <Card
            subtitle="subtitle"
            title="Students" class="cursor-pointer hover:bg-gray-100 md:col-span-1 md:row-span-1 md:w-full"
            icon
            @click="studentsLink"
        >
            <h3 class="flex flex-col">
                <slot>
                        <span class="text-2xl font-semibold">
                    {{ studentsCount }}
                </span>

                </slot>
            </h3>
            <template #icon>
                <UsersIcon/>
            </template>
        </Card>
        <Card
            subtitle="subtitle"
            title="Subjects" class="cursor-pointer hover:bg-gray-100 md:col-span-1 md:row-span-1 md:w-full"
            icon
            @click="subjectsLink"
        >
            <h3 class="flex flex-col">
                        <span class="text-2xl font-semibold">
                    {{ subjectsCount }}
                </span>
            </h3>
            <template #icon>
                <BookOpenIcon/>
            </template>
        </Card>
        <Card
            subtitle="subtitle"
            title="Administrators" class="cursor-pointer hover:bg-gray-100 md:col-span-1 md:row-span-1 md:w-full"
            icon
        >
            <h3 class="flex flex-col">
                <slot>
                         <span class="text-2xl font-semibold">
                            {{ adminsCount }}
                         </span>
                </slot>
            </h3>
            <template #icon>
                <UserIcon/>
            </template>
        </Card>
    </div>
</template>
<script setup>
import Card from "@/Components/Card.vue";
import {BookOpenIcon, UserIcon, UsersIcon} from "@heroicons/vue/24/outline";
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

function studentsLink() {
    router.get("admin/students/");
}

function teachersLink() {
    router.get("admin/teachers/");
}

function subjectsLink() {
    router.get("admin/subjects/")
}


</script>
