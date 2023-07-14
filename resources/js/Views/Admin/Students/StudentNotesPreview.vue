<template>

    <Card class="!max-w-xs" :title="`Notes about ${student.user.name.split(' ').slice(-1)}`" icon>

        <template #icon>
            <PencilIcon />
        </template>

        <ul class="flex flex-col gap-4">
            <li v-for="(note, n) in notes" :key="n">
                <h3 class="text-sm">{{ note.title }}</h3>
                <h3 class="line-clamp-3 text-sm text-gray-500">
                    {{ note.body }}
                </h3>
                <div class="mt-1 flex w-full gap-2 text-xs">
                    <div class="flex items-center gap-1">
                        <div :class="note.party === 'parent' ? 'bg-yellow-600' : 'bg-green-600'" class="h-2.5 w-2.5 rounded-full"/>
                        <h3 class="">{{ note.author }}</h3>
                    </div>
                    <h3 class="italic text-gray-500">{{ note.date }}</h3>
                </div>
            </li>
        </ul>

        <TertiaryButton>{{ $t('studentNotesPreview.viewAll')}}</TertiaryButton>

    </Card>

</template>

<script setup>
import {notes} from "@/fake.js";
import {PencilIcon} from "@heroicons/vue/24/outline";
import Card from "@/Components/Card.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";

const student = computed(() => usePage().props.student)
</script>

<style scoped>

</style>
