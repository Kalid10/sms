<template>
    <div
        class="group flex h-full cursor-pointer items-center space-x-10 py-4 hover:scale-105 hover:rounded-lg hover:bg-zinc-800 hover:text-white"
        :class="isTeacher() ? 'justify-evenly' : ''"
    >
        <div
            :class="{
                'border-yellow-500': schoolSchedule.type === 'holiday',
                'border-sky-500': schoolSchedule.type === 'event',
            }"
            class="w-1/12 border-l px-3 text-center text-xs font-light capitalize group-hover:border-none group-hover:text-center"
        >
            {{ schoolSchedule.type }}
        </div>
        <div class="flex w-8/12 flex-col justify-center space-y-4">
            <div class="text-sm font-medium">
                {{ schoolSchedule.title }}
                {{ schoolSchedule.type }}
            </div>
            <div class="flex items-center space-x-2">
                <CalendarDaysIcon class="w-4 text-gray-500" />
                <span class="text-xs font-light">{{
                    moment(schoolSchedule.start_date).format(
                        "dddd MMM DD YYYY"
                    ) +
                    " - " +
                    moment(schoolSchedule.end_date).format("dddd MMM DD YYYY")
                }}</span>
            </div>
        </div>
        <div
            v-if="view === 'admin' && isAdmin()"
            class="flex w-2/12 justify-evenly"
        >
            <TrashIcon
                v-if="moment(schoolSchedule.start_date).isAfter(moment())"
                class="group-hover:text-ref-500 w-4 cursor-pointer text-red-600 hover:scale-110"
                @click="toggleUpdateModal(schoolSchedule)"
            />
            <PencilSquareIcon
                class="w-4 cursor-pointer text-gray-500 hover:scale-110 group-hover:text-gray-200"
                @click="edit(schoolSchedule)"
            />
        </div>
    </div>

    <Modal v-model:view="showUpdateModal">
        <FormElement
            class="max-w-2xl"
            :title="$t('schoolScheduleItem.updateSchoolSchedule')"
            :subtitle="$t('schoolScheduleItem.update')"
            @submit="update"
        >
            <TextInput
                v-model="form.title"
                :error="form.errors.title"
                :label="$t('schoolScheduleItem.titleLabel')"
                :placeholder="$t('schoolScheduleItem.titlePlaceholder')"
                :required="true"
            />
            <TextArea
                v-model="form.body"
                :error="form.errors.body"
                :label="$t('schoolScheduleItem.bodyLabel')"
                :placeholder="$t('schoolScheduleItem.bodyPlaceholder')"
                :required="true"
            />
            <TextInput
                v-model="form.tags"
                :label="$t('schoolScheduleItem.inputTagsLabel')"
                :placeholder="$t('schoolScheduleItem.inputTagsPlaceholder')"
                :required="true"
            />
            <DatePicker
                v-model:start-date="form.start_date"
                v-model:end-date="form.end_date"
                range
                :label="$t('schoolScheduleItem.datePickerLabel')"
                required
            ></DatePicker>
            <RadioGroupPanel
                v-model="form.type"
                label="Type"
                :options="eventTypes"
                name="event-types"
            />
        </FormElement>
    </Modal>

    <DialogBox
        v-if="isDialogBoxOpen"
        open
        @abort="isDialogBoxOpen = false"
        @confirm="deleteSchedule"
    />
</template>
<script setup>
import { CalendarDaysIcon } from "@heroicons/vue/24/solid";
import { PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/outline";
import moment from "moment/moment";
import { computed, ref } from "vue";
import FormElement from "@/Components/FormElement.vue";
import DatePicker from "@/Components/DatePicker.vue";
import TextArea from "@/Components/TextArea.vue";
import RadioGroupPanel from "@/Components/RadioGroupPanel.vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";
import { router, useForm } from "@inertiajs/vue3";
import DialogBox from "@/Components/DialogBox.vue";
import { isAdmin, isTeacher } from "@/utils";
import {useI18n} from "vue-i18n";
const {t} = useI18n()
const isDialogBoxOpen = ref(false);

const props = defineProps({
    schoolSchedule: {
        type: Object,
        required: true,
    },
    view: {
        type: String,
        default: "admin",
    },
});
const showUpdateModal = ref(false);

const eventTypes = [
    {
        value: "closed",
        label: t('schoolScheduleItem.fullDay'),
        description: t('schoolScheduleItem.fullDayDescription'),
    },
    {
        value: "half_closed",
        label: t('schoolScheduleItem.halfDay'),
        description: t('schoolScheduleItem.halfDayDescription'),
    },
    {
        value: "not_closed",
        label: t('schoolScheduleItem.none'),
        description: t('schoolScheduleItem.noneDescription'),
    },
];

const selectedScheduleId = ref(null);

function toggleUpdateModal(schoolSchedule) {
    isDialogBoxOpen.value = !isDialogBoxOpen.value;
    selectedScheduleId.value = schoolSchedule.id;
}

const inputTags = ref("");
const tagsFormData = computed(() => inputTags.value.split(","));
const form = useForm({
    title: "",
    body: "",
    start_date: null,
    end_date: null,
    type: null,
});

function update() {
    router.post(
        "/school-schedules/update",
        {
            ...form,
            tags: tagsFormData.value,
            start_date: moment(form.start_date).format("YYYY-MM-D"),
            end_date: moment(form.end_date).format("YYYY-MM-D"),
        },
        {
            onSuccess: () => {
                showUpdateModal.value = false;
            },
        }
    );
}

function edit(schoolSchedule) {
    showUpdateModal.value = true;
    form.id = schoolSchedule.id;
    form.title = schoolSchedule.title;
    form.body = schoolSchedule.body;
    form.type = schoolSchedule.type;
    form.tags = schoolSchedule.tags;
}

const deleteSchedule = () => {
    router.delete("/school-schedules/" + selectedScheduleId.value, {
        onFinish: () => {
            isDialogBoxOpen.value = false;
            selectedScheduleId.value = null;
        },
    });
};
</script>

<style scoped></style>
