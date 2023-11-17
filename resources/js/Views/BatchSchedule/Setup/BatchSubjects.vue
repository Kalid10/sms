<template>
    <div v-if="batchSubjects" class="flex h-3/5 w-full justify-between">
        <div
            class="flex h-full w-7/12 flex-col items-center justify-evenly space-y-2 pr-5"
        >
            <div class="py-2 text-center text-2xl font-semibold">
                Assign Subject Teachers
            </div>

            <div
                v-if="batchSubjects"
                class="flex w-full flex-wrap justify-evenly gap-6 space-x-2"
            >
                <div
                    v-for="(item, index) in displayBatchSubjects"
                    :key="index"
                    class="flex w-2/12 min-w-fit cursor-pointer flex-col space-y-2 rounded-lg border-2 p-3 text-center hover:scale-105 hover:text-white"
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
                    <div class="text-xs font-bold uppercase">
                        {{ item.subject.full_name }}
                    </div>
                    <div class="text-xs">
                        <span
                            v-if="item.teacher_id"
                            class="font-medium uppercase"
                        >
                            {{ item.teacher.user.name }}
                        </span>
                        <span v-else class="font-bold">
                            Teacher is not set
                        </span>
                    </div>

                    <div class="text-xs font-semibold uppercase">
                        <span v-if="item.weekly_frequency">
                            {{ item.weekly_frequency }} periods
                        </span>
                        <span v-else> Weekly frequency is not set </span>
                    </div>
                </div>
            </div>

            <div class="flex w-11/12 justify-center pt-14">
                <SecondaryButton
                    title="Update"
                    class="w-fit !rounded-2xl bg-brand-400 !px-10 py-2 text-white"
                    @click="saveBatchSubjects"
                />
            </div>
        </div>
        <div
            class="flex w-4/12 flex-col space-y-4 rounded-lg border-2 border-gray-600 px-3 py-5"
        >
            <div class="text-center text-xl font-light">
                View Teacher Schedule
            </div>

            <TextInput
                v-model="searchTeacherText"
                placeholder="Search Teacher"
                class="!rounded-2xl"
            />

            <SearchTeacher
                v-if="viewTeacherList"
                class="!text-center"
                @selected-teacher="loadTeacherSchedule"
            />

            <TeacherBatchSubject v-else />
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

            <SearchTeacher @selected-teacher="updateTeacher" />
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
import SearchTeacher from "@/Views/BatchSchedule/SearchTeacher.vue";
import TeacherBatchSubject from "@/Views/BatchSchedule/TeacherBatchSubject.vue";

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

const viewTeacherList = ref(false);
const searchTeacherText = ref("");

const debouncedUpdate = debounce(() => {
    isLoading.value = true;
    viewTeacherList.value = true;
    router.get(
        "/admin/batch-schedules",
        {
            batch_id: props.batch.id,
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
            onFinish: () => {
                isLoading.value = false;
            },
        }
    );
};

const updateTeacher = (teacher) => {
    selectedTeacher.value = teacher;
};

const loadTeacherSchedule = (teacher) => {
    viewTeacherList.value = false;
    router.get(
        "/admin/batch-schedules",
        {
            batch_id: props.batch.id,
            teacher_id: teacher.id,
        },
        {
            preserveState: true,
            only: ["teacher"],
        }
    );
};
</script>

<style scoped></style>
