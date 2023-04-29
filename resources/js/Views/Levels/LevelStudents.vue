<template>

    <div class="flex flex-col gap-3">

        <TableElement
            v-if="!! students"
            :title="selectedSection && `Section ${selectedSection} Students List` || 'Students List'"
            :subtitle="`Students enrolled in ${parseLevel(level.name)} for ${level.batches[0].school_year.name}`"
            class="w-fit"
            :row-actionable="true"
            :selectable="false"
            :footer="false"
            :columns="studentsConfig"
            :data="students"
        >
            <template #filter>
                <RadioGroup v-model="selectedSection" :options="sectionsRadioButtons" name="sections" />
            </template>

            <template #empty-data>
                <div class="flex flex-col items-center justify-center">
                    <ExclamationTriangleIcon class="mb-2 h-6 w-6 text-negative-50"/>
                    <p class="text-sm font-semibold">
                        No data found
                    </p>
                    <p class="text-sm text-gray-500">
                        No student has been enrolled in this section
                    </p>
                </div>
            </template>

            <template #row-actions="{ row }">
                <Link :href="'/students/' + row['student_id']" class="flex flex-col items-center gap-1">
                    <EyeIcon class="h-3 w-3 stroke-2 transition-transform duration-150 hover:scale-125"/>
                </Link>
                <Link :href="'/users/' + row['student_id'] + '/edit'" class="flex flex-col items-center gap-1">
                    <ArrowPathIcon
                        class="h-3 w-3 stroke-2 transition-all duration-150 hover:scale-125 hover:stroke-blue-700"/>
                </Link>
                <Link :href="'/users/' + row['student_id'] + '/delete'" class="flex flex-col items-center gap-1">
                    <ArchiveBoxXMarkIcon
                        class="h-3 w-3 stroke-2 transition-all duration-150 hover:scale-125 hover:stroke-red-700"/>
                </Link>
            </template>
        </TableElement>

    </div>

</template>

<script setup>
import {ref, computed, onMounted} from "vue";
import {Link, router, usePage} from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import RadioGroup from "@/Components/RadioGroup.vue";
import {parseLevel} from "@/utils.js";
import {ArchiveBoxXMarkIcon, ArrowPathIcon, EyeIcon, ExclamationTriangleIcon} from "@heroicons/vue/24/outline/index.js";

const level = computed(() => usePage().props.level)
const students = computed(() => (usePage().props.students || [])
    .filter(student => {
        if (!! selectedSection.value) {
            return student?.section === selectedSection.value
        }
        return true
    }))
const batches = computed(() => usePage().props.batches)

const selectedSection = ref()
const sectionsRadioButtons = computed(() => usePage().props.batches.map(batch => {
    return {
        label: `Section ${batch.section}`,
        value: batch.section
    }
}))

const studentsConfig = [
    {
        name: '',
        key: 'name',
        class: 'font-semibold',
        align: 'right'
    },
    {
        name: '',
        key: 'email',
        class: 'text-gray-500 text-xs w-full',
        align: 'left'
    },
    {
        name: '',
        key: 'username',
        class: 'text-gray-500 text-xs font-semibold'
    },
    {
        name: '',
        key: 'gender',
        type: 'enum',
        options: ['male', 'female']
    },
    {
        name: 'Date of Birth',
        key: 'date_of_birth',
    },
    {
        name: 'Last updated',
        key: 'updated_at',
        class: 'text-gray-500 text-xs',
        align: 'right'
    }
]

onMounted(() => {

    router.reload({
        only: ["students"],
    })

})
</script>

<style scoped>

</style>
