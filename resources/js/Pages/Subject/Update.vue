<template>
    <UpdateModal v-model:view="isOpen">
        <UpdateFormElement title="Update Subject" @submit="update" @cancel="clear">
            <UpdateTextInput
                v-model="form.full_name" required :error="form.errors.full_name" label="Full Name"
                placeholder="full name"/>
            <UpdateTextInput
                v-model="form.short_name" required :error="form.errors.short_name" label="Short Name"
                placeholder="short name"/>
            <UpdateTextInput
                v-model="form.tags"
                required
                placeholder="Assign tags (separate multiple tags with comma)"
                label="Subject Tags"
            />
            <UpdateTextInput
                v-model="form.category"
                required
                placeholder="Enter a category"
                label="Subject Category"
            />
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
    category: "",
    tags: [],
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
        form.id = props.subject.id,
        form.category = props.subject.category,
        form.tags = props.subject.tags
}

// Clear form input
const clear = () => {
    form.reset();
}
</script>
