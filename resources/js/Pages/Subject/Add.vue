<template>
    <add-modal v-model:view="isOpen">
        <add-form-element
            title="Add Subject"
            @submit="submit">
            <add-text-input v-model="form.full_name" label="Full Name" placeholder="full name" required/>
            <add-text-input v-model="form.short_name" label="Short Name" placeholder="short name" required/>
        </add-form-element>
    </add-modal>
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

// Add Subject
const submit = () => {
    form.post(route('subject.create'), {
        onSuccess: () => {
            isOpen.value = false
        }
    })
}
</script>
