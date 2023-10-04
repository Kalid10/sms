<template>
    <FormElement
        :title="`${$t('requestMeeting.meetingRequestTo')} ${
            guardian.user.name
        }`"
        :placeholder="$t('staffAbsenteesTable.userType')"
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
    return `Dear ${
        guardian.value.user.name
    },\n\nI would like to request a meeting with you to discuss your child's progress.\n\nThank you. please contact me at ${
        usePage().props.auth.user.phone_number
    }`;
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
