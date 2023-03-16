<template>

    <TableElement
        :columns-config="columnConfigs" :data="users"
        actionable
        selectable
        subtitle="List of personnel registered on your system, with user types and contact information"
        title="Users List"
    />

    <FormElement
        subtitle="Fill in the information required about the new user" title="Register new User">

        <TextInput v-model="formData.name" label="Name" placeholder="Full name of new user" required/>
        <TextInput v-model="formData.position" label="Position" placeholder="Position of user"/>
        <SelectInput
            v-model="formData.role"
            :options="roleOptions" label="Role" placeholder="placeholder"
            required
        />

    </FormElement>

</template>

<script setup>
import {ref} from "vue"
import {users} from "@/fake";
import FormElement from "@/Components/FormElement.vue"
import TextInput from "@/Components/TextInput.vue"
import SelectInput from "@/Components/SelectInput.vue"
import TableElement from "@/Components/TableElement.vue"

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

const columnConfigs = [
    {
        name: 'Full Name',
        key: 'name',
        link: '/users/{id}',
    },
    {
        name: 'Email',
        key: 'email',
        link: 'mailto:{email}'
    },
    {
        name: 'Phone Number',
        key: 'phone',
    },
    {
        name: 'User Type',
        key: 'type',
        link: '/users/{id}/edit'
    },
    {
        name: 'User Roles',
        key: 'roles',
        link: 'https://google.com'
    },
    {
        name: 'Status',
        key: 'active',
    },
]

const showRegisterUser = ref(true)
const showModal = ref(false)
</script>

<style scoped>

</style>
