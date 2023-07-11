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
                        >{{ $t('gettingStartedSchoolSchedule.congratulations') }}
                    </Heading>
                    <Heading
                        class="text-center !font-normal text-gray-500"
                        size="sm"
                        >{{ $t('gettingStartedSchoolSchedule.almostDone') }}
                    </Heading>
                </div>

                <div class="flex flex-col gap-3">
                    <p class="max-w-2xl text-center text-sm text-gray-500">
                        {{ $t('gettingStartedSchoolSchedule.successfullyRegisteredMessage') }}
                    </p>

                    <p class="max-w-2xl text-center text-sm text-gray-500">
                        {{ $t('gettingStartedSchoolSchedule.firstStepHint') }}
                    </p>

                    <p class="max-w-2xl text-center text-sm text-gray-500">
                        {{ $t('gettingStartedSchoolSchedule.doNotWorryMessage') }}
                    </p>
                </div>

                <div class="flex flex-col gap-3">
                    <Heading class="text-center" size="sm">{{ $t('gettingStartedSchoolSchedule.nextSteps') }}</Heading>

                    <div class="flex w-full flex-col gap-2 md:flex-row">
                        <Card
                            class="group cursor-pointer hover:border-black"
                            @click="registerSemesterStartDate"
                        >
                            <div
                                class="flex flex-col items-center justify-between gap-2"
                            >
                                <div
                                    class="flex w-full items-center justify-center"
                                >
                                    <h3 class="text-xs font-semibold">
                                        {{ $t('gettingStartedSchoolSchedule.registerSemesterStartDate') }}
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
                                    {{ $t('gettingStartedSchoolSchedule.AddStartOfSchoolSemester') }}
                                </h3>
                            </div>
                        </Card>
                        <Card
                            class="group cursor-pointer hover:border-black"
                            @click="viewCalendar"
                        >
                            <div
                                class="flex h-full flex-col items-center gap-2"
                            >
                                <div
                                    class="flex w-full items-center justify-center"
                                >
                                    <h3 class="text-xs font-semibold">
                                        {{ $t('gettingStartedSchoolSchedule.ViewSchoolSchedule') }}
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
                                    {{ $t('gettingStartedSchoolSchedule.useSchoolCalenderMessage') }}
                                </h3>
                            </div>
                        </Card>
                        <Card
                            class="group cursor-pointer hover:border-black"
                            @click="goToDashboard"
                        >
                            <div
                                class="flex flex-col items-center justify-between gap-2"
                            >
                                <div
                                    class="flex w-full items-center justify-center"
                                >
                                    <h3 class="text-xs font-semibold">
                                        {{ $t('gettingStartedSchoolSchedule.proceedToDashboard') }}
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
                                    {{ $t('gettingStartedSchoolSchedule.youCanSkipMessage') }}
                                </h3>
                            </div>
                        </Card>
                    </div>
                </div>
            </div>
        </Card>
    </Modal>

    <div class="grid h-screen w-full grid-cols-12 border-t md:max-h-full">
        <div
            class="relative col-span-12 h-full w-full flex-col items-center border-r md:col-span-4 md:flex md:overflow-auto lg:col-span-3"
        >
            <div
                class="flex h-[57px] w-full flex-col justify-center border-b pl-12"
            >
                <h3 class="font-semibold">{{ $t('gettingStartedSchoolSchedule.addNewEvent')}} </h3>
                <h3 class="text-sm leading-none text-gray-500">
                    {{ $t('gettingStartedSchoolSchedule.addEvent')}}
                </h3>
            </div>

            <div class="flex w-full flex-col gap-4 py-6 pl-12 pr-6">
                <TextInput
                    v-model="formData.title"
                    required
                    :placeholder="$t('gettingStartedSchoolSchedule.eventTitlePlaceHolder')"
                    :label="$t('gettingStartedSchoolSchedule.eventTitleLabel')"
                />

                <label class="flex flex-col gap-1">
                    <span class="">
                        <span class="pl-0.5 text-sm font-semibold text-gray-500"
                            >{{ $t('gettingStartedSchoolSchedule.eventDescription')}} </span
                        >
                        <span class="pl-0.5 text-xs text-red-600">*</span>
                    </span>
                    <textarea
                        v-model="formData.body"
                        rows="5"
                        required
                        :placeholder="$t('gettingStartedSchoolSchedule.eventBodyPlaceHolder')"
                        class="w-full rounded-md border border-gray-200 text-sm placeholder:text-sm placeholder:text-gray-500"
                    ></textarea>
                </label>

                <div class="flex w-full flex-col items-end gap-3">
                    <div class="flex w-full items-start gap-3">
                        <Toggle
                            v-model="allDay"
                            label-location="top"
                            :label="$t('gettingStartedSchoolSchedule.allDayLabel')"
                            class="min-w-fit"
                        />
                        <DatePicker
                            v-model:start-date="formData.start_date"
                            v-model:end-date="formData.end_date"
                            v-model="formData.start_date"
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
                        :placeholder="$t('gettingStartedSchoolSchedule.pickTypeOfEventPlaceHolder')"
                        :label="$t('gettingStartedSchoolSchedule.pickTypeOfEventLabel')"
                    />
                    <TextInput
                        v-model="tags"
                        label="Tags"
                        class="w-full"
                        :placeholder="$t('gettingStartedSchoolSchedule.tagsPlaceHolder')"
                    />
                </div>

                <div class="flex items-center justify-end gap-3">
                    <TertiaryButton @click="clear">{{ $t('gettingStartedSchoolSchedule.clear')}}</TertiaryButton>
                    <PrimaryButton @click="submit">{{ $t('gettingStartedSchoolSchedule.createEvent')}}</PrimaryButton>
                </div>
            </div>

            <div class="absolute bottom-0 w-full p-4">
                <button
                    class="h-12 w-full place-items-center rounded-md bg-black font-semibold text-white"
                    @click="goToDashboard"
                >
                    {{ $t('gettingStartedSchoolSchedule.goToDashboard')}}
                </button>
            </div>
        </div>

        <MonthView
            class="hidden h-full w-full md:col-span-8 md:flex lg:col-span-9"
            @select="addNewEvent"
        />
    </div>

    <Modal
        v-model:view="isNewEventModalOpened"
        :close-on-outside-click="closeNewEventFormOnClick"
    >
        <FormElement
            v-model:show-modal="isNewEventModalOpened"
            modal
            :title="$t('gettingStartedSchoolSchedule.addNewEventFormTitle')"
            :subtitle="$t('gettingStartedSchoolSchedule.addNewEventFormSubtitle')"
        >
            <TextInput
                v-model="formData.title"
                required
                :placeholder="$t('gettingStartedSchoolSchedule.eventTitlePlaceHolder')"
                :label="$t('gettingStartedSchoolSchedule.eventTitleLabel')"
            />

            <label class="flex flex-col gap-1">
                <span class="">
                    <span class="pl-0.5 text-sm font-semibold text-gray-500"
                        >{{ $t('gettingStartedSchoolSchedule.eventDescription')}} </span
                    >
                    <span class="pl-0.5 text-xs text-red-600">*</span>
                </span>
                <textarea
                    v-model="formData.body"
                    rows="5"
                    required
                    :placeholder="$t('gettingStartedSchoolSchedule.eventBodyPlaceHolder')"
                    class="w-full rounded-md border border-gray-200 text-sm placeholder:text-sm placeholder:text-gray-500"
                ></textarea>
            </label>

            <div class="flex w-full flex-col items-end gap-3 md:flex-row">
                <div class="flex w-full items-start gap-3">
                    <Toggle
                        v-model="allDay"
                        label-location="top"
                        :label="$t('gettingStartedSchoolSchedule.allDayLabel')"
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
                    :placeholder="$t('gettingStartedSchoolSchedule.pickTypeOfEventPlaceHolder')"
                    :label="$t('gettingStartedSchoolSchedule.pickTypeOfEventLabel')"
                />
            </div>
        </FormElement>
    </Modal>
