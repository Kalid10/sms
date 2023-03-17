<template>
    <update-modal v-model:view="isOpen">
        <update-form-element subtitle="update" title="Update Subject" @submit="update">
            <update-text-input v-model="form.full_name" label="Full Name" placeholder="full name"/>
            <update-text-input v-model="form.short_name" label="Short Name" placeholder="short "/>
        </update-form-element>
    </update-modal>
</template>
<script setup>
import {ref} from "vue";
import UpdateModal from "@/Components/Modal.vue";
import UpdateFormElement from "@/Components/FormElement.vue";
import UpdateTextInput from "@/Components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    toggle: {
        type: Boolean,
        required: true
    },
    subjects: {
        type: Object,
        required: true
    }
})

const isOpen = ref(props.toggle)

const form = useForm({
    full_name: props.subjects.full_name,
    short_name: props.subjects.short_name,
    id: props.subjects.id,
})

// Update Subject
const update = () => {
    form.put(route('subject.update', form.id), {
        onSuccess: () => {
            isOpen.value = false
        }
    })
}
</script>
