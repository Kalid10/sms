<template>
    <div class="flex flex-col justify-center">
        <SemesterRegistrationFormElement
            title="Create school year"
            subtitle="Register Name ,number of semesters and starting date"
            @submit="handleSubmit"
        >
            <TextInput v-model="formData.name" label="Year Name" placeholder="Name" :required="true" />

            <div class="flex flex-row">
                <div class="p-2">

                    <SelectInput :options="NoOfSemistor"></SelectInput>
                    <TextInput v-model="formData.noOfSemester" label="No of Semester" placeholder="" :required="true" />

                </div>


                <div class="p-2">
                    <label for="Starting Date" class="mr-8">Starting Date</label><br>
                    <input  v-model="formData.start_date" type="date">
                </div>
            </div>

            <template #form-actions>
                <div>
                    <PrimaryButton title="Submit" @click="handleSubmit"/>
                </div>
            </template>
        </SemesterRegistrationFormElement>
    </div>
</template>


<script setup>
import { ref,computed } from "vue";
import TextInput from "@/Components/TextInput.vue";
import SemesterRegistrationFormElement from "@/Components/FormElement.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm} from "@inertiajs/vue3";
import SelectInput from "@/Components/SelectInput.vue";

const NoOfSemistor =[
    {value: 1, label: 1},
    {value: 2, label: 2},
    {value: 3, label: 3},
    {value: 4, label: 4},
    {value: 5, label: 5},

]

const formData = useForm({
    name: "",
    noOfSemester: "",
    start_date: null
});

function handleSubmit() {
        formData.post('school-year/create',{
            onSuccess:()=>{
                alert("done");
    }
        });
}


numberOfSemistor
</script>
