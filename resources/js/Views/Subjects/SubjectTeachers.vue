<template>

    <TableElement
        :data="teacher"
        :columns="config"
        :selectable="false"
        :header="false"
    >

        <template #filter>

            <div class="flex items-center justify-between gap-3">
                <div class="flex flex-col">
                    <h3 class="font-semibold">Mathematics Teachers</h3>
                    <h3 class="text-sm text-gray-500">List of teachers in the Mathematics Faculty</h3>
                </div>
                <RadioGroup v-model="filteredLevelCategory" :options="levelCategoryOptions" name="levelCategories" />
            </div>

        </template>

        <template #teacher-column="{data}">

            <Link :href="`/teachers/${data['teacher_id']}`" class="group flex items-center gap-3">
                <div class="flex flex-col">
                    <div class="flex items-center gap-1.5">
                        <span class="font-semibold underline-offset-2 group-hover:underline">{{ data['name'] }}</span>
                        <span :class="genderLabels[data['gender']]" class="scale-[.7] rounded-xl px-2.5 py-0.5 font-semibold">{{ data['gender'] }}</span>
                    </div>
                    <span class="text-gray-500">{{ data['email'] }}</span>
                </div>
            </Link>

        </template>

        <template #batches-column="{data}">

            <div class="flex items-center justify-end gap-3 py-2">
                <Link v-for="(batch, b) in data" :key="b" :class="levelCategoryLabels[levelCategories.indexOf(batch['level_category'])]" class="flex w-24 flex-col items-start rounded-md border py-1 pl-2 pr-4">
                    <span class="text-xs">{{ batch['level_category'] }}</span>
                    <span class="text-xs font-semibold">{{ parseLevel(batch['name']) }} {{ batch['section'] }}</span>
                </Link>
            </div>

        </template>

    </TableElement>

</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import {usePage, Link, router} from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import {genderLabels, levelCategoryLabels, parseLevel} from "@/utils.js";
import RadioGroup from "@/Components/RadioGroup.vue";

const filteredLevelCategory = ref(null)

const teacher = computed(() => usePage().props.teacher?.filter((teacher) => {
    if (!! filteredLevelCategory.value) {
        return teacher.batches.map(batch => batch['level_category']).includes(filteredLevelCategory.value)
    }
    return true
}) || [])
const levelCategories = ref([])
const levelCategoryOptions = computed(() => levelCategories.value.map(levelCategory => {
    return {
        label: levelCategory,
        value: levelCategory
    }
}))

const config = [
    {
        key: 'teacher',
        name: 'Teacher',
        type: 'custom',
        align: 'left',
        class: 'w-full'
    },
    {
        key: 'batches',
        name: 'Grades',
        type: 'custom',
        align: 'right'
    }
]

onMounted(() => {

    router.reload({
        only: ['teacher'],
        onSuccess() {
            levelCategories.value = teacher.value.reduce((acc, t) => {
                t.batches.forEach(batch => {
                    if (! acc.includes(batch['level_category'])) {
                        acc.push(batch['level_category'])
                    }
                })
                return acc
            }, [])
        }
    },)

})

</script>

<style scoped>

</style>
