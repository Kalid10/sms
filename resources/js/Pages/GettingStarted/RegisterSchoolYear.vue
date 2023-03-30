<template>

    <form class="flex min-h-max w-full flex-col items-center justify-center py-4">

        <Heading size="sm" class="mb-1 text-center !font-normal text-black">Register a New School Year</Heading>

        <p contenteditable="true" type="text" class="max-w-[18rem] appearance-none border-none p-0 text-center text-4xl font-bold">
            {{ formData.name }}
        </p>

        <div class="mt-8 flex flex-col gap-3">
            <DatePicker v-model="formData.start_date" :minimum="new Date()" :label-location="!! formData.start_date ? 'inside' : ''" :label="!! formData.start_date ? 'Choose a Start Date' : ''" placeholder="Choose a Start Date" class="w-72" />
            <SelectInput v-model="formData.number_of_semesters" class="w-72" :options="noOfSemesters" :label-location="!! formData.number_of_semesters ? 'inside' : ''" :label="!! formData.number_of_semesters ? 'Number of Semesters' : ''" placeholder="Number of Semesters" />
            <PrimaryButton :disabled="! formComplete" class="w-72" @click="handleSubmit">Create and Proceed</PrimaryButton>
        </div>

    </form>

</template>
<script setup>
import { computed } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm, router} from "@inertiajs/vue3";
import SelectInput from "@/Components/SelectInput.vue";
import DatePicker from "@/Components/DatePicker.vue";
import Heading from "@/Components/Heading.vue";

const noOfSemesters = [
    {value: 1, label: 1},
    {value: 2, label: 2},
    {value: 3, label: 3},
    {value: 4, label: 4},
    {value: 5, label: 5},
]
const formData = useForm({
    name: "2022/2023",
    number_of_semesters: null,
    start_date: null
});

const formComplete = computed(() => {
    return !!formData.name && !!formData.number_of_semesters && !!formData.start_date
})

function handleSubmit() {
        formData.post('/school-year/create',{
            onSuccess: () => {
                console.log("Success")
                router.get('/getting-started/register-batches')
            },
            onError: (error) => {
                console.log("Error")
                console.log(error)
            }
        });
}
</script>
