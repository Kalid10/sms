<template>
    <div
        v-if="inProgressSession"
        class="flex w-full flex-col items-center justify-evenly space-y-0.5 rounded-md py-2 px-3 text-center text-gray-200"
    >
        <div class="text-[0.6rem] font-light">Current Class</div>
        <div class="text-xl font-bold">
            {{
                inProgressSession.batch_schedule.batch_subject.subject.full_name
            }}
        </div>
        <div class="text-xs font-light">
            {{
                inProgressSession.batch_schedule.batch_subject.teacher.user.name
            }}
        </div>
    </div>
    <div v-else class="w-full text-center font-light text-white">
        No Active Class
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const inProgressSession = computed(() => usePage().props.in_progress_session);
</script>

<style scoped></style>
