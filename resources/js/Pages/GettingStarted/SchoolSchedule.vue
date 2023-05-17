<template>

    <Modal
        v-model:view="welcomeModal"
        center
        background-color="black"
        :close-on-outside-click="false"
    >
        <Card class="mx-auto mt-12 !min-w-full">
            <div class="flex w-full flex-col items-center gap-6">
                <div>
                    <Heading class="text-center" size="md"
                    >Congratulations!
                    </Heading>
                    <Heading
                        class="text-center !font-normal text-gray-500"
                        size="sm"
                    >You're almost done...
                    </Heading>
                </div>

                <div class="flex flex-col gap-3">
                    <p class="max-w-2xl text-center text-sm text-gray-500">
                        You have successfully registered your school's grades,
                        subjects and classes. Next, you may proceed to add your
                        school's schedule for the current school year. We will
                        guide you through the process.
                    </p>

                    <p class="max-w-2xl text-center text-sm text-gray-500">
                        As a first step, you may add the school year's first
                        semester start date. This will be used to notify
                        guardians and students of the start of the school year.
                        Calendar events such as public holidays and school
                        holidays can also be added here.
                    </p>

                    <p class="max-w-2xl text-center text-sm text-gray-500">
                        Do not worry, if you are not sure of the exact dates,
                        you can always add these events and more later. Once you
                        are done here, we will finally take you to your
                        Dashboard.
                    </p>
                </div>

                <div class="flex flex-col gap-3">
                    <Heading class="text-center" size="sm">Next Steps</Heading>

                    <div class="flex w-full flex-col gap-2 md:flex-row">
                        <Card
                            class="group !h-fit cursor-pointer hover:border-black"
                            @click="registerSemesterStartDate"
                        >
                            <div
                                class="flex flex-col items-center justify-between gap-2"
                            >
                                <div
                                    class="flex w-full items-center justify-center"
                                >
                                    <h3 class="text-xs font-semibold">
                                        Register Semester Start Date
                                    </h3>
                                    <div
                                        class="flex max-w-0 grow justify-end opacity-0 transition-all duration-150 group-hover:max-w-full group-hover:opacity-100"
                                    >
                                        <ArrowRightIcon
                                            class="h-3 w-3 stroke-2"
                                        />
                                    </div>
                                </div>

                                <h3 class="text-center text-xs">
                                    Add the start of the school semester in your
                                    calendar. Guardians, Teachers and Students
                                    will be notified once they are registered
                                </h3>
                            </div>
                        </Card>
                        <Card
                            class="group !h-fit cursor-pointer hover:border-black"
                            @click="viewCalendar"
                        >
                            <div
                                class="flex h-full flex-col items-center justify-between gap-2"
                            >
                                <div
                                    class="flex w-full items-center justify-center"
                                >
                                    <h3 class="text-xs font-semibold">
                                        View your School Schedule
                                    </h3>
                                    <div
                                        class="flex max-w-0 grow justify-end opacity-0 transition-all duration-150 group-hover:max-w-full group-hover:opacity-100"
                                    >
                                        <ArrowRightIcon
                                            class="h-3 w-3 stroke-2"
                                        />
                                    </div>
                                </div>

                                <h3 class="text-center text-xs">
                                    View your school calendar and add events
                                    such as public holidays, school holidays and
                                    more.
                                </h3>
                            </div>
                        </Card>
                        <Card
                            class="group !h-fit cursor-pointer hover:border-black"
                            @click="goToDashboard"
                        >
                            <div
                                class="flex flex-col items-center justify-between gap-2"
                            >
                                <div
                                    class="flex w-full items-center justify-center"
                                >
                                    <h3 class="text-xs font-semibold">
                                        Proceed to your Dashboard
                                    </h3>
                                    <div
                                        class="flex max-w-0 grow justify-end opacity-0 transition-all duration-150 ease-in group-hover:max-w-full group-hover:opacity-100"
                                    >
                                        <ArrowRightIcon
                                            class="h-3 w-3 stroke-2"
                                        />
                                    </div>
                                </div>

                                <h3 class="text-center text-xs">
                                    Alternatively, you can skip this step and
                                    proceed to your Dashboard. You can always
                                    view, add, update and delete events to your
                                    calendar later.
                                </h3>
                            </div>
                        </Card>
                    </div>
                </div>
            </div>
        </Card>
    </Modal>

    <div class="grid h-full max-h-full w-full grid-cols-12 border-t">

        <div class="relative col-span-3 flex h-full flex-col items-center overflow-auto border-r">

            <div class="flex h-[57px] w-full flex-col justify-center border-b pl-12">
                <h3 class="font-semibold">Add new Event</h3>
                <h3 class="text-sm leading-none text-gray-500">
                    Add a new event to your school calendar.
                </h3>
            </div>

            <div class="flex w-full flex-col gap-4 py-6 pl-12 pr-6">

                <TextInput
                    v-model="formData.title"
                    required
                    placeholder="Title for your new event"
                    label="Event Name"
                />

                <label class="flex flex-col gap-1">
            <span class="">
                <span class="pl-0.5 text-sm font-semibold text-gray-500"
                >Event Description</span
                >
                <span class="pl-0.5 text-xs text-red-600">*</span>
            </span>
                    <textarea
                        v-model="formData.body"
                        rows="5"
                        required
                        placeholder="What is your event about? Write your description here."
                        class="w-full rounded-md border border-gray-200 text-sm placeholder:text-sm placeholder:text-gray-500"
                    ></textarea>
                </label>

                <div class="flex w-full flex-col items-end gap-3">
                    <div class="flex w-full items-start gap-3">
                        <Toggle
                            v-model="allDay"
                            label-location="top"
                            label="All Day"
                            class="min-w-fit"
                        />
                        <DatePicker
                            v-model:start-date="formData.start_date"
                            v-model:end-date="formData.end_date"
                            v-model="formData.start_date"
                            required
                            class="w-full"
                            :range="!allDay"
                            :label="
                            allDay
                                ? 'Day of Event'
                                : 'Event\'s Starting and End Days'
                        "
                        />
                    </div>
                    <SelectInput
                        v-model="formData.type"
                        required
                        class="w-full"
                        :options="eventTypes"
                        placeholder="Pick the type of Event"
                        label="Event Type"
                    />
                </div>

                <div class="flex items-center justify-end gap-3">
                    <TertiaryButton @click="clear">Clear</TertiaryButton>
                    <PrimaryButton @click="submit">Create Event</PrimaryButton>
                </div>

            </div>

        </div>

        <MonthView class="col-span-9 h-full" @select="addNewEvent" />

    </div>

    <Modal
        v-model:view="isNewEventModalOpened"
        :close-on-outside-click="closeNewEventFormOnClick"
    >
        <FormElement
            v-model:show-modal="isNewEventModalOpened"
            modal
            title="Add new Event"
            subtitle="Add a new event to the current school year in your school's schedule"
        >
            <TextInput
                v-model="formData.title"
                required
                placeholder="Title for your new event"
                label="Event Name"
            />

            <label class="flex flex-col gap-1">
                <span class="">
                    <span class="pl-0.5 text-sm font-semibold text-gray-500"
                    >Event Description</span
                    >
                    <span class="pl-0.5 text-xs text-red-600">*</span>
                </span>
                <textarea
                    v-model="formData.body"
                    rows="5"
                    required
                    placeholder="What is your event about? Write your description here."
                    class="w-full rounded-md border border-gray-200 text-sm placeholder:text-sm placeholder:text-gray-500"
                ></textarea>
            </label>

            <div class="flex w-full flex-col items-end gap-3 md:flex-row">
                <div class="flex w-full items-start gap-3">
                    <Toggle
                        v-model="allDay"
                        label-location="top"
                        label="All Day"
                        class="min-w-fit"
                    />
                    <DatePicker
                        v-model:start-date="formData.start_date"
                        v-model:end-date="formData.end_date"
                        v-model="formData.start_date"
                        required
                        class="w-full"
                        :range="!allDay"
                        :label="
                            allDay
                                ? 'Day of Event'
                                : 'Event\'s Starting and End Days'
                        "
                    />
                </div>
                <SelectInput
                    v-model="formData.type"
                    required
                    class="w-full"
                    :options="eventTypes"
                    placeholder="Pick the type of Event"
                    label="Event Type"
                />
            </div>
        </FormElement>
    </Modal>

