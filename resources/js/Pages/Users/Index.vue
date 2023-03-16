<template>

    <FormElement>

        <TextInput v-model="formData.name" label="Name" placeholder="Full name of new user" required/>
        <TextInput v-model="formData.position" label="Position" placeholder="Position of user"/>
        <SelectInput
            v-model="formData.role"
            :options="roleOptions" label="Role" placeholder="placeholder"
            required/>

    </FormElement>

</template>

<script setup>
import {ref} from "vue"
import FormElement from "@/Components/FormElement.vue"
import TextInput from "@/Components/TextInput.vue"
import SelectInput from "@/Components/SelectInput.vue"

const formData = ref({
    name: '',
    position: '',
    role: ''
})

const roleOptions = [
    {value: 'admin', label: 'Administrator'},
    {value: 'teacher', label: 'Teacher'},
    {value: 'student', label: 'Student'},
];

const showRegisterUser = ref(false)
const showModal = ref(false)
</script>

<style scoped>

</style>
