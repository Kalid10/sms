<template>
    <div
        class="grid-rows-10 grid w-full grid-cols-5 place-items-center gap-x-5 gap-y-3"
    >
        <div
            v-for="(day, d) in [
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
            ]"
            :key="d"
            class="flex items-center justify-center py-1 text-brand-text-50"
        >
            <Heading size="xs">{{ day }}</Heading>
        </div>

        <div
            v-for="(subject, s) in schedule"
            :key="s"
            :class="[
                getColumnByDay(subject),
                getRowByName(subject),
                isCustomPeriod(subject['batch_subject']),
                isTeacherAssigned(subject),
            ]"
            class="flex w-10/12 rounded-lg border px-4 py-2"
        >
            <div
                v-if="!!subject['batch_subject']"
                class="flex flex-col space-y-2"
            >
                <Heading class="text-brand-text-50" size="sm"
                    >{{ subject["batch_subject"]["subject"]["short_name"] }}
                </Heading>
                <Heading
                    v-if="!!subject.batch_subject.teacher"
                    class="text-brand-450"
                    size="xs"
                    >Teacher
                    {{ subject.batch_subject.teacher?.user?.name }}
                </Heading>
                <div v-else-if="searchEditedSubject(subject)">
                    <Heading size="xs">
                        {{
                            editedBatchSubjects[subject?.batch_subject?.id]
                                ?.teacher?.user?.name
                        }}
                    </Heading>
                </div>
                <Heading
                    v-else
                    size="xs"
                    class="cursor-pointer hover:underline"
                    @click="
                        showSetTeacherModal = true;
                        selectedSubject = subject;
                    "
                >
                    Set Teacher
                </Heading>
            </div>
            <div v-else class="flex flex-col">
                <Heading size="sm"
                    >{{ subject["school_period"]["name"] }}
                </Heading>
                <div class="flex items-center gap-1">
                    <ClockIcon class="h-3.5 w-3.5 stroke-2" />
                    <Heading class="!font-normal" size="xs"
                        >{{ subject["school_period"]["duration"] }}
                        minutes
                    </Heading>
                </div>
            </div>
        </div>
    </div>
    <div class="flex w-full justify-end px-4 pt-8">
        <PrimaryButton
            v-if="isBeingEdited"
            title="Update"
            class="w-fit bg-brand-100 !px-10 py-2 !font-semibold"
            @click="saveBatchSubjects"
        />
    </div>
    <Modal v-model:view="showSetTeacherModal" class="!z-40">
        <FormElement
            :show-clear-button="false"
            :title="
                'Update ' +
                selectedSubject['batch_subject']['subject']['full_name']
            "
            @submit="updateEditedBatchSubject"
        >
            <div
                v-if="selectedSubject?.teacher_id"
                class="py-4 text-sm font-semibold"
            >
                Assigned Teacher: {{ selectedSubject?.teacher?.user?.name }}
            </div>
            <div
                v-else
                class="flex flex-col items-center justify-center space-y-2 py-4 text-center text-sm font-semibold"
            >
                <div>There is no teacher assigned to this subject.</div>
            </div>
            <TextInput
                v-model="searchTeacherText"
                placeholder="Search For Teacher Name"
            />

            <div v-if="selectedTeacher" class="text-sm font-medium">
                You have selected:
                <span class="font-bold uppercase">
                    {{ selectedTeacher.user.name }}
                </span>
                , once you save this change, the teacher will be assigned to
                this subject.
            </div>

            <SearchTeacher @selected-teacher="updateTeacher" />
        </FormElement>
    </Modal>

    <Loading v-if="isLoading" is-full-screen />
</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import { ClockIcon } from "@heroicons/vue/24/outline";
import { computed, reactive, ref, watch } from "vue";
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import SearchTeacher from "@/Views/BatchSchedule/SearchTeacher.vue";
import { router } from "@inertiajs/vue3";
import debounce from "lodash/debounce";
import Loading from "@/Components/Loading.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    batch: {
        type: Object,
        required: true,
    },
});

const schedule = computed(() => props.batch.schedule);

function getRowByName(schedule) {
    let style = "";

    style += [
        "row-start-1",
        "row-start-2",
        "row-start-3",
        "row-start-4",
        "row-start-5",
        "row-start-6",
        "row-start-7",
        "row-start-8",
        "row-start-9",
        "row-start-10",
        "row-start-11",
        "row-start-12",
        "row-start-13",
    ][schedule["school_period"]["order"]];

    return style;
}

function isCustomPeriod(schedule) {
    if (schedule === null) return "text-gray-500";
    return "bg-brand-100 border-brand-150 border-brand-200";
}

function getColumnByDay(schedule) {
    let style = "";

    switch (schedule["day_of_week"]) {
        case "monday":
            style += "col-start-1";
            break;
        case "tuesday":
            style += "col-start-2";
            break;
        case "wednesday":
            style += "col-start-3";
            break;
        case "thursday":
            style += "col-start-4";
            break;
        case "friday":
            style += "col-start-5";
            break;
        default:
            style += "";
            break;
    }

    return style;
}

function isTeacherAssigned(subject) {
    if (
        !subject?.batch_subject.teacher_id &&
        !editedBatchSubjects[subject?.batch_subject?.id]
    )
        return "border-2 border-red-500";
    return "border border-brand-100";
}

const showSetTeacherModal = ref(false);
const selectedTeacher = ref(null);
const searchTeacherText = ref("");
const selectedSubject = ref(null);
const editedBatchSubjects = reactive({});
const isLoading = ref(false);
const isBeingEdited = ref(false);

const updateTeacher = (teacher) => {
    selectedTeacher.value = teacher;
};

const updateEditedBatchSubject = () => {
    editedBatchSubjects[selectedSubject.value.batch_subject?.id] = {
        selected_subject: selectedSubject.value,
        teacher_id: selectedTeacher.value?.id
            ? selectedTeacher.value.id
            : selectedSubject.value.teacher_id,
        teacher: selectedTeacher.value
            ? selectedTeacher.value
            : selectedSubject.value.teacher,
    };
    closeModal();
};

const debouncedUpdate = debounce(() => {
    isLoading.value = true;
    router.get(
        "/admin/batch-schedules",
        {
            batch_id: selectedSubject.value.batch_subject.batch_id,
            search: searchTeacherText.value,
        },
        {
            only: ["teachers"],
            preserveState: true,
            onFinish: () => (isLoading.value = false),
        }
    );
}, 300);

watch(searchTeacherText, debouncedUpdate);

const searchEditedSubject = (subject) => {
    // Check and return if subject is found in editedBatchSubjects
    return !!editedBatchSubjects[subject?.batch_subject?.id];
};

const closeModal = () => {
    showSetTeacherModal.value = false;
    searchTeacherText.value = "";
    selectedTeacher.value = null;
    isBeingEdited.value = true;
};

const saveBatchSubjects = () => {
    isLoading.value = true;

    const data = Object.values(editedBatchSubjects).map((item) => {
        return {
            id: item.selected_subject?.batch_subject?.id,
            teacher_id: item.teacher_id,
            teacher: item.teacher,
        };
    });

    router.post(
        "/admin/batch-schedules/batch-subjects/update",
        {
            batch_subjects: data,
        },
        {
            preserveState: true,
            onFinish: () => {
                isLoading.value = false;
            },
        }
    );
};
</script>

<style scoped></style>
