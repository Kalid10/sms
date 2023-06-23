<template>
    <Modal v-model:view="showCreateModal">
        <FormElement
            class="max-w-2xl"
            :title="$t('schoolSchedule.formTitle')"
            :subtitle="$t('schoolSchedule.formSubTitle')"
            @submit="addSchoolSchedule"
            @cancel="clear">
            <TextInput
                v-model="schoolScheduleFormData.title"
                :error="schoolScheduleFormData.errors.title"
                :label="$t('schoolSchedule.titleLabel')"
                :placeholder="$t('schoolSchedule.titlePlaceholder')"
                :required="true"/>
                <TextArea
                    v-model="schoolScheduleFormData.body"
                    :error="schoolScheduleFormData.errors.body"
                    :label="$t('schoolSchedule.bodyLabel')"
                    :placeholder="$t('schoolSchedule.bodyPlaceholder')"
                    :required="true"/>
                <TextInput
                    v-model="inputTags"
                    :label="$t('schoolSchedule.inputTagsLabel')"
                    :placeholder="$t('schoolSchedule.inputTagsPlaceholder')"
                    :required="true"/>
         <DatePicker
             v-model:start-date="schoolScheduleFormData.start_date"
             v-model:end-date="schoolScheduleFormData.end_date"
             range
             :label="$t('schoolSchedule.datePickerLabel')"
             required>
        </DatePicker>
      <RadioGroupPanel
        v-model="schoolScheduleFormData.type"
        :label="$t('schoolSchedule.typeLabel')"
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
import {useI18n} from "vue-i18n";
const {t} = useI18n()

const showCreateModal = ref(true)
const eventTypes = [
    {
        value: 'closed',
        label: t('schoolSchedule.fullDay'),
        description: t('schoolSchedule.fullDayDescription')
    },
    {
        value: 'half_closed',
        label: t('schoolSchedule.halfDay'),
        description: t('schoolSchedule.halfDayDescription')
    },
    {
        value: 'not_closed',
        label: t('schoolSchedule.none'),
        description: t('schoolSchedule.noneDescription')
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
