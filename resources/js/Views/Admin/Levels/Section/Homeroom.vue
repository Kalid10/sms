<template>
    <div
        class="flex h-fit w-full items-center justify-center rounded-lg bg-white py-3"
    >
        <div
            v-if="batch.home_room_teacher"
            class="flex h-full w-full flex-col items-center justify-center space-y-2"
        >
            <div
                class="cursor-pointer font-semibold hover:scale-105 hover:underline hover:underline-offset-2"
            >
                {{ batch.home_room_teacher.teacher.user.name }}
            </div>
            <div class="text-sm font-light">
                {{ $t('sectionHomeroom.homeroomTeacher')}}
            </div>
        </div>
        <div v-else class="h-full">
            <EmptyView
                :title="$t('sectionHomeroom.noHomeroomTeacherAssigned')"
                :link-title="$t('sectionHomeroom.assignHomeroom')"
                link-url="/homeroom/assign"
                class="!h-full"
            />
        </div>
    </div>
</template>

<script setup>
import EmptyView from "@/Views/EmptyView.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    batch: {
        type: Object,
        required: true,
    },
});
</script>
<style scoped></style>
