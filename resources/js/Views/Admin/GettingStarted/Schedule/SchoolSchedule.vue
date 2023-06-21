<template>
    <Modal v-model:view="showCreateModal">
        <FormElement
            class="max-w-2xl"
            title="Create school schedule "
            subtitle="Registration form"
            @submit="addSchoolSchedule"
            @cancel="clear">
            <TextInput
                v-model="schoolScheduleFormData.title" 
                :error="schoolScheduleFormData.errors.title"
                label="Name"                          
                placeholder=" name" 
                :required="true"/>
                <TextArea
                    v-model="schoolScheduleFormData.body" 
                    :error="schoolScheduleFormData.errors.body"
                    label="Description" 
                    placeholder="Its about..." 
                    :required="true"/>
                <TextInput 
                    v-model="inputTags" 
                    label="tags" 
                    placeholder="tag1,tag2,tag3" 
                    :required="true"/>
         <DatePicker
             v-model:start-date="schoolScheduleFormData.start_date"
             v-model:end-date="schoolScheduleFormData.end_date" 
             range 
             label="Date" 
             required>
        </DatePicker>
      <RadioGroupPanel
        v-model="schoolScheduleFormData.type" 
        label="Type" 
        :options="eventTypes"
        name="event-types"/>
 </FormElement>
    </Modal>
</template>

<script setup>
import DatePicker from "@/Components/DatePicker.vue";
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import RadioGroupPanel from "@/Components/RadioGroupPanel.vue";
import {computed, ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import moment from "moment";

const showCreateModal = ref(true)
const eventTypes = [
    {
        value: 'closed',
        label: 'Full Day',
        description: 'There is no school for the whole day.'
    },
    {
        value: 'half_closed',
        label: 'Half Day',
        description: 'There will be no class for the half day.'
    },
    {
        value: 'not_closed',
        label: 'None',
        description: 'There will be class all day.'
    }
];


const inputTags = ref('');
const tagsFormData = computed(() => inputTags.value.split(','))
const schoolScheduleFormData = useForm({
    title: '',
    body: '',
    tags: inputTags,
    start_date: null,
    end_date: null,
    type: null,
})

function addSchoolSchedule() {
    console.log(schoolScheduleFormData)

    router.post('/school-schedules/create', {
            ...schoolScheduleFormData,
            tags: tagsFormData.value,
            start_date: moment(schoolScheduleFormData.start_date).format('YYYY-MM-D'),
            end_date: moment(schoolScheduleFormData.end_date).format('YYYY-MM-D'),
        }, {
            onSuccess: () => {
                showCreateModal.value = false
            },
        }
    )
}

const clear = () => {
    schoolScheduleFormData.reset();
    inputTags.value = ''
}
</script>
