<template>
    <div class="scrollbar-hide h-screen overflow-y-auto pb-8">
        <FormElement
            class="h-fit pb-5"
            title="Add Absentee"
            @submit="handleSubmit"
        >
            <div class="flex h-full flex-col space-y-3 overflow-y-auto">
                <TextInput
                    v-model="searchText"
                    :placeholder="$t('addAbsentees.searchTextPlaceholder')"
                    class="w-full"
                />

                <div
                    v-if="absenteeStudents.length"
                    class="flex w-full flex-col space-y-2 rounded-lg border border-brand-450 bg-brand-150 px-4 py-3 shadow-sm"
                >
                    <div class="text-sm font-light">
                        {{ $t("addAbsentees.absenteeStudents") }}
                    </div>
                    <div class="flex w-full flex-wrap space-x-1.5">
                        <div
                            v-for="(item, index) in absenteeStudents"
                            :key="index"
                            class="flex w-fit space-x-1 rounded-md bg-brand-450 px-2 py-1 text-xs text-white"
                        >
                            <div>
                                {{ item.name }}
                            </div>
                            <XMarkIcon
                                class="w-4 cursor-pointer text-red-500 hover:scale-105"
                                @click="
                                    absenteeStudents.splice(index, 1);
                                    studentList[index].isAbsentee = false;
                                "
                            />
                        </div>
                    </div>
                </div>
                <div
                    v-for="(item, index) in studentList"
                    :key="index"
                    class="mt-3 flex w-full flex-col justify-between p-2 text-sm font-semibold"
                    :class="
                        selectedReasonInput === index
                            ? 'border-2 border-brand-500 p-3 rounded-lg bg-brand-300 text-white'
                            : index % 2 === 0
                            ? 'bg-brand-50'
                            : 'bg-white border-none'
                    "
                >
                    <div class="flex w-full justify-between px-4">
                        <div class="w-6/12">
                            {{ item.name }}
                        </div>
                        <Toggle
                            v-model="studentList[index].isAbsentee"
                            class="w-5/12"
                            @change="
                                handleAbsenteeChanges(
                                    {
                                        user_id: item.user_id,
                                        name: item.name,
                                        reason: studentList[index].reason,
                                    },
                                    studentList[index].isAbsentee
                                )
                            "
                        />
                        <ChatBubbleBottomCenterIcon
                            :class="
                                studentList[index].reason
                                    ? 'text-black'
                                    : 'text-brand-text-350 hover:text-black'
                            "
                            class="w-5 cursor-pointer hover:scale-125"
                            @click="handleReasonClick(index, item)"
                        />
                    </div>
                    <div v-if="selectedReasonInput === index" class="p-4">
                        <TextArea
                            v-model="studentList[index].reason"
                            rows="4"
                            :class="
                                selectedReasonInput === index
                                    ? '!text-white'
                                    : 'text-black'
                            "
                            :label="$t('addAbsentees.reasonLabel')"
                            :placeholder="$t('addAbsentees.reasonPlaceholder')"
                        />
                    </div>
                </div>
                <Pagination
                    :preserve-state="true"
                    :links="batchStudents.links"
                    position="center"
                />
            </div>
        </FormElement>

        <Loading v-if="showLoading" />
    </div>
</template>
<script setup>
import FormElement from "@/Components/FormElement.vue";
import { router, usePage } from "@inertiajs/vue3";
import { computed, onMounted, reactive, ref, watch } from "vue";
import TextInput from "@/Components/TextInput.vue";
import Pagination from "@/Components/Pagination.vue";
import Toggle from "@/Components/Toggle.vue";
import { XMarkIcon } from "@heroicons/vue/20/solid";
import TextArea from "@/Components/TextArea.vue";
import { ChatBubbleBottomCenterIcon } from "@heroicons/vue/20/solid/index";
import debounce from "lodash/debounce";
import { toInteger } from "lodash";
import Loading from "@/Components/Loading.vue";

const inProgressSession = computed(() => usePage().props.in_progress_session);
const searchText = ref(usePage().props.filters.search);
const absenteeStudents = reactive([]);
const selectedReasonInput = ref(null);
const showLoading = ref(false);

onMounted(() => {
    updateAbsenteeList();
});

const emit = defineEmits(["close"]);

const batchStudents = computed(() => usePage().props.students);
const studentList = computed(() =>
    batchStudents.value.data.map((item) => ({
        user_id: item.student.user.id,
        reason: "",
        name: item.student.user.name,
    }))
);

function updateAbsenteeList() {
    // If there are absentees, push them to absenteeStudents
    if (inProgressSession.value.absentees.length > 0) {
        inProgressSession.value.absentees.forEach((item) => {
            const existingStudentIndex = absenteeStudents.findIndex(
                (absentee) => absentee.user_id === item.user.id
            );
            if (existingStudentIndex === -1) {
                // check if the student is already in the list
                absenteeStudents.push({
                    user_id: item.user.id,
                    reason: item.reason,
                    name: item.user.name,
                    isAbsentee: true,
                });
            }
        });
    }

    // Iterate over the studentList
    studentList.value.forEach((student) => {
        // If the student is in the absenteeStudents list
        const absentee = absenteeStudents.find(
            (item) => item.user_id === student.user_id
        );
        if (absentee) {
            // Update the isAbsentee property of the student
            student.isAbsentee = true;
        }
    });
}

function handleAbsenteeChanges(student, value) {
    const existingStudentIndex = absenteeStudents.findIndex(
        (item) => item.user_id === student.user_id
    );
    if (existingStudentIndex === -1 && value) {
        // Student not found in the array, so add them
        absenteeStudents.push(student);
    } else if (!value) {
        // Remove comment
        selectedReasonInput.value = null;

        // Student found in the array, so remove them
        absenteeStudents.splice(existingStudentIndex, 1);
    }
}

function handleSubmit() {
    showLoading.value = true;
    router.post(
        "/absentees/students/add",
        {
            batch_session_id: inProgressSession.value.id,
            user_type: "student",
            absentees: absenteeStudents,
        },
        {
            onSuccess: () => {
                emit("close");
            },
            onFinish: () => {
                showLoading.value = false;
            },
        }
    );
}

function handleReasonClick(index, student) {
    if (selectedReasonInput.value === index)
        return (selectedReasonInput.value = null);

    selectedReasonInput.value = index;
    studentList.value[index].isAbsentee = true;
    handleAbsenteeChanges(student);
}

const updateBatchInfo = () => {
    router.visit(
        "/teacher/class?batch_subject_id=" +
            toInteger(inProgressSession.value.batch_schedule.batch_subject.id) +
            "&search=" +
            searchText.value,
        {
            preserveState: true,
        }
    );
};

const debouncedUpdate = debounce(updateBatchInfo, 300);

watch(searchText, () => {
    debouncedUpdate();
});

watch(studentList, () => {
    updateAbsenteeList();
});
</script>
<style scoped></style>
