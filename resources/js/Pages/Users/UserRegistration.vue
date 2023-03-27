<template>
    <div class="flex flex-col justify-center">
        <UserRegistrationFormElement
            subtitle="Create admin"
            title="Register Admin"
            @submit="handleSubmit"
        >
            <TextInput v-model="formData.name" label="Name" placeholder="Full name" :required="true" />
            <TextInput v-model="formData.phone" label="Phone" placeholder="+251..." :required="true" />
            <TextInput v-model="formData.email" label="Email" placeholder="example@example.com" :required="true" />

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
import {router,useForm} from "@inertiajs/vue3";

const formData = useForm({
    name: "test name",
    phone: "09034932",
    email: "test@test.com",
    role: { value: "admin", label: "Administrator" },
});
//
//  funciton submit(){
//     formData.post(route('/registor'));
// }
function handleSubmit() {
    router.post('/register', {
        data: formData.value
    }, {
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
