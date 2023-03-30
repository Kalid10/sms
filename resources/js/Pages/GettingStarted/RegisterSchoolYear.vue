<template>
    <div class="flex flex-col justify-center">
        <FormElement
            title="Create school year"
            subtitle="Register Name ,number of semesters and starting date"
            @submit="handleSubmit"
        >
            <TextInput v-model="formData.name" label="Year Name" placeholder="Name" :required="true" />
            <div class="flex w-1/2 ">
                <div class="">
                    <SelectInput
                        label="Number of semester"
                        placeholder="0"
                        :options="noOfSemester"
                        :model-value="selectedSemester"
                        @update:model-value="selectedSemester = $event"
                    />
                </div>
                <div class="pl-8">
                    <DatePicker v-model="formData.start_date" label="Start date"></DatePicker>
                </div>
            </div>
            <template #form-actions>
                <div>
                    <PrimaryButton title="Submit" @click="handleSubmit"/>
                </div>
            </template>
        </FormElement>
    </div>
</template>
<script setup>
import { ref } from "vue";
import TextInput from "@/Components/TextInput.vue";
import FormElement from "@/Components/FormElement.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm} from "@inertiajs/vue3";
import SelectInput from "@/Components/SelectInput.vue";
import DatePicker from "@/Components/DatePicker.vue";

const selectedSemester = ref(null);
const noOfSemester =[
    {value: 1, label: 1},
    {value: 2, label: 2},
    {value: 3, label: 3},
    {value: 4, label: 4},
    {value: 5, label: 5},
]
const selectedNoOfSemester = ref(1)
const formData = useForm({
    name: "",
    number_of_semesters: selectedNoOfSemester,
    start_date: null
});
function handleSubmit() {
        formData.post('/school-year/create',{
            onSuccess: () => {
                console.log("Success")
            },
            onError: (error) => {
                console.log("Error")
                console.log(error)
            }
        });
}
</script>
