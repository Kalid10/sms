<template>
    <FormElement title="SMS" @submit="submit">
        <TextInput v-model="form.phone" placeholder="phone number"/>
        <TextInput v-model="form.message" placeholder="message"/>
    </FormElement>
</template>
<script setup>
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";

const form = useForm({
    phone: "",
    message: "",
});

const submit = () => {
    form.post(route('sms.send'), {
        onSuccess: () => {
            form.reset();
        }
    });
}
</script>
