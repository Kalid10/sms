<template>
    <div class="flex flex-col justify-center">
        <SemesterRegistrationFormElement
            title="Create school year"
            subtitle="Register Semester name,number of semesters and starting date"
            @submit="handleSubmit"
        >
            <TextInput v-model="formData.name" label="Year Name" placeholder="Name" :required="true" />
            <TextInput v-model="formData.noOfSemester" label="No of Semester" placeholder="" :required="true" />

            <div>
                <label for="Starting Date" class="mr-8">Starting Date</label><br>
                <input  v-model="formData.date" type="date">
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
import { ref } from "vue";
import TextInput from "@/Components/TextInput.vue";
import SemesterRegistrationFormElement from "@/Components/FormElement.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";


let today = new Date().toLocaleDateString();
console.log(today);


const formData = ref({
    name: "",
    noOfSemester: "",
    date: "",
});

function handleSubmit() {
    console.log(formData);
}

</script>
