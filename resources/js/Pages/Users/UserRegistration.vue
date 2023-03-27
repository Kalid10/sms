<template>
    <div class="flex flex-col justify-center">
        <UserRegistrationFormElement
            subtitle="Create admin"
            title="Register Admin"
            @submit="handleSubmit"
        >
            <TextInput v-model="formData.name" label="Name" placeholder="Full name" :required="true"/>

            <TextInput v-model="formData.phone" label="Phone" placeholder="+251..." :required="true" />
            <TextInput v-model="formData.email" label="Email" placeholder="example@example.com" :required="true" />

            <div>
                <div>
                    <h3 class="">Select position:</h3>
                    <RadioGroup v-model="selectedPosition" :name="'userPositions'" :options="userPositions" />
                </div>
            </div>

            <template #form-actions>
                <TertiaryButton :title="modal ? 'Close' : 'skip'" @click="skip"/>
                <PrimaryButton title="Submit" @click="handleSubmit"/>
            </template>
        </UserRegistrationFormElement>

    </div>
</template>

<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";
import UserRegistrationFormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import { ref } from "vue";
import {useForm} from "@inertiajs/vue3";
import RadioGroup from "@/Components/RadioGroup.vue";


 const userPositions = ref([
     {value:'Administrator',label:'Administrator'},
     {value:'principal',label:'principal'},
     {value:'Teacher',label:'Teacher'}
      ])

const selectedPosition = 'Administrator'

const formData = useForm({
    name: "Jontra Kebede",
    phone: "09034932",
    email: "jontra@nafyad.com",
    userType: selectedPosition ,
});

function handleSubmit() {
    formData.post('/register',{
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    });
}


function skip() {
    alert("Skip admin registration")
}
</script>
