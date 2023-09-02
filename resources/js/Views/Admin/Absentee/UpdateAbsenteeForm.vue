<template>
    <FormElement title="Update Absentee" @submit="submit" @cancel="form.reset">
        <TextArea
            v-model="form.reason"
            rows="3"
            label="Reason"
            placeholder="Reason"
            :error="form.errors.reason"
        />
        <Toggle v-model="form.is_leave" label="Is this a valid leave?" />
    </FormElement>
</template>
<script setup>
import FormElement from "@/Components/FormElement.vue";
import { useForm } from "@inertiajs/vue3";
import Toggle from "@/Components/Toggle.vue";
import TextArea from "@/Components/TextArea.vue";

const emit = defineEmits(["update"]);
const props = defineProps({
    absentee: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    id: props.absentee.id,
    reason: props.absentee.reason,
    is_leave: props.absentee.is_leave,
});

const submit = () => {
    form.patch("/admin/absentee/staff/update", {
        preserveScroll: true,
        onSuccess: () => {
            emit("update");
        },
    });
};
</script>
<style scoped></style>
