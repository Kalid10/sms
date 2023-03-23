<template>
    <StudentFormElement
        subtitle="Fill in the information required about the new parent"
        title="Register new Student"
    >
        <StudentTextInput label="Name" placeholder="Full name of new student" required/>
        <StudentTextInput label="Level (Grade)" placeholder="Grade of the student" required/>

    </StudentFormElement>

    <StudentFormElement title="Register students by mass" subtitle="only upload an excel or csv file">
        <StudentFileInput @file-uploaded="onFileUploaded"/>
    </StudentFormElement>
</template>
<script setup>
import StudentFormElement from "@/Components/FormElement.vue"
import StudentTextInput from "@/Components/TextInput.vue"
import StudentFileInput from "@/Components/FileInput.vue"

defineEmits(['file-uploaded']);

const onFileUploaded = (file) => {
    console.log("File uploaded:", file);
};
</script>
