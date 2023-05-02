<template>
    <div class="flex min-h-screen justify-evenly space-x-3 md:px-10">
        <div class="w-1/12">
            <Heading class="text-xl">Subjects</Heading>

            <div class="mt-5 flex flex-col space-y-3">
                <div v-for="(item, key) in groupedSubjects" :key="key">
                    <Heading class="mb-1">{{ item.level }} {{ item.section }}</Heading>
                    <ul>
                        <li
                            v-for="subject in item.subjects" :key="subject.id" class="cursor-pointer hover:underline"
                            @click="loadLessonPlan(subject)">
                            {{ subject.name }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="w-10/12">
            <div class="mb-4 flex flex-col items-center justify-between space-y-3 lg:mb-10 lg:space-y-0">
                <Heading class="w-fit text-xl font-medium lg:text-3xl"> {{ moment(selectedMonth).format('MMMM') }}
                    Lesson
                    Plan For {{
                        batch.level.name
                    }} {{
                        batch.section
                    }} {{
                        selectedSubject
                    }}
                </Heading>
                <select
                    v-model="selectedView"
                    class="focus:ring/50 w-full rounded-md border-gray-300 text-center text-xs shadow-sm lg:hidden lg:w-fit lg:text-sm"
                >
                    <option v-for="option in views" :key="option" :value="option.value">{{ option.name }}</option>
                </select>
                <div class="flex items-center space-x-3">
                    <label for="month-selector" class="hidden lg:block">Select Month:</label>
                    <select
                        id="month-selector"
                        v-model="selectedMonth"
                        class="focus:ring/50 w-full rounded-md border-gray-300 text-center text-xs shadow-sm lg:w-fit lg:text-sm"
                        @change="handleMonthChange"
                    >
                        <option v-for="month in months" :key="month.value" :value="month.value">{{
                                month.label
                            }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="mt-5 flex min-h-screen flex-col text-center md:grid" :class="'grid-cols-'+ weeksInMonth">
                <div v-for="(item, index) in views" :key="index" class="h-full border-r border-gray-300">
                    <Heading class="my-4 md:my-0">{{ item.name }}</Heading>
                    <div class="flex flex-col space-y-3 lg:space-y-4 xl:space-y-7">
                        <div
                            v-for="batchSession in batchSessions[item.value]" :key="batchSession.id"
                            class="m-2 h-full bg-neutral-50 text-center">
                            <div v-if="batchSession.lesson_plan" class="text-center">

                                <Card
                                    class="min-w-full py-2 text-center">
                                    <div class="font-semibold">
                                        {{ moment(batchSession.date).format('dddd MMMM Do') }}, Period
                                        {{ batchSession.batch_schedule.school_period.name }}
                                    </div>
                                    <div>
                                        {{ batchSession.lesson_plan.topic }}

                                    </div>

                                    <SecondaryButton
                                        class="bg-neutral-600 text-white" title="View Lesson Plan"
                                        @click="prepareForm(batchSession.id)"/>
                                </Card>
                            </div>
                            <div v-else>
                                <Card
                                    class="min-w-full"
                                >
                                    <div class="font-semibold">
                                        {{ moment(batchSession.date).format('dddd MMMM Do') }}, Period
                                        {{ batchSession.batch_schedule.school_period.name }}
                                    </div>
                                    <div class="font-bold">
                                        No lesson plan found!
                                    </div>
                                    <PrimaryButton title="Add Lesson Plan" @click="prepareForm(batchSession.id)"/>
                                </Card>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <Modal v-model:view="isModalOpen">
                    <div class="fixed inset-y-0 right-0 w-7/12 rounded-l-lg bg-white">


                        <div class="flex flex-col space-y-7">
                            <div class="bg-white py-5 pl-7">
                                <div>
                                    <h1 class="font-light italic"> Grade {{ batch.level.name }} {{ batch.section }}</h1>
                                </div>
                                <div class="text-2xl font-bold">
                                    {{ moment(selectedBatchSession.date).format('dddd MMMM Do') }}, Period
                                    {{ selectedBatchSession.batch_schedule.school_period.name }}
                                </div>

                            </div>

                            <div class="flex justify-evenly">
                                <div
                                    class="border-1 flex w-6/12 flex-col space-y-5 rounded-md border-black bg-white">
                                    <Heading>Add Lesson Plan</Heading>
                                    <TextInput v-model="form.topic" placeholder="Topic" title=""/>
                                    <TextArea
                                        v-model="form.description" placeholder="Description" label=""
                                        :rows="isLg?18:7"/>

                                    <div class="flex w-full justify-end">
                                        <PrimaryButton title="Submit" class="w-1/5" @click="handleSubmit"/>
                                    </div>


                                    <div class="mt-10">
                                        <Heading>Homeworks and ClassWorks</Heading>
                                    </div>
                                </div>

                                <div class=" w-5/12 ">


                                    <Heading class="fixed">Previous Lesson Plans
                                    </Heading>
                                    <div class="hide-scrollbar mt-10 h-screen overflow-y-auto pb-44">
                                        <div>
                                            <div
                                                v-if="previousBatchSessionsWithLessonPlans.length > 0"
                                                class=" flex flex-col space-y-4">
                                                <div
                                                    v-for="previousBatchSession in previousBatchSessionsWithLessonPlans"
                                                    :key="previousBatchSession.id"
                                                    class="flex flex-col space-y-3">
                                                    <div class="font-light italic">
                                                        {{ moment(previousBatchSession.date).format('dddd MMMM Do') }},
                                                        Period
                                                        {{ previousBatchSession.batch_schedule.school_period.name }}
                                                    </div>
                                                    <div class="font-semibold">
                                                        {{ previousBatchSession.lesson_plan.topic }}
                                                    </div>
                                                    <div class="text-sm font-light">
                                                        {{ previousBatchSession.lesson_plan.description }}
                                                    </div>
                                                    <hr class="my-4">
                                                </div>
                                            </div>
                                            <div v-else>
                                                No previous lesson plans found.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </Modal>
            </div>
        </div>
    </div>
</template>
<script setup>
import {computed, ref} from 'vue';
import {router, useForm, usePage} from '@inertiajs/vue3';
import moment from 'moment';
import Heading from '@/Components/Heading.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import TextArea from '@/Components/TextArea.vue';
import TextInput from '@/Components/TextInput.vue';

const batchSessions = computed(() => usePage().props.batch_sessions);
const batch = computed(() => usePage().props.batch);
const weeksInMonth = usePage().props.weeks_in_month;
const subjects = computed(() => usePage().props.subjects);
const selectedSubject = ref(usePage().props.lesson_plan_subject.subject.full_name);
const isModalOpen = ref(false);
const isLg = computed(() => window.innerWidth >= 1024);
const selectedMonth = ref(usePage().props.selected_month);
const selectedView = ref('week1');
const months = usePage().props.months;

const groupedSubjects = computed(() => {
    const grouped = {};

    subjects.value.forEach((subject) => {
        const batch = subject.batch;
        const levelName = batch.level.name;
        const section = batch.section;
        const key = `${levelName}-${section}`;

        if (!grouped[key]) {
            grouped[key] = {
                level: levelName,
                section: section,
                subjects: [],
            };
        }

        grouped[key].subjects.push({
            id: subject.id,
            name: subject.subject.full_name,
        });
    });
    return grouped;
});

const previousBatchSessionsWithLessonPlans = computed(() => {
    if (selectedBatchSession.value) {
        const allBatchSessions = Object.values(batchSessions.value).flat();
        const currentBatchSessionIndex = allBatchSessions.findIndex(bs => bs.id === selectedBatchSession.value.id);

        const sessionsWithLessonPlans = [];
        for (let i = currentBatchSessionIndex - 1; i >= 0; i--) {
            if (allBatchSessions[i].lesson_plan) {
                sessionsWithLessonPlans.push(allBatchSessions[i]);
            }
        }
        return sessionsWithLessonPlans;
    }
    return [];
});

const views = computed(() => {
    let views = [];
    for (let i = 1; i <= weeksInMonth; i++) {
        views.push({
            name: `Week ${i}`,
            value: `week${i}`,
        });
    }
    return views;
});

const form = useForm({
    batch_session_id: '',
    topic: '',
    description: ''
});

const selectedBatchSession = ref(null);

function prepareForm(batchSessionId) {
    let batchSession = null;

    // Find the batch session in all weeks
    for (const weekKey in batchSessions.value) {
        batchSession = batchSessions.value[weekKey].find(bs => bs.id === batchSessionId);
        if (batchSession) {
            break;
        }
    }

    // If the batch session is found
    if (batchSession) {
        // Set the form data based on the existing lesson plan or a new one
        if (batchSession.lesson_plan) {
            form.topic = batchSession.lesson_plan.topic;
            form.description = batchSession.lesson_plan.description;
        } else {
            form.topic = '';
            form.description = '';
        }

        form.batch_session_id = batchSessionId;
        isModalOpen.value = true;

        selectedBatchSession.value = batchSession;
    } else {
        console.error('Batch session not found in any week');
    }
}

function handleMonthChange() {
    router.get('/teacher/lesson-plan/', {
        batch_subject_id: selectedSubject.value.id,
        month: selectedMonth.value,
    });
}

function handleSubmit() {
    form.post('/teacher/lesson-plan', {
        onSuccess: () => {
            form.reset();
            isModalOpen.value = false;
        }
    })
}

function loadLessonPlan(item) {
    selectedSubject.value = item.name;
    router.get('/teacher/lesson-plan/', {
        batch_subject_id: item.id,
        month: selectedMonth.value,
    });
}
</script>


<style>
.hide-scrollbar::-webkit-scrollbar {
    display: none; /* for Chrome, Safari, and Opera */
}

.hide-scrollbar {
    -ms-overflow-style: none; /* for Internet Explorer and Edge */
    scrollbar-width: none; /* for Firefox */
}
</style>
