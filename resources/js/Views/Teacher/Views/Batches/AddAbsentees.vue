<template>
    <div class="h-screen">
        <FormElement class="h-full" title="Add Absentee" @submit="handleSubmit">
            <div class="flex h-full flex-col space-y-3 overflow-y-auto">
                <TextInput
                    v-model="searchText"
                    placeholder="Search Student"
                    class="w-full"
                />

                <div
                    v-if="absenteeStudents.length"
                    class="flex w-full flex-col space-y-2 rounded-lg bg-zinc-200 py-3 px-4 shadow-md"
                >
                    <div class="text-sm font-light">Absentee Students</div>
                    <div class="flex w-full flex-wrap space-x-1.5">
                        <div
                            v-for="(item, index) in absenteeStudents"
                            :key="index"
                            class="flex w-fit space-x-1 rounded-md bg-zinc-800 py-1 px-2 text-xs text-white"
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
                    :class="index % 2 === 0 ? 'bg-zinc-100' : 'bg-white'"
                >
                    <div class="flex w-full justify-between px-4">
                        <div class="w-6/12">
                            {{ item.name }}
                        </div>
                        <Toggle
                            v-model="studentList[index].isAbsentee"
                            class="w-5/12"
                            @change="
                                handleAbsenteeChanges({
                                    user_id: item.user_id,
                                    name: item.name,
                                    reason: studentList[index].reason,
                                })
                            "
                        />
                        <ChatBubbleBottomCenterIcon
                            :class="
                                studentList[index].reason
                                    ? 'text-black'
                                    : 'text-gray-300 hover:text-black'
                            "
                            class="w-5 cursor-pointer hover:scale-125"
                            @click="handleReasonClick(index, item)"
                        />
                    </div>
                    {{ selectedReasonInput }}
                    <div v-if="selectedReasonInput === index" class="p-4">
                        <TextArea
                            v-model="studentList[index].reason"
                            rows="4"
                            label="Reason"
                            placeholder="Add reason on why the student is absentee"
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

function handleAbsenteeChanges(student) {
    const existingStudentIndex = absenteeStudents.findIndex(
        (item) => item.user_id === student.user_id
    );
    if (existingStudentIndex === -1) {
        // Student not found in the array, so add them
        absenteeStudents.push(student);
    } else {
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

function handleReasonClick(index) {
    if (selectedReasonInput.value === index)
        return (selectedReasonInput.value = null);

    selectedReasonInput.value = index;
    studentList.value[index].isAbsentee = true;
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
