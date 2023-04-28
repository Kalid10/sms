<template>

    <TableElement
        :header="false"
        :footer="false"
        :columns="subjectsConfig"
        :filterable="false"
        :data="subjects"
        :selectable="false"
    >
        <template #full_name-column="{data}">
            <Link :href="`/subjects/${data['id']}`" class="font-semibold hover:underline hover:underline-offset-2">{{ data['full_name'] }}</Link>
        </template>
        <template #short_name-column="{data}">
            <span class="text-xs text-gray-500">{{ data }}</span>
        </template>
        <template #tags-column="{data}">
            <div class="flex w-full items-center justify-center gap-2">
                <span v-for="(tag, t) in data" :key="t" class="text-xs">{{ toHashTag(tag) }}</span>
            </div>
        </template>
        <template #priority-column="{data}">
            <div :class="priorityLabel[data-1]" class="mx-auto h-2.5 w-2.5 rounded-full" />
        </template>
        <template #teacher-column="{data}">
            <div class="flex justify-end gap-2 text-xs">
                <span>{{ data['name'] }}</span>
                <span class="text-gray-500">{{ data['email'] }}</span>
            </div>
        </template>
        <template #updated_at-column="{data}">
            <span class="text-xs text-gray-500">
                {{ moment(data).fromNow() }}
            </span>
        </template>
    </TableElement>

</template>

<script setup>
import {computed} from "vue";
import {Link} from "@inertiajs/vue3";
import {toHashTag} from "@/utils.js";
import {usePage} from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import moment from "moment";

const batchesSubjects = computed(() => usePage().props.batches.map(batch => batch.subjects))
const subjects = computed(() => batchesSubjects.value.reduce((acc, batchSubjects) => {
    batchSubjects.forEach(subject => {
        if (! acc.map(s => s.subject_id).includes(subject.subject_id)) {
            acc.push(subject)
        }
    })
    return acc
}, []).map(subject => {
    return {
        ...subject.subject,
        full_name: {
            full_name: subject.subject.full_name,
            id: subject.subject.id
        },
        teacher: subject.teacher.user
    }
}))

const subjectsConfig = [
    {
        key: 'priority',
        type: 'custom',
        class: 'w-fit'
    },
    {
        key: 'full_name',
        name: 'Subject',
        type: 'custom',
        align: 'right'
    },
    {
        key: 'short_name',
        type: 'custom',
        align: 'left',
    },
    {
        key: 'tags',
        type: 'custom',
        class: 'w-full'
    },
    {
        key: 'teacher',
        name: 'Teacher',
        type: 'custom',
        align: 'right'
    },
    {
        key: 'updated_at',
        name: 'Last Updated',
        type: 'custom',
        align: 'right'
    }
]

const priorityLabel = [
    'bg-red-100',
    'bg-red-300',
    'bg-red-500',
    'bg-red-700',
    'bg-red-900',
]
</script>

<style scoped>

</style>
