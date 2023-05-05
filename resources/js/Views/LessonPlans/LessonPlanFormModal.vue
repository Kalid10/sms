<template>

    <Modal v-model:view="openForm" background-color="transparent">

        <div class="fixed top-0 left-0 grid h-screen w-full grid-cols-2">

            <div class="absolute z-0 h-full w-full animate-darken delay-150" @click="closeForm" />

            <div class="relative z-10 col-span-1 col-start-2 h-full w-full animate-slide-left border-l bg-white pl-8 pt-8 shadow-sm">

                <div class="flex h-full w-full flex-col gap-4">

                    <div class="flex w-full flex-col">

                        <div class="flex w-full origin-left items-center justify-between pr-8">

                            <h3 class="text-xs font-semibold text-gray-500">
                                {{ moment(selectedBatchSession.date).fromNow() }}
                            </h3>

                            <div class="flex items-center gap-12">

                                <div class="flex items-center gap-1">
                                    <CalendarIcon class="h-4 w-4 stroke-gray-500 stroke-2"/>
                                    <h3 class="text-xs font-semibold text-gray-500">
                                        {{ moment(selectedBatchSession.date).format('dddd MMMM Do') }}
                                    </h3>
                                </div>

                                <div class="flex items-center gap-1">
                                    <ClockIcon class="h-4 w-4 stroke-gray-500 stroke-2"/>
                                    <h3 class="text-xs font-semibold text-gray-500">
                                        {{ moment(selectedBatchSession['batch_schedule']['school_period']['start_time'], 'HH:mm').format('HH:mm A') }}
                                    </h3>
                                </div>

                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-semibold text-gray-500">Period</span>
                                    <div class="flex origin-left scale-95 items-center gap-1">
                                    <span class="grid h-5 w-5 place-items-center rounded-full border border-brand-100 bg-brand-50 text-xs font-semibold leading-none text-brand-100">
                                        {{ selectedBatchSession['batch_schedule']['school_period']['name'] }}
                                    </span>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="flex flex-col">
                            <h3 class="text-lg font-semibold">
                                {{ parseLevel(batch['level']['name']) }} {{ batch['section'] }}
                                {{ selectedBatchSession['batch_schedule']['batch_subject']['subject']['full_name'] }}
                            </h3>
                        </div>

                    </div>

                    <div class="grid w-full grow grid-cols-12 gap-8">

                        <div class="col-span-7 flex h-full flex-col gap-8 pt-4">

                            <form v-if="updateLessonPlan || !!! selectedBatchSession['lesson_plan_id']" class="flex h-full w-full flex-col gap-3">

                                <div v-if="!!! selectedBatchSession['lesson_plan_id']" class="flex flex-col">
                                    <h3 class="text-sm font-semibold">New Lesson Plan</h3>
                                    <h3 class="text-sm text-gray-500">Add the topic and description of the lesson plan for the session here</h3>
                                </div>

                                <TextInput v-model="form.topic" required label="Topic" placeholder="Topic of the Lesson Plan" />

                                <TextArea v-model="form.description" label="Description" rows="20" leading="leading-loose" placeholder="Add your Lesson Plan description" />

                                <div class="flex w-full justify-end">
                                    <PrimaryButton title="Submit" @click="handleSubmit"/>
                                </div>

                            </form>

                            <template v-else>

                                <div class="flex flex-col gap-3">

                                    <div class="flex w-full items-end justify-between">
                                        <h3 class="font-semibold">{{ selectedBatchSession['lesson_plan']['topic'] }}</h3>
                                        <TertiaryButton class="shadow-sm" @click="updateLessonPlan = true">Edit</TertiaryButton>
                                    </div>

                                    <h3 class="text-sm leading-loose">
                                        {{ selectedBatchSession['lesson_plan']['description'] }}
                                    </h3>

                                </div>

                            </template>

                        </div>

                        <div class="col-span-5 flex h-full w-full flex-col gap-8 rounded-tl-md border-l border-t py-4 pl-4 pr-8">

                            <div class="flex flex-col">
                                <h3 class="text-sm font-semibold">Previous Lesson Plans</h3>
                                <h3 class="text-sm text-gray-500">Other lesson plans for class sessions before
                                    <span class="font-semibold">
                                        {{ moment(selectedBatchSession.date).format('MMMM Do') }}
                                    </span>
                                </h3>
                            </div>

                            <div class="flex flex-col gap-6">

                                <template v-if="previousBatchSessionsWithLessonPlans.length < 1">

                                    <div class="grid h-36 w-full place-items-center">

                                        <div class="flex w-full flex-col items-center justify-center gap-1">
                                            <ExclamationTriangleIcon class="h-6 w-6 text-gray-500"/>
                                            <h3 class="text-xs font-semibold text-black">No Previous Lesson Plans</h3>
                                            <h3 class="max-w-[18rem] text-center text-xs text-gray-500">
                                                Previous lesson plans have not been added for this session. Make sure you have
                                                populated your lesson plans to view them here.
                                            </h3>
                                        </div>

                                    </div>

                                </template>

                                <template v-else>

                                    <div v-for="(prevBatchSession, pBS) in previousBatchSessionsWithLessonPlans" :key="pBS" class="flex flex-col">

                                        <h3 class="text-sm font-normal">{{ prevBatchSession['lesson_plan']['topic'] }}</h3>
                                        <h3 class="line-clamp-4 text-sm text-gray-400">
                                            {{ prevBatchSession['lesson_plan']['description'] }}
                                        </h3>
                                        <div class="mt-2 flex w-full gap-8">

                                            <div class="flex w-full items-center justify-between">
                                                <div class="flex origin-left scale-[.8] items-center gap-2">
                                            <span class="rounded-xl border border-black bg-white px-2 py-1 text-xs font-semibold leading-none">
                                                {{ dayLabel(prevBatchSession['batch_schedule']['day_of_week']) }}
                                            </span>
                                                    <h3 class="font-semibold text-gray-500">{{  moment(prevBatchSession.date).format('MMMM Do') }}</h3>
                                                </div>

                                                <div class="flex origin-right scale-90 items-center gap-1.5">
                                                    <div class="flex gap-1">

                                                        <div class="flex items-end gap-1.5">
                                                            <span class="text-sm font-semibold text-gray-500">Period</span>
                                                            <div class="flex origin-left scale-95 items-center gap-1">
                                                        <span class="grid h-5 w-5 place-items-center rounded-full border border-brand-100 bg-brand-50 text-xs font-semibold leading-none text-brand-100">
                                                            {{ prevBatchSession['batch_schedule']['school_period']['name'] }}
                                                        </span>
                                                            </div>
                                                        </div>

                                                        <h3 class="text-sm font-semibold text-gray-500">
                                                            {{ '09:30 AM' }}
                                                        </h3>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </template>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </Modal>

