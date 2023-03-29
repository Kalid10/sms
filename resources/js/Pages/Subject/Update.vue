<template>
    <UpdateModal v-model:view="isOpen">
        <UpdateFormElement title="Update Subject" @submit="update" @cancel="clear">
            <UpdateTextInput
                v-model="form.full_name" :error="form.errors.full_name" label="Full Name"
                placeholder="full name"/>
            <UpdateTextInput
                v-model="form.short_name" :error="form.errors.short_name" label="Short Name"
                placeholder="short name"/>
        </UpdateFormElement>
    </UpdateModal>
</template>
<script setup>
import {onMounted, ref} from "vue";
import UpdateModal from "@/Components/Modal.vue";
import UpdateFormElement from "@/Components/FormElement.vue";
import UpdateTextInput from "@/Components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    toggle: {
        type: Boolean,
        required: true
    },
    subject: {
        type: Object,
        required: true
    },
})

const isOpen = ref(props.toggle)

onMounted(() => {
    isOpen.value = true;

    if (props.subject) {
        editSubject();
    }
});

const form = useForm({
    full_name: "",
    short_name: "",
    id: "",
})

// Update Subject
const update = () => {
    form.post(route('subjects.update'), {
        onSuccess: () => {
            isOpen.value = false
        }
    })
}

// Edit Subject
function editSubject() {
    form.full_name = props.subject.full_name,
        form.short_name = props.subject.short_name,
        form.id = props.subject.id
}

// Clear form input
const clear = () => {
    form.reset();
}
</script>
