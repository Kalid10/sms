<template>
    <div class="b h-32 min-w-full rounded-lg">
        <div class="text-2xl font-medium">Assessments</div>

        <Modal v-model:view="showModal">
            <FormElement title="Assessment Name" class="my-2" @submit="handleSubmit">

                <DatePicker v-model="form.due_date" placeholder="Due Date"/>

                <!--                Todo: Change the select options to SelectInput component-->
                <select v-model="form.assessment_type_id">
                    <option
                        v-for="type in selected_batch_assessment_types" :key="type.id"
                        :value="type.id">
                        {{ type.name }}
                    </option>
                </select>
                <select v-model="form.batch_subject_id">
                    <option
                        v-for="batch_subject in teacher.batch_subjects" :key="batch_subject.id"
                        :value="batch_subject.id">
                        {{ batch_subject.subject.full_name }}
                        {{ batch_subject.batch.level.name }}
                        {{ batch_subject.batch.section }}
                    </option>
                </select>

            </FormElement>
        </Modal>

        <div class="flex justify-center">
            <PrimaryButton class="my-2 w-fit px-5" @click="showModal = true">View Assessments</PrimaryButton>
        </div>
    </div>
</template>

<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FormElement from "@/Components/FormElement.vue";
import {computed, onMounted, ref} from "vue";
import Modal from "@/Components/Modal.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import DatePicker from "@/Components/DatePicker.vue";

const showModal = ref(false);
const teacher = usePage().props.teacher;
const all_assessment_types = usePage().props.assessment_type;

const selected_batch_assessment_types = computed(() => {
    if (form.batch_subject_id) {
        const batch_subject = teacher.batch_subjects.find(batch_subject => batch_subject.id === form.batch_subject_id);
        if (batch_subject) {
            const level_category_id = batch_subject.batch.level.level_category.id;
            return all_assessment_types.filter(type => type.level_category_id === level_category_id);
        }
    }
    return [];
});


const form = useForm({
    assessment_type_id: '',
    batch_subject_id: teacher.batch_subjects.length > 0 ? teacher.batch_subjects[0].id : '',
    due_date: new Date()
});

onMounted(() => {
    form.assessment_type_id = selected_batch_assessment_types.value.length > 0 ? selected_batch_assessment_types.value[0].id : '';
});

function handleSubmit() {
    form.post('/teacher/assessments/create', {
        onSuccess: () => {
            showModal.value = false;
        }
    });
}
</script>


<style scoped>

</style>
