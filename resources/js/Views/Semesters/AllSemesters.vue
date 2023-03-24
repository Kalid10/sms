<template>

    <div class="container mx-auto flex w-full flex-col items-center gap-3">
        <Heading value="All Semesters" />
        <div class="flex w-full flex-col gap-3 xl:flex-row">
            <TextInput
                v-model="query"
                class="grow" placeholder="Search for Semesters by name"
            />
            <div class="flex w-full flex-col gap-3 sm:flex-row xl:w-fit">
                <RadioGroup v-model="selectedStatus" name="status" :options="statuses" />
                <SelectInput v-model="selectedYear" class="w-full sm:w-1/3 sm:max-w-xs xl:w-44" :options="years" placeholder="Filter by School Year" />
            </div>
        </div>
        <div v-if="filtersApplied" class="flex min-h-8 w-full flex-wrap items-center justify-start gap-3">
            <span class="text-sm text-gray-500">Applied filters:</span>
            <div class="flex flex-wrap items-start justify-center gap-3">
                <ButtonLabel v-if="!! query" :value="query" @click="clearFilter('query')" />
                <ButtonLabel v-if="!! selectedStatus" :value="selectedStatus" @click="clearFilter('status')" />
                <ButtonLabel v-if="selectedYear !== 0" :value="selectedYearLabel" @click="clearFilter('school_year')" />
            </div>
        </div>
        <SemestersList :semesters="semesters.slice().reverse()" />
    </div>

</template>

<script setup>
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import RadioGroup from "@/Components/RadioGroup.vue";
import Heading from "@/Components/Heading.vue";
import SemestersList from "@/Views/Semesters/SemestersList.vue"
import ButtonLabel from "@/Components/ButtonLabel.vue"
import {computed, ref} from "vue";
import {semesters as fake, school_years as years, statuses} from "@/fake";

const semesters = computed(() => {
    return fake.filter((semester) => {
        return semester.status.includes(selectedStatus.value) &&
            semester.school_year.includes(selectedYearLabel.value === 'All years' ? '' : selectedYearLabel.value) &&
            semester.name.toLowerCase().includes(query.value.toLowerCase())
    })
})
const query = ref('')
const selectedStatus = ref('')
const selectedYear = ref(0)
const selectedYearLabel = computed(() => years.filter(year => year.value === selectedYear.value)[0].label)
const filtersApplied = computed(() => !! selectedStatus.value || selectedYear.value !== 0 || !! query.value)

function clearFilter(attribute) {

    switch (attribute) {

        case 'status':
            selectedStatus.value = ''
            break;

        case 'school_year':
            selectedYear.value = 0
            break;

        case 'query':
            query.value = ''
            break;

        default:
            break;
    }

}
</script>

<style scoped>

</style>