</template>

<script setup>
import moment from "moment";
import {parseLevel} from "@/utils.js";
import {CalendarIcon, ClockIcon, ExclamationTriangleIcon} from "@heroicons/vue/24/outline/index.js";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import {computed, ref, watch} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";

const props = defineProps({
    batchSession: {
        type: Object,
        required: true
    },
    view: {
        type: Boolean,
        required: true
    },
})

const emits = defineEmits(['update:view'])

const batch = computed(() => usePage().props.batch)
const selectedBatchSession = computed(() => props.batchSession)
const batchSessions = computed(() => usePage().props['batch_sessions'])
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

const openForm = computed({
    get() {
        return props.view
    },
    set(value) {
        emits('update:view', value)
    }
})

function closeForm() {
    updateLessonPlan.value = false
    openForm.value = false
}

function dayLabel(day) {

    let label
    day[0].toLowerCase() === 't' ? label = day[0] + day[1] : label = day[0]
    return label.toUpperCase()
}

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
        openForm.value = true;

        selectedBatchSession.value = batchSession;
    } else {
        console.error('Batch session not found in any week');
    }
}

const updateLessonPlan = ref(false)
const form = useForm({
    batch_session_id: '',
    topic: '',
    description: ''
});
function handleSubmit() {
    form.post('/teacher/lesson-plan', {
        onSuccess: () => {
            form.reset();
            if (updateLessonPlan.value) {
                updateLessonPlan.value = false;
            } else {
                openForm.value = false;
            }
        }
    })
}

watch(selectedBatchSession, () => {
    prepareForm(selectedBatchSession.value.id)
})
</script>

<style scoped>

</style>
