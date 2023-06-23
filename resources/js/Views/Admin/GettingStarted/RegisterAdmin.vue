<template>
    <FormElement
class="max-w-2xl"
                 :title="$t('registerAdmin.title')"
                 :subtitle="$t('registerAdmin.subtitle')"
                 @submit="handleSubmit"
    >
        <TextInput
            v-model="formData.name"
            :label="$t('registerAdmin.nameLabel')"
            :placeholder="$t('registerAdmin.namePlaceholder')"
            :required="true"/>
        <TextInput
            v-model="formData.phone"
            :label="$t('registerAdmin.phoneLabel')"
            placeholder="+251..."
            :required="true" />
        <TextInput
            v-model="formData.email"
            :label="$t('registerAdmin.emailLabel')"
            placeholder="example@example.com"
            :required="true" />
        <RadioGroupPanel v-model="adminType" :options="adminTypes"  name="admin-types"/>
    </FormElement>
</template>

<script setup>
import { ref } from "vue";
import FormElement from "@/Components/FormElement.vue";
import RadioGroupPanel from "@/Components/RadioGroupPanel.vue";
import TextInput from "@/Components/TextInput.vue";
import {router, useForm} from "@inertiajs/vue3";

const adminType = ref('unit-leader');
const adminTypes = [
    {
        id: 'unit-leader',
        value: 'unit-leader',
        label: 'Unit Leader',
        description: 'Unit Leader responsible for managing the batches. Has manage access to batches, schedules, students, teachers, assessments'
    },
    {
        id: 'secretary',
        value: 'secretary',
        label: 'Secretary',
        description: 'Secretary responsible for managing the batches. Has manage access to students, guardians, teachers, schedules'
    },
    {
        id: 'principal',
        value: 'principal',
        label: 'Principal',
        description: 'Principal has access to all resources in the system. Can view, update and delete all resources'
    },
]

const formData = useForm({
    name: "",
    phone: "",
    email: "",
    position: adminType,
    type: "admin",
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

</script>
