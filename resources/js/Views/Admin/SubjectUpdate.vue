<template>
    <UpdateModal v-model:view="isUpdateSubjectFormOpened">
        <UpdateFormElement v-model:show-modal="isUpdateSubjectFormOpened" modal :title="$t('subjectUpdate.updateFormElementTitle')"  @submit="update" @cancel="clear">
            <UpdateTextInput
                v-model="form.full_name" required :error="form.errors.full_name"
                :label="$t('subjectUpdate.fullName')"
                :placeholder="$t('subjectUpdate.fullName')"
            />
            <UpdateTextInput
                v-model="form.short_name" required :error="form.errors.short_name"
                :placeholder="$t('subjectUpdate.shortName')"
                :label="$t('subjectUpdate.shortName')"
            />
            <UpdateTextInput
                v-model="tags"
                required
                :placeholder="$t('subjectUpdate.tagsPlaceholder')"
                :label="$t('subjectUpdate.tagsLabel')"
            />
            <UpdateTextInput
                v-model="form.category"
                required
                :placeholder="$t('subjectUpdate.categoryPlaceholder')"
                :label="$t('subjectUpdate.categoryLabel')"

            />
        </UpdateFormElement>
    </UpdateModal>
</template>
<script setup>
import {computed, ref, watch} from "vue";
import UpdateModal from "@/Components/Modal.vue";
import UpdateFormElement from "@/Components/FormElement.vue";
import UpdateTextInput from "@/Components/TextInput.vue";
import {router, useForm} from "@inertiajs/vue3";

const props = defineProps({
    open: {
        type: Boolean,
        required: true
    },
    subject: {
        type: Object,
        required: true
    },
})

const emits = defineEmits(['update:open'])

const subject = computed(() => props.subject)
watch(subject, () => {
    editSubject()
})

const isUpdateSubjectFormOpened = computed({
    get() {
        return props.open;
    },
    set(value) {
        emits('update:open', value)
    }
})

const form = useForm({
    full_name: "",
    short_name: "",
    id: "",
    category: "",
})

// Update Subject
const update = () => {
    router.post(route('subjects.update'), {
        ...form,
        tags: formTags.value
    }, {
        onSuccess: () => {
            clear();
            emits('update:open', false)
        }
    })
}

const tags = ref('')
const formTags = computed(() => tags.value.split(','))

function editSubject() {
    form.full_name = props.subject.full_name.full_name
    form.short_name = props.subject.short_name
    form.id = props.subject.id
    form.category = props.subject.category
    tags.value = props.subject.tags

}

// Clear form input
const clear = () => {
    form.reset();
    tags.value = '';
}
</script>
