<template>
    <TeacherFormElement
        subtitle="Fill in the information required about the new teacher"
        title="Register new teacher"
    >
        <TeacherTextInput label="Name" placeholder="Full name of new teacher" required/>
        <TeacherTextInput label="Assign levels" required/>

    </TeacherFormElement>
</template>
<script setup>
import TeacherFormElement from "@/Components/FormElement.vue";
import TeacherTextInput from "@/Components/TextInput.vue";</script>
