<template>
    <div class="flex w-full flex-col space-y-5 lg:text-start">
        <div class="flex w-full flex-col justify-between space-x-4">
            <div
                :class="
                    view === 'class'
                        ? 'text-xs'
                        : 'text-xs font-semibold lg:text-sm'
                "
            >
                {{ item.title }}
            </div>
        </div>
        <div
            class="flex flex-col space-y-0.5 text-[0.65rem] font-light lg:flex-row lg:space-x-1.5 lg:space-y-0 lg:text-start lg:text-[0.7rem] 2xl:text-xs"
        >
            <span
                class="flex space-x-1"
                :class="view === 'class' ? 'text-[0.6rem]' : ''"
            >
                <span>{{ item.batch_subject.subject.full_name }}</span>
                <span class="font-normal">{{
                    item.assessment_type.name + " ,"
                }}</span>
            </span>
            <span :class="view === 'class' ? 'text-[0.6rem]' : ''">
                On {{ moment(item.due_date).format("dddd MMMM Do") }}
                <span class="font-semibold lg:hidden">
                    <span class="font-light">for </span>
                    {{ item.batch_subject.batch.level.name
                    }}{{ item.batch_subject.batch.section }}
                </span>
            </span>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue";
import moment from "moment";

defineProps({
    item: {
        type: Object,
        required: true,
    },
    view: {
        type: String,
        required: true,
    },
});
</script>
