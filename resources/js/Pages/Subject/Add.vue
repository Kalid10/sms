<template>
    <AddModal v-model:view="isOpen">
        <AddFormElement
            title="Add Subject"
            @cancel="clear"
            @submit="submit">
            <AddTextInput v-model="form.full_name" label="Full Name" placeholder="full name" required/>
            <AddTextInput v-model="form.short_name" label="Short Name" placeholder="short name" required/>
        </AddFormElement>
    </AddModal>
</template>
<script setup>
import {ref} from "vue";
import AddModal from "@/Components/Modal.vue";
import AddFormElement from "@/Components/FormElement.vue";
import AddTextInput from "@/Components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    toggle: {
        type: Boolean,
        required: true
    }
})

const isOpen = ref(props.toggle)

const form = useForm({
    full_name: '',
    short_name: '',
})

// Add subject
const submit = () => {
    form.post(route('subjects.create'), {
        onSuccess: () => {
            isOpen.value = false
        }
    })
}

// Clear form input
const clear = () => {
    form.reset();
}
</script>
