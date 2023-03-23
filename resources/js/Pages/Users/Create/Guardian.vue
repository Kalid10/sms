<template>
    <ParentFormElement
        subtitle="Fill in the information required about the new parent"
        title="Register new Parent"
    >
        <ParentTextInput label="Name" placeholder="Full name of new parent" required/>
        <ParentTextInput label="Child's Level (Grade)" placeholder="Grade of the child" required/>

    </ParentFormElement>
</template>
<script setup>
import ParentFormElement from "@/Components/FormElement.vue";
import ParentTextInput from "@/Components/TextInput.vue";</script>
