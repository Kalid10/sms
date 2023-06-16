<template>
    <div class="rounded-lg">
        <TableElement
            :columns="config"
            :selectable="false"
            :header="false"
            row-actionable
            class="px-3 pt-4 pb-2"
            :data="formattedStudentsData"
        >
            <template #filter>
                <div class="flex w-full">
                    <TextInput
                        v-model="searchKey"
                        class="w-7/12"
                        placeholder="Search for a student by name"
                    />
                </div>
            </template>

            <template #table-header>
                <div class="flex w-full justify-between pb-4">
                    <div class="pl-4">
                        <div class="pb-2 text-xl font-semibold">Students</div>
                        <div class="text-xs font-light text-gray-500">
                            List of all students in the school
                        </div>
                    </div>
                    <SecondaryButton
                        class="h-fit !rounded-2xl bg-zinc-700 text-white"
                        title="Register Student"
                        @click="router.get('/register/student')"
                    />
                </div>
                <div
                    class="flex w-full justify-between divide-x divide-gray-200 rounded-lg border p-3"
                >
                    <div class="w-3/12 text-center">
                        <div class="text-xl font-semibold text-gray-900">
                            1243
                        </div>
                        <div class="text-[0.65rem] font-medium text-gray-500">
                            Total Students
                        </div>
                    </div>
                    <div class="w-3/12 text-center">
                        <div class="text-xl font-semibold text-gray-900">
                            10
                        </div>
                        <div class="text-[0.65rem] font-medium text-gray-500">
                            Absentees Today
                        </div>
                    </div>
                    <div class="w-3/12 text-center">
                        <div class="text-xl font-semibold text-gray-900">4</div>
                        <div class="text-[0.65rem] font-medium text-gray-500">
                            Latest Period Absentees
                        </div>
                    </div>

                    <div class="w-3/12 text-center">
                        <div class="text-xl font-semibold text-gray-900">3</div>
                        <div class="text-[0.65rem] font-medium text-gray-500">
                            Suspended Students
                        </div>
                    </div>
                </div>
            </template>
            <template #row-actions="{ row }">
                <div class="flex flex-col items-center gap-1">
                    <button @click="transferStudent(row)">
                        <ArrowsRightLeftIcon
                            class="h-4 w-4 stroke-2 transition-all duration-150 hover:scale-125 hover:stroke-blue-700"
                        />
                    </button>
                </div>
            </template>

            <template #name-column="{ data }">
                <div class="flex items-start gap-2">
                    <span class="font-medium">{{ data }}</span>
                </div>
            </template>

            <template #email-column="{ data }">
                <div class="flex items-center gap-1">
                    <span class="text-xs">
                        {{ data }}
                    </span>
                </div>
            </template>

            <template #grade-column="{ data }">
                <div class="flex items-center gap-1">
                    <span class="text-xs font-semibold">
                        {{ data }}
                    </span>
                </div>
            </template>

            <template #footer>
                <Pagination :links="students.links" position="center" />
            </template>

            <template #empty-data>
                <div class="flex flex-col items-center justify-center">
                    <ExclamationTriangleIcon
                        class="mb-2 h-6 w-6 text-negative-50"
                    />
                    <p class="text-sm font-semibold">No data found</p>
                    <div v-if="searchKey.length">
                        <p
                            v-if="searchKey === null"
                            class="text-sm text-gray-500"
                        >
                            No student has been enrolled
                        </p>
                        <p v-else class="text-center text-sm text-gray-500">
                            Your search query "<span
                                class="font-medium text-black"
                                >{{ searchKey }}</span
                            >" did not match
                            <span class="block">any student's name</span>
                        </p>
                    </div>
                </div>
            </template>
        </TableElement>
    </div>
    <Modal v-model:view="isModalOpen">
        <FormElement
            :title="
                'Transfer Student ' +
                selectedStudentForTransfer.name +
                ' from ' +
                ' Grade ' +
                selectedStudentForTransfer.grade
            "
            @submit="submit"
        >
            <p class="text-xs text-gray-500">
                Transferring a student will remove theStum from their current
                section and add them to the selected section.
            </p>

            <RadioGroupPanel
                v-model="transferForm.destination_batch_id"
                :options="transferOptions"
                label="Select Destination Section"
                name="sections"
            />
        </FormElement>
    </Modal>
</template>
<script setup>
import TableElement from "@/Components/TableElement.vue";
import { computed, ref, watch } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { debounce } from "lodash";
import TextInput from "@/Components/TextInput.vue";
import {
    ArrowsRightLeftIcon,
    ExclamationTriangleIcon,
} from "@heroicons/vue/24/outline";
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue";
import RadioGroupPanel from "@/Components/RadioGroupPanel.vue";
import Pagination from "@/Components/Pagination.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const isModalOpen = ref(false);

const students = computed(() => {
    return usePage().props.students;
});

const formattedStudentsData = computed(() => {
    return students.value.data.map((student) => {
        return {
            id: student.id,
            name: student.user.name,
            email: student.user.email,
            gender: student.user.gender,
            grade:
                student.current_batch[0]?.level.name +
                "-" +
                student.current_batch[0]?.section,
        };
    });
});

const batches = computed(() => {
    return usePage().props.batches;
});

const selectedStudentForTransfer = ref(null);

const transferOptions = computed(() => {
    return batches.value
        .filter((batch) => {
            return (
                batch.level_id === selectedStudentForTransfer.value.level_id &&
                batch.id !== selectedStudentForTransfer.value.batch_id
            );
        })
        .map((batch, b) => {
            return {
                id: b,
                value: batch.id,
                label: batch.level.name + "-" + batch.section,
                description: `Homeroom Teacher: ${batch.homeroom_teacher.teacher.user.name}`,
            };
        });
});

function transferStudent(row) {
    selectedStudentForTransfer.value = row;
    isModalOpen.value = !isModalOpen.value;
    transferForm.student_id = selectedStudentForTransfer.value.id;
}

const transferForm = useForm({
    student_id: null,
    destination_batch_id: null,
});

function submit() {
    transferForm.post("/batches/students/transfer", {
        onSuccess: () => {
            isModalOpen.value = false;
        },
    });
}

const searchKey = ref("");
const perPage = ref(15);

const search = debounce(() => {
    router.get(
        "/students/",
        {
            search: searchKey.value,
            perPage: perPage.value,
        },
        {
            only: ["students"],
            preserveState: true,
            replace: true,
        }
    );
}, 300);

watch([searchKey, perPage], () => {
    search();
});

const config = [
    {
        name: "Name",
        key: "name",
        link: "/students/{id}",
        align: "left",
        type: "custom",
    },
    {
        name: "Email",
        key: "email",
        type: "custom",
    },
    {
        name: "Gender",
        key: "gender",
        type: "enum",
        options: ["female", "male"],
    },
    {
        name: "Grade",
        key: "grade",
        type: "custom",
        align: "right",
    },
];
</script>
