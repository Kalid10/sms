<template>
    <div
        v-if="errorBatchSubjectsMessage"
        class="flex w-full items-center justify-center"
    >
        <span class="w-fit rounded-lg bg-red-600 p-4 text-center text-white">
            {{ errorBatchSubjectsMessage }}
        </span>
    </div>
    <div
        v-if="batchSubjects"
        class="flex h-full w-full flex-col items-center justify-evenly space-y-2"
    >
        <div class="py-3 text-center text-2xl font-semibold">Subject List</div>

        <div
            v-if="batchSubjects"
            class="flex w-full flex-wrap justify-evenly gap-6 space-x-2"
        >
            <div
                v-for="(item, index) in displayBatchSubjects"
                :key="index"
                class="flex w-2/12 cursor-pointer flex-col space-y-2 rounded-lg border-2 p-3 pl-4 hover:scale-105 hover:text-white"
                :class="
                    item?.teacher_id
                        ? 'bg-white text-black border-brand-500 hover:bg-brand-400'
                        : 'bg-red-600 text-white border-gray-200'
                "
                @click="
                    selectedBatchSubject = item;
                    showEditModal = true;
                "
            >
                <div class="text-sm font-bold uppercase">
                    {{ item.subject.full_name }}
                </div>
                <div class="text-xs">
                    <span v-if="item.teacher_id" class="font-medium uppercase">
                        {{ item.teacher.user.name }}
                    </span>
                    <span v-else class="font-bold"> Teacher is not set </span>
                </div>

                <div class="text-xs font-semibold uppercase">
                    <span v-if="item.weekly_frequency">
                        {{ item.weekly_frequency }} periods a week
                    </span>
                    <span v-else> Weekly frequency is not set </span>
                </div>
            </div>
        </div>

        <div class="flex w-11/12 justify-between pt-14">
            <SecondaryButton
                title="Save"
                class="w-fit !rounded-2xl bg-brand-400 py-2 !px-10 text-white"
                @click="saveBatchSubjects"
            />
        </div>

        <div
            class="flex w-5/12 flex-col items-center justify-center space-y-2 rounded-lg border-2 border-black bg-brand-400 p-4 text-white"
        >
            <div class="py-2">
                Teachers have been allocated to their respective subjects.
            </div>
            <SecondaryButton
                title="Generate Schedule"
                class="w-fit !rounded-2xl bg-brand-100 !px-10 font-bold"
                @click="generateSchedule"
            />
        </div>
    </div>

    <div
        v-else
        class="flex w-full items-center justify-center py-10 text-2xl font-light"
    >
        <span class="w-11/12 text-center"
            >No grade selected, please select a grade from the list to your
            right to continue With the schedule setup process and generate a
            class schedule.
        </span>
    </div>

    <Modal v-model:view="showEditModal" @close="closeModals">
        <FormElement
            :show-clear-button="false"
            :title="selectedBatchSubject?.subject?.full_name"
            @submit="updateEditedBatchSubject"
        >
            <TextInput
                v-model="weeklyFrequency"
                label="Weekly Frequency"
                type="number"
                placeholder="Weekly Frequency"
            />
            <div
                v-if="selectedBatchSubject?.teacher_id"
                class="text-sm font-semibold"
            >
                Assigned Teacher: {{ selectedBatchSubject.teacher.user.name }}
            </div>
            <div
                v-else
                class="flex flex-col items-center justify-center space-y-2 text-center text-sm"
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
            <div v-if="teachers?.length && !selectedTeacher" class="py-2">
                <div
                    v-for="(item, index) in teachers"
                    :key="index"
                    class="flex cursor-pointer flex-col space-y-2 px-2 py-3 text-sm hover:rounded-lg hover:bg-brand-400 hover:text-white"
                    :class="index % 2 === 0 ? 'bg-brand-50' : 'bg-white'"
                    @click="selectedTeacher = item"
                >
                    <div>
                        {{ item.user.name }}({{ item.user.phone_number }}),
                        Active weekly sessions
                        {{ sumWeeklyFrequency(item.active_batch_subjects) }}
                    </div>
                </div>
            </div>
            <Loading v-if="isLoading" type="bounce" />
        </FormElement>
    </Modal>
    <Loading v-if="isLoading" is-full-screen />
</template>

<script setup>
import { computed, reactive, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import debounce from "lodash/debounce";
import Loading from "@/Components/Loading.vue";
import { useUIStore } from "@/Store/ui";

const props = defineProps({
    batch: {
        type: Object,
        required: true,
    },
});

const isLoading = ref(false);
const batchSubjects = computed(() => usePage().props.batchSubjects);
const selectedBatchSubject = ref(null);
const showEditModal = ref(false);
const teachers = computed(() => usePage().props.teachers);

const searchTeacherText = ref("");
const debouncedUpdate = debounce(() => {
    isLoading.value = true;
    router.get(
        "/admin/batch-schedules",
        { search_teacher_text: searchTeacherText.value },
        {
            only: ["teachers"],
            preserveState: true,
            onFinish: () => (isLoading.value = false),
        }
    );
}, 300);

watch(searchTeacherText, debouncedUpdate);

const closeModals = () => {
    showEditModal.value = false;
    searchTeacherText.value = "";
    selectedTeacher.value = null;
};

const weeklyFrequency = ref(0);
const selectedTeacher = ref(null);
const editedBatchSubjects = reactive({});

watch(selectedBatchSubject, (newVal) => {
    if (newVal) weeklyFrequency.value = newVal.weekly_frequency || 5;
});

const updateEditedBatchSubject = () => {
    editedBatchSubjects[selectedBatchSubject.value.id] = {
        selected_batch_subject: selectedBatchSubject.value,
        weekly_frequency: weeklyFrequency.value,
        teacher_id: selectedTeacher.value?.id
            ? selectedTeacher.value.id
            : selectedBatchSubject.value.teacher_id,
        teacher: selectedTeacher.value
            ? selectedTeacher.value
            : selectedBatchSubject.value.teacher,
    };
    closeModals();
};

const displayBatchSubjects = computed(() => {
    return batchSubjects.value.map((item) => {
        const edited = editedBatchSubjects[item.id];
        if (edited) {
            return { ...item, ...edited };
        }
        return item;
    });
});

const saveBatchSubjects = () => {
    isLoading.value = true;

    const data = Object.values(editedBatchSubjects).map((item) => {
        return {
            id: item.selected_batch_subject.id,
            weekly_frequency: item.weekly_frequency,
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
            onFinish: () => (isLoading.value = false),
        }
    );
};

const uiStore = useUIStore();
const errorBatchSubjects = ref(null);
const errorBatchSubjectsMessage = ref(null);
const generateSchedule = () => {
    isLoading.value = true;
    errorBatchSubjects.value = null;
    errorBatchSubjectsMessage.value = null;
    router.post(
        "/admin/batch-schedules/generate",
        {},
        {
            preserveState: true,
            onSuccess: () => {
                uiStore.setLoading(true, "Generating Class Schedule");
            },
            onError: (error) => {
                uiStore.setLoading(false);

                if (error.schedule_generator_error)
                    errorBatchSubjectsMessage.value =
                        error.schedule_generator_error;

                if (error.error_batch_subjects) {
                    errorBatchSubjects.value = error.error_batch_subjects;
                }
            },
            onFinish: () => (isLoading.value = false),
        }
    );
};

function sumWeeklyFrequency(batchSubjects) {
    console.log("batchSubjects", batchSubjects);
    return batchSubjects.reduce(
        (total, subject) => total + subject.weekly_frequency,
        0
    );
}
</script>

<style scoped></style>
