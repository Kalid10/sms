<template>
    <div class="h-full">
        <div
            v-if="teachers?.length && !isLoading"
            class="h-full overflow-y-auto py-2"
        >
            <div
                v-for="(item, index) in teachers"
                :key="index"
                class="flex cursor-pointer flex-col space-y-2 px-2 py-3 text-sm hover:rounded-lg hover:bg-brand-400 hover:text-white"
                :class="index % 2 === 0 ? 'bg-brand-50' : 'bg-white'"
                @click="$emit('selectedTeacher', item)"
            >
                <div>
                    {{ item.user.name }}({{ item.user.phone_number }}), Active
                    weekly sessions
                    {{ sumWeeklyFrequency(item.active_batch_subjects) }}
                </div>
            </div>
        </div>
        <div v-else class="scrollbar-hide h-full overflow-y-auto">
            <div v-if="!isLoading" class="py-2">
                <div class="text-center text-sm font-light">
                    No teachers found!
                </div>
            </div>
        </div>
        <Loading
            v-if="isLoading"
            type="bounce"
            color="secondary"
            class="!py-5"
        />
    </div>
</template>
<script setup>
import Loading from "@/Components/Loading.vue";
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const emit = defineEmits(["selectedTeacher"]);
const isLoading = ref(false);
const teachers = computed(() => usePage().props.teachers);

function sumWeeklyFrequency(batchSubjects) {
    return batchSubjects.reduce(
        (total, subject) => total + subject.weekly_frequency,
        0
    );
}
</script>

<style scoped></style>
