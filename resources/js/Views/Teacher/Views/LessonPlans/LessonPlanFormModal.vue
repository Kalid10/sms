<template>
    <Modal v-model:view="openForm" background-color="transparent">
        <div
            class="fixed top-0 left-0 grid h-screen w-full"
            :class="isTeacher() ? 'grid-cols-12' : 'grid-cols-5'"
        >
            <div
                class="col-span-1 min-h-full animate-darken delay-150"
                @click="closeForm"
            ></div>

            <div
                class="relative col-start-2 flex h-full w-full animate-slide-left space-x-5 border-l bg-white pl-3 pt-8 shadow-sm"
                :class="isTeacher() ? 'col-span-11' : 'col-span-4'"
            >
                <div
                    v-if="isTeacher()"
                    class="h-5/6 w-6/12 overflow-y-auto"
                    :class="showAISection ? 'flex ' : 'hidden'"
                >
                    <LessonPlanCopilot
                        :topic="form.topic"
                        :generate-note-suggestions="generateNoteSuggestions"
                        :description="form.description"
                        :generate-question-suggestions="
                            generateQuestionSuggestions
                        "
                        :lesson-plan-id="batchSession?.lesson_plan?.id"
                        :batch-subject-id="
                            batchSession?.batch_schedule.batch_subject_id
                        "
                        @selected-text="updateSelectedText"
                        @finish="
                            generateQuestionSuggestions = false;
                            generateNoteSuggestions = false;
                        "
                        @close="showAISection = false"
                    />
                </div>
                <div
                    class="flex h-full flex-col gap-4"
                    :class="
                        isTeacher()
                            ? showAISection
                                ? 'w-6/12'
                                : ' w-full'
                            : 'w-full px-5'
                    "
                >
                    <div class="flex w-full flex-col">
                        <div
                            class="flex w-full origin-left items-center justify-between pr-8"
                        >
                            <h3 class="text-xs font-semibold text-gray-700">
                                {{
                                    moment(selectedBatchSession.date).fromNow()
                                }}
                            </h3>

                            <div class="flex items-center gap-12">
                                <div class="flex items-center gap-1">
                                    <CalendarIcon
                                        class="h-4 w-4 stroke-gray-500 stroke-2"
                                    />
                                    <h3 class="text-xs font-medium">
                                        {{
                                            moment(
                                                selectedBatchSession.date
                                            ).format("dddd MMMM Do")
                                        }}
                                    </h3>
                                </div>

                                <div class="flex items-center gap-1">
                                    <ClockIcon
                                        class="h-4 w-4 stroke-gray-500 stroke-2"
                                    />
                                    <h3 class="text-xs font-medium">
                                        {{
                                            moment(
                                                selectedBatchSession[
                                                    "batch_schedule"
                                                ]["school_period"][
                                                    "start_time"
                                                ],
                                                "HH:mm"
                                            ).format("HH:mm A")
                                        }}
                                    </h3>
                                </div>

                                <div class="flex items-center gap-1.5">
                                    <span
                                        class="text-xs font-medium text-brand-text-300"
                                        >{{ $t("common.period") }}
                                    </span>
                                    <div
                                        class="flex origin-left scale-95 items-center gap-1"
                                    >
                                        <span
                                            class="grid h-5 w-5 place-items-center rounded-full border border-black bg-brand-300 text-xs font-semibold leading-none text-white"
                                        >
                                            {{
                                                selectedBatchSession[
                                                    "batch_schedule"
                                                ]["school_period"]["name"]
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col">
                            <h3 class="text-lg font-semibold">
                                {{ parseLevel(batch["level"]["name"]) }}
                                {{ batch["section"] }}
                                {{
                                    selectedBatchSession["batch_schedule"][
                                        "batch_subject"
                                    ]["subject"]["full_name"]
                                }}
                            </h3>
                        </div>
                        <div
                            v-if="!showAISection && isTeacher()"
                            class="mt-3 flex w-fit cursor-pointer space-x-1 rounded-2xl bg-purple-600 px-3 py-1.5 text-xs text-white hover:font-medium"
                            @click="showAISection = true"
                        >
                            <SparklesIcon class="w-3.5 text-white" />
                            <div>
                                {{ $t("lessonPlanFormModal.showAISection") }}
                            </div>
                        </div>
                    </div>

                    <div class="flex w-full grow items-center justify-between">
                        <div
                            class="flex h-full flex-col pt-4"
                            :class="
                                showAISection ? 'w-11/12' : 'w-7/12 mx-auto'
                            "
                        >
                            <form
                                v-if="
                                    updateLessonPlan ||
                                    !!!selectedBatchSession['lesson_plan_id']
                                "
                                class="flex h-full w-full flex-col gap-3"
                            >
                                <div
                                    v-if="
                                        !!!selectedBatchSession[
                                            'lesson_plan_id'
                                        ]
                                    "
                                    class="flex flex-col"
                                >
                                    <h3 class="text-sm font-semibold">
                                        {{
                                            $t(
                                                "lessonPlanFormModal.newLessonPlan"
                                            )
                                        }}
                                    </h3>
                                    <h3 class="text-sm text-gray-600">
                                        {{
                                            $t(
                                                "lessonPlanFormModal.addTheTopic"
                                            )
                                        }}
                                    </h3>
                                </div>

                                <div
                                    class="flex w-full items-end justify-between space-x-4"
                                >
                                    <TextInput
                                        v-model="form.topic"
                                        required
                                        :label="$t('lessonPlanFormModal.topic')"
                                        :placeholder="
                                            $t(
                                                'lessonPlanFormModal.topicOfLessonPlan'
                                            )
                                        "
                                        class="w-full"
                                        :disabled="isAdmin()"
                                    />
                                    <div class="flex h-8 items-center">
                                        <SparklesIcon
                                            class="w-4 cursor-pointer text-purple-500 hover:scale-105 hover:text-fuchsia-500"
                                            @click="
                                                handleGenerateNoteSuggestions()
                                            "
                                        />
                                    </div>
                                </div>
                                <div
                                    class="flex w-full justify-between space-x-4 pr-4"
                                >
                                    <TextArea
                                        v-model="form.description"
                                        rows="30"
                                        leading="leading-loose"
                                        class="w-full pr-4"
                                        :label="$t('common.description')"
                                        :disabled="isAdmin()"
                                        :placeholder="
                                            $t(
                                                'lessonPlanFormModal.addLessonPlanDescription'
                                            )
                                        "
                                    />
                                </div>
                                <div class="flex w-full justify-end px-7">
                                    <SecondaryButton
                                        :title="$t('common.submit')"
                                        class="w-2/12 !rounded-2xl bg-brand-450 text-white"
                                        @click="handleSubmit"
                                    />
                                </div>
                            </form>

                            <template v-else>
                                <div class="flex flex-col space-y-6">
                                    <div
                                        class="flex w-full items-end justify-between lg:items-center"
                                    >
                                        <div
                                            class="flex w-10/12 items-center justify-center space-x-4 text-center font-semibold"
                                        >
                                            <div class="flex h-8 items-center">
                                                <SparklesIcon
                                                    class="w-4 cursor-pointer text-purple-500 hover:scale-105 hover:text-fuchsia-500"
                                                    @click="
                                                        handleGenerateNoteSuggestions()
                                                    "
                                                />
                                            </div>

                                            <div class="">
                                                {{
                                                    selectedBatchSession[
                                                        "lesson_plan"
                                                    ]["topic"]
                                                }}
                                            </div>
                                        </div>

                                        <div
                                            class="flex justify-evenly rounded-2xl bg-brand-400 px-4 hover:scale-105"
                                        >
                                            <PencilIcon
                                                class="w-4 text-white"
                                            />
                                            <SecondaryButton
                                                :title="
                                                    $t(
                                                        'lessonPlanFormModal.edit'
                                                    )
                                                "
                                                class="text-white"
                                                @click="updateLessonPlan = true"
                                            />
                                        </div>
                                    </div>

                                    <h3 class="text-sm leading-loose">
                                        {{
                                            selectedBatchSession["lesson_plan"][
                                                "description"
                                            ]
                                        }}
                                    </h3>
                                </div>
                            </template>
                        </div>

                        <div
                            v-if="!showAISection"
                            class="scrollbar-hide flex h-screen w-4/12 flex-col items-center space-y-4 overflow-y-scroll rounded-tl-md border-l border-t border-zinc-300 p-4"
                        >
                            <div class="flex w-11/12 flex-col">
                                <h3 class="text-sm font-semibold">
                                    {{
                                        $t(
                                            "lessonPlanFormModal.previousLessonPlans"
                                        )
                                    }}
                                </h3>
                                <h3 class="text-sm text-gray-600">
                                    {{
                                        $t(
                                            "lessonPlanFormModal.otherLessonPlans"
                                        )
                                    }}
                                    <span class="font-semibold">
                                        {{
                                            moment(
                                                selectedBatchSession.date
                                            ).format("MMMM Do")
                                        }}
                                    </span>
                                </h3>
                            </div>

                            <div class="flex w-11/12 flex-col gap-6">
                                <template
                                    v-if="
                                        previousBatchSessionsWithLessonPlans.length <
                                        1
                                    "
                                >
                                    <div
                                        class="grid h-36 w-full place-items-center"
                                    >
                                        <div
                                            class="flex w-full flex-col items-center justify-center gap-1"
                                        >
                                            <ExclamationTriangleIcon
                                                class="h-6 w-6 text-red-600"
                                            />
                                            <h3
                                                class="text-sm font-medium text-black"
                                            >
                                                {{
                                                    $t(
                                                        "lessonPlanFormModal.noPreviousLessonPlans"
                                                    )
                                                }}
                                            </h3>
                                            <h3
                                                class="max-w-[18rem] text-center text-xs text-gray-600"
                                            >
                                                {{
                                                    $t(
                                                        "lessonPlanFormModal.noPreviousLessonPlansDescription"
                                                    )
                                                }}
                                            </h3>
                                        </div>
                                    </div>
                                </template>

                                <template v-else>
                                    <div
                                        v-for="(
                                            prevBatchSession, pBS
                                        ) in previousBatchSessionsWithLessonPlans"
                                        :key="pBS"
                                        class="scrollbar-hide flex h-fit flex-col items-center justify-center space-y-2 overflow-hidden overflow-y-scroll rounded-lg bg-brand-50 p-2 shadow-sm"
                                    >
                                        <h3
                                            class="text-center text-sm font-medium"
                                        >
                                            {{
                                                prevBatchSession["lesson_plan"][
                                                    "topic"
                                                ]
                                            }}
                                        </h3>
                                        <h3
                                            class="text-xs font-light text-black"
                                        >
                                            <ReadMoreLess
                                                lines="4"
                                                :text="
                                                    prevBatchSession[
                                                        'lesson_plan'
                                                    ]['description']
                                                "
                                                class=""
                                            />
                                        </h3>
                                        <div class="mt-2 flex w-full gap-8">
                                            <div
                                                class="flex w-full items-center justify-between"
                                            >
                                                <div
                                                    class="flex origin-left scale-[.8] items-center gap-2"
                                                >
                                                    <span
                                                        class="rounded-xl border border-black bg-white px-2 py-1 text-xs font-semibold leading-none"
                                                    >
                                                        {{
                                                            dayLabel(
                                                                prevBatchSession[
                                                                    "batch_schedule"
                                                                ]["day_of_week"]
                                                            )
                                                        }}
                                                    </span>
                                                    <h3
                                                        class="font-semibold text-gray-600"
                                                    >
                                                        {{
                                                            moment(
                                                                prevBatchSession.date
                                                            ).format("MMMM Do")
                                                        }}
                                                    </h3>
                                                </div>

                                                <div
                                                    class="flex origin-right scale-90 items-center gap-1.5"
                                                >
                                                    <div class="flex gap-1">
                                                        <div
                                                            class="flex items-end gap-1.5"
                                                        >
                                                            <span
                                                                class="text-sm font-semibold text-brand-text-300"
                                                                >{{
                                                                    $t(
                                                                        "common.period"
                                                                    )
                                                                }}</span
                                                            >
                                                            <div
                                                                class="flex origin-left scale-95 items-center gap-1"
                                                            >
                                                                <span
                                                                    class="grid h-5 w-5 place-items-center rounded-full border border-black bg-brand-300 text-xs font-semibold leading-none text-white"
                                                                >
                                                                    {{
                                                                        prevBatchSession[
                                                                            "batch_schedule"
                                                                        ][
                                                                            "school_period"
                                                                        ][
                                                                            "name"
                                                                        ]
                                                                    }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <h3
                                                            class="text-sm font-semibold text-brand-text-300"
                                                        >
                                                            {{ "09:30 AM" }}
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
import { isAdmin, isTeacher, parseLevel } from "@/utils.js";
import {
    CalendarIcon,
    ClockIcon,
    ExclamationTriangleIcon,
    PencilIcon,
} from "@heroicons/vue/24/outline/index.js";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import TextArea from "@/Components/TextArea.vue";
import { computed, ref, watch } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import ReadMoreLess from "@/Components/ReadMoreLess.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import LessonPlanCopilot from "@/Views/Teacher/Views/LessonPlans/LessonPlanCopilot.vue";
import { SparklesIcon } from "@heroicons/vue/20/solid";

const props = defineProps({
    batchSession: {
        type: Object,
        required: true,
    },
    view: {
        type: Boolean,
        required: true,
    },
});

const emits = defineEmits(["update:view"]);

const showAISection = ref(false);

const batch = computed(() => usePage().props.batch);
const selectedBatchSession = computed(() => props.batchSession);
const batchSessions = computed(() => usePage().props["batch_sessions"]);
const previousBatchSessionsWithLessonPlans = computed(() => {
    if (selectedBatchSession.value) {
        const allBatchSessions = Object.values(batchSessions.value).flat();
        const currentBatchSessionIndex = allBatchSessions.findIndex(
            (bs) => bs.id === selectedBatchSession.value.id
        );

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
const generateNoteSuggestions = ref(false);
const generateQuestionSuggestions = ref(false);

const openForm = computed({
    get() {
        return props.view;
    },
    set(value) {
        emits("update:view", value);
    },
});

function closeForm() {
    updateLessonPlan.value = false;
    openForm.value = false;
    showAISection.value = false;
}

function dayLabel(day) {
    let label;
    day[0].toLowerCase() === "t" ? (label = day[0] + day[1]) : (label = day[0]);
    return label.toUpperCase();
}

function prepareForm(batchSessionId) {
    let batchSession = null;

    // Find the batch session in all weeks
    for (const weekKey in batchSessions.value) {
        batchSession = batchSessions.value[weekKey].find(
            (bs) => bs.id === batchSessionId
        );
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
            form.topic = "";
            form.description = "";
        }

        form.batch_session_id = batchSessionId;
        openForm.value = true;

        selectedBatchSession.value = batchSession;
    } else {
        console.error("Batch session not found in any week");
    }
}

const updateLessonPlan = ref(false);
const form = useForm({
    batch_session_id: "",
    topic: "",
    description: "",
});

function handleSubmit() {
    form.post("/teacher/lesson-plan", {
        onSuccess: () => {
            form.reset();
            if (updateLessonPlan.value) {
                updateLessonPlan.value = false;
            } else {
                openForm.value = false;
            }
        },
    });
}

watch(selectedBatchSession, () => {
    prepareForm(selectedBatchSession.value.id);
});

const updateSelectedText = (text) => {
    form.description += " " + text;
};

function handleGenerateNoteSuggestions() {
    // check if form.topic has value
    if (!form.topic) return;

    generateNoteSuggestions.value = true;
    showAISection.value = true;
}
</script>

<style scoped></style>
