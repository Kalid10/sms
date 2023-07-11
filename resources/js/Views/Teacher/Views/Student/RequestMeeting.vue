<template>
    <FormElement
        :title="`Meeting Request to ${guardian.user.name}`"
        subtitle=""
        @submit="submit"
    >
        <TextInput
            v-model="form.phone"
            label="Phone Number"
            placeholder="phone number"
            class="text-start"
        />
        <TextArea
            v-model="form.message"
            label="Message"
            required
            placeholder="message"
            class="text-start"
        />
    </FormElement>
</template>
<script setup>
import TextInput from "@/Components/TextInput.vue";
import FormElement from "@/Components/FormElement.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import TextArea from "@/Components/TextArea.vue";

const emit = defineEmits(["success"]);

const guardian = computed(() => usePage().props.student.guardian);

const message = computed(() => {
    return `Hello ${guardian.value.user.name}, your ward ${
        usePage().props.student.user.name
    } is requesting a meeting with you. Please contact ${
        usePage().props.student.user.name
    } on ${usePage().props.auth.user.phone_number} to schedule a meeting`;
});

const form = useForm({
    phone: guardian.value.user.phone_number,
    message: message,
});

const submit = () => {
    form.post(route("sms.send"), {
        onSuccess: () => {
            form.reset();
            emit("success");
        },
    });
};
</script>
<style scoped></style>
