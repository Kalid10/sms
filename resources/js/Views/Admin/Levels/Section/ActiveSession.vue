<template>
    <div
        class="flex min-h-full w-full flex-col items-center justify-center rounded-lg bg-gradient-to-bl from-violet-500 to-purple-500 py-2 text-center uppercase text-white shadow-sm"
    >
        <div
            v-if="batch.active_session.length > 0"
            class="flex h-full flex-col items-center space-y-1.5"
        >
            <div class="text-[0.65rem] font-light">
                {{ $t('sectionActiveSession.attending') }}
            </div>

            <div class="text-3xl font-semibold uppercase">
                {{ batch.active_session[0].batch_subject.subject.full_name }}
            </div>
            <div class="text-[0.65rem] font-light">{{ $t('sectionActiveSession.with') }}</div>

            <div
                class="cursor-pointer text-xs font-semibold uppercase underline-offset-2 hover:scale-105 hover:underline"
            >
                {{ batch.active_session[0].teacher.user.name }}
            </div>
        </div>
        <div v-else>{{ $t('sectionActiveSession.noActiveSession') }}</div>
    </div>
</template>

<script setup>
const props = defineProps({
    batch: {
        type: Object,
        required: true,
    },
});
</script>
<style scoped></style>
