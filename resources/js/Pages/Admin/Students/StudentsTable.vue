<template>
    <div class="rounded-lg">
        <TableElement
            :columns="config"
            :selectable="false"
            :header="false"
            row-actionable
            :filterable="false"
            class="rounded-lg p-5 shadow-sm"
            :data="formattedStudentsData"
        >
            <template #table-header>
                <div v-if="showTitle" class="flex w-full justify-between pb-4">
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
                <TextInput
                    v-model="searchKey"
                    class="w-7/12"
                    placeholder="Search for a student by name"
                />
                <div
                    class="mt-3 flex w-full justify-between divide-x divide-gray-200 rounded-lg border bg-gray-50 p-3"
                >
                    <div class="w-4/12 text-center">
                        <div class="text-xl font-semibold text-gray-900">
                            {{ studentsCount }}
                        </div>
                        <div class="text-[0.65rem] font-medium text-gray-500">
                            Total Students
                        </div>
                    </div>
                    <div class="w-4/12 text-center">
                        <div
                            class="cursor-pointer text-xl font-semibold text-gray-900"
                            @click="showAbsentees = true"
                        >
                            {{ todayAbsentees.length }}
                        </div>
                        <div class="text-[0.65rem] font-medium text-gray-500">
                            Absentees Today
                        </div>
                    </div>
                    <div class="w-4/12 text-center">
                        <div
                            class="cursor-pointer text-xl font-semibold text-gray-900"
                            @click="showLatestPeriodAbsentees = true"
                        >
                            {{ latestPeriodAbsentees.length }}
                        </div>
                        <div class="text-[0.65rem] font-medium text-gray-500">
                            Latest Period Absentees
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
                Transferring a student will remove them from their current
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

    <Modal v-model:view="showAbsentees">
        <div class="lex flex-col space-y-3 rounded-lg bg-white p-4 text-center">
            <div>
                <Title title="Today's Absentees" />
            </div>

            <div
                v-if="todayAbsentees.length > 0"
                class="mx-auto mt-10 flex w-full flex-col space-y-4"
            >
                <div
                    v-for="(absentee, index) in todayAbsentees"
                    :key="index"
                    class="flex w-full flex-col justify-start rounded-lg py-2"
                    :class="index % 2 === 0 ? 'bg-gray-50' : ''"
                >
                    <div class="flex w-full px-4 text-sm font-medium">
                        <div class="">
                            {{ index + 1 }}.
                            {{ absentee.user.name }}
                        </div>
                        <div v-if="absentee?.reason">
                            ({{ absentee.reason }}),
                        </div>
                    </div>
                </div>
            </div>
            <EmptyView v-else title="No absentees today" />
        </div>
    </Modal>

    <Modal v-model:view="showLatestPeriodAbsentees">
        <div class="lex flex-col space-y-3 rounded-lg bg-white p-4 text-center">
            <div>
                <Title title="Latest Period Absentees" />
            </div>

            <div
                v-if="latestPeriodAbsentees.length > 0"
                class="mx-auto mt-10 flex w-full flex-col space-y-4"
            >
                <div
                    v-for="(absentee, index) in latestPeriodAbsentees"
                    :key="index"
                    class="flex w-full flex-col justify-start"
                >
                    <div class="flex w-full px-4 text-sm font-medium">
                        <div class="">
                            {{ index + 1 }}.
                            {{ absentee.user.name }}
                        </div>
                        <div v-if="absentee?.reason">
                            ({{ absentee.reason }}),
                        </div>
                    </div>
                </div>
            </div>
            <EmptyView v-else title="No absentees in the latest period" />
        </div>
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
import Title from "@/Views/Teacher/Views/Title.vue";
import EmptyView from "@/Views/EmptyView.vue";

const props = defineProps({
    url: {
        type: String,
        default: "/admin/students",
    },
    showTitle: {
        type: Boolean,
        default: true,
    },
});

const showAbsentees = ref(false);

const showLatestPeriodAbsentees = ref(false);

const isModalOpen = ref(false);

const students = computed(() => {
    return usePage().props.students;
});

const studentsCount = computed(() => {
    return usePage().props.students_count;
});

const todayAbsentees = computed(() => {
    return usePage().props.today_absentees;
});

const latestPeriodAbsentees = computed(() => {
    return usePage().props.latest_period_absentees;
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
            batch_id: student.current_batch[0]?.batch_id,
            level_id: student.current_batch[0]?.level.id,
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
                description: `Homeroom Teacher: ${batch.homeroom_teacher?.teacher.user.name}`,
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
        props.url,
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
        link: "/admin/teachers/students/{id}",
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