</template>

<script setup>
import { ref, watch } from "vue";
import MonthView from "@/Views/Admin/Calendar/MonthView.vue";
import { ArrowRightIcon } from "@heroicons/vue/24/outline";
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import { router } from "@inertiajs/vue3";
import Toggle from "@/Components/Toggle.vue";
import DatePicker from "@/Components/DatePicker.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Card from "@/Components/Card.vue";
import Heading from "@/Components/Heading.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";
import {useI18n} from "vue-i18n";
const {t} = useI18n()
const isNewEventModalOpened = ref(false);
const welcomeModal = ref(true);
const closeNewEventFormOnClick = ref(true);

const formData = ref({
    title: "",
    body: "",
    start_date: null,
    end_date: null,
    type: "",
});

const tags = ref("");

watch(tags, (value) => {
    console.log(value.split(","));
    formData.value.tags = value.split(",");
});

function clear() {
    formData.value.reset();
}

function submit() {
    if (formData.value.end_date === null) {
        formData.value.end_date = formData.value.start_date;
    }

    router.post(
        "/school-schedules/create",
        { ...formData.value, tags: tags.value.split(", ") },
        {
            onSuccess: () => {
                isNewEventModalOpened.value = false;
                clear();
                console.log("scuccess");
            },
            onError: () => {
                console.log("error");
            },
        }
    );
}

const eventTypes = [
    {
        value: "closed",
        label: t('gettingStartedSchoolSchedule.noSchoolDay'),
    },
    {
        value: "half_closed",
        label: t('gettingStartedSchoolSchedule.halfDayClosed'),
    },
    {
        value: "not_closed",
        label: t('gettingStartedSchoolSchedule.schoolDay'),
    },
];

const allDay = ref(false);

function registerSemesterStartDate() {
    welcomeModal.value = false;
    isNewEventModalOpened.value = true;
    formData.value.title = "First Semester Starts";
    formData.value.body =
        "This is the first day of the first semester of the school year. All students are expected to be in school for the commencement of the semester.";
    formData.value.type = "academic";
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
    formData.value.start_date = date;
}
</script>
