<template>

    <FormElement class="max-w-2xl" subtitle="Register and assign a position with predefined roles into the system" title="New Admin Registration">

        <TextInput v-model="name" label="Name" placeholder="Enter name" />
        <RadioGroupPanel v-model="adminType" :options="adminTypes"  name="admin-types"/>

    </FormElement>

</template>

<script setup>
import { ref } from "vue";
import FormElement from "@/Components/FormElement.vue";
import RadioGroupPanel from "@/Components/RadioGroupPanel.vue";
import TextInput from "@/Components/TextInput.vue";

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
</script>

<style scoped>

</style>