</template>

<script setup>
import { ref } from "vue";
import MonthView from "@/Views/Calendar/MonthView.vue";
import { ArrowRightIcon } from "@heroicons/vue/24/outline";
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import { router, useForm } from "@inertiajs/vue3";
import Toggle from "@/Components/Toggle.vue";
import DatePicker from "@/Components/DatePicker.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Card from "@/Components/Card.vue";
import Heading from "@/Components/Heading.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";

const isNewEventModalOpened = ref(false);
const welcomeModal = ref(true);
const closeNewEventFormOnClick = ref(true);

const formData = useForm({
    title: "",
    body: "",
    start_date: null,
    end_date: null,
    type: "",
});

function clear() {
    formData.reset()
}

function submit() {
    // TODO: Send POST request to backend
}

const eventTypes = [
    {
        value: "holiday",
        label: "Holiday",
    },
    {
        value: "special-day",
        label: "Special Day",
    },
    {
        value: "meeting",
        label: "Meeting",
    },
    {
        value: "academic",
        label: "Academic Schedule",
    },
];

const allDay = ref(false);

function registerSemesterStartDate() {
    welcomeModal.value = false;
    isNewEventModalOpened.value = true;
    formData.title = "First Semester Starts";
    formData.body =
        "This is the first day of the first semester of the school year. All students are expected to be in school for the commencement of the semester.";
    formData.type = "academic";
    allDay.value = true;
    closeNewEventFormOnClick.value = false;
}

function viewCalendar() {
    welcomeModal.value = false;
}

function goToDashboard() {
    router.get("/");
}

function addNewEvent(date) {
    isNewEventModalOpened.value = true;
    formData.start_date = date
}
</script>
