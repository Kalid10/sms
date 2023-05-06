<template>
    <div class="h-fit min-w-full rounded-lg">
        <div class="flex w-11/12 justify-between xl:w-10/12">
            <div class="text-2xl font-medium">Recent Assessments</div>
            <div
                class="flex w-fit items-center justify-center space-x-1 rounded-md px-3 text-sm font-medium underline underline-offset-2 hover:scale-105 hover:cursor-pointer">
                <div>SEE ALL</div>
            </div>
        </div>

        <div class="flex flex-col">
            <div
                v-if="teacher.assessments.length > 0"
                class="mt-2 flex w-10/12 flex-col justify-center divide-y-2 py-2">
                <div
                    v-for="(item,index) in teacher.assessments"
                    :key="index"
                    class="mt-2 flex items-center justify-evenly py-2">

                    <div class="flex w-2/12 flex-col items-center justify-center text-center">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl">
                            <component
                                :is="getIconAndColor(item.assessment_type.name).icon"
                                :class="getIconAndColor(item.assessment_type.name).color" class="w-5"/>
                        </div>
                        <div class="mt-1.5 text-xs font-light uppercase">
                            {{ item.batch_subject.batch.level.name }}{{ item.batch_subject.batch.section }}
                        </div>
                    </div>

                    <div class="flex w-8/12 flex-col space-y-4">
                        <div class="flex w-full flex-col justify-between space-x-4">
                            <div class="font-medium">{{ item.title }}</div>
                        </div>
                        <div class="flex space-x-1.5 text-start text-sm font-light">
                            <div>{{ item.batch_subject.subject.full_name }}</div>
                            <div class="font-medium">{{ item.assessment_type.name }}</div>
                            <div>
                                <span class="font-base">on, </span>{{ moment(item.due_date).format('dddd MMMM Do') }}
                            </div>
                        </div>
                    </div>

                    <div class="flex w-2/12 flex-col items-end space-y-2">
                        <div class="flex font-light uppercase">
                            <div class="mr-2 text-3xl font-bold">{{ item.maximum_point }}</div>
                            <div class="flex flex-col space-y-0.5 text-xs font-medium">
                                <div>MAX</div>
                                <div>POINTS</div>
                            </div>
                        </div>
                        <div
                            class="text-xs text-neutral-600 underline-offset-1 hover:cursor-pointer hover:text-black hover:underline">
                            LessonPlan #{{ item.id }}
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="flex flex-col items-center space-y-4 ">
                <ExclamationTriangleIcon class="h-6 w-6 text-gray-500"/>
                <div>No Assessments Found!</div>
                <PrimaryButton>Go To Assessments</PrimaryButton>
            </div>
        </div>

        <Modal v-model:view="showModal">
            <FormElement title="Assessment Name" class="my-2" @submit="handleSubmit">
                <TextInput v-model="form.title" placeholder="Add title for the assessment" label="Title"/>
                <TextArea
                    v-model="form.description" :rows="7" label="Description"
                    placeholder="Add description for the assessment"/>
                <TextInput
                    v-model="form.maximum_point" placeholder="Maximum Point" label="Maximum Point"
                    type="number"/>
                <DatePicker v-model="form.due_date" placeholder="Due Date"/>

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
    </div>
</template>
<script setup>
import FormElement from "@/Components/FormElement.vue";
import {computed, onMounted, ref} from "vue";
import Modal from "@/Components/Modal.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import DatePicker from "@/Components/DatePicker.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import {
    BookOpenIcon,
    ClipboardDocumentCheckIcon,
    DocumentChartBarIcon,
    DocumentTextIcon,
    HomeIcon,
    PencilIcon
} from "@heroicons/vue/24/solid";
import {ExclamationTriangleIcon} from "@heroicons/vue/24/outline";
import moment from "moment";
import PrimaryButton from "@/Components/PrimaryButton.vue";

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
    due_date: new Date(),
    title: '',
    description: '',
    maximum_point: ''
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

function getIconAndColor(name) {
    switch (name) {
        case "Tests":
            return {icon: DocumentTextIcon, color: 'fill-orange-500'};
        case "Homework":
            return {icon: ClipboardDocumentCheckIcon, color: 'fill-red-500'};
        case "Classwork":
            return {icon: PencilIcon, color: 'fill-cyan-600'};
        case "Final Quarterly Exam":
            return {icon: DocumentChartBarIcon, color: 'fill-yellow-500'};
        case "Final Exam":
            return {icon: BookOpenIcon, color: 'fill-teal-600'};
        default:
            return {icon: HomeIcon, color: 'fill-emerald-600'};
    }
}
</script>
<style scoped>
</style>
