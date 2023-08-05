<template>
    <div class="body-font flex w-full text-gray-600">
        <div class="container mx-auto space-y-6 px-5">
            <div
                class="mx-auto my-8 flex flex-col items-center border-b border-gray-200 pb-10 sm:flex-row lg:w-full"
            >
                <div
                    class="inline-flex h-20 w-20 shrink-0 items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-500 sm:mr-10 sm:h-32 sm:w-32"
                >
                    {{ props.assessment.title }}
                </div>
                <div class="mt-6 grow text-center sm:mt-0 sm:text-left">
                    <h2
                        class="title-font mb-2 text-lg font-medium text-gray-900"
                    >
                        {{ props.assessment.assessment_type.name }}
                    </h2>
                    <p class="text-base leading-relaxed">
                        {{ props.assessment.description }}
                    </p>
                </div>
            </div>
            <div
                class="mx-auto mb-10 flex flex-col items-center pb-10 sm:flex-row lg:w-full"
            >
                <div
                    class="mt-6 grow space-y-3 text-center sm:mt-0 sm:text-left"
                >
                    <h2
                        class="title-font mb-2 text-lg font-medium text-gray-900"
                    >
                        {{ props.assessment.batch_subject.subject.full_name }}
                    </h2>
                    <p>{{ props.assessment.batch_subject.subject.tags }}</p>
                    <p class="text-base leading-relaxed">
                        <span>Grade: </span>
                        {{ props.assessment.batch_subject.batch.level.name }}
                        {{ props.assessment.batch_subject.batch.section }}
                    </p>
                </div>
                <div
                    class="inline-flex h-20 w-20 shrink-0 items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-500 sm:mr-10 sm:h-32 sm:w-32"
                >
                    {{ props.assessment.batch_subject.subject.short_name }}
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { defineProps } from "vue";

const props = defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});
</script>
