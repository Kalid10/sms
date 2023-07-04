<template>
    <div class="my-5 h-full w-10/12">
        <TeacherTableElement
            :columns="config"
            :selectable="false"
            :data="formattedTeachersData"
            :filterable="false"
        >
            <template #table-header>
                <div class="flex w-full items-center justify-between pb-5">
                    <div class="flex w-full">
                        <Title title="Teachers" />
                    </div>

                    <div class="flex w-full justify-between space-x-2">
                        <TextInput
                            v-model="searchKey"
                            class="w-full"
                            placeholder="Search for a teacher by name"
                        />
                        <div class="flex w-full justify-between space-x-2">
                            <SelectInput
                                v-model="selectedSubject"
                                class="h-fit w-full rounded-2xl !text-sm"
                                :options="subjectOptions"
                                placeholder="Filter by subject"
                            />
                            <SelectInput
                                v-model="selectedBatch"
                                class="h-fit w-full rounded-2xl !text-sm"
                                :options="batchOptions"
                                placeholder="Filter by batch"
                            />
                        </div>
                    </div>
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

            <template #subjects-column="{ data }">
                <div class="flex text-xs">
                    {{ data }}
                </div>
            </template>

            <template #footer>
                <Pagination :links="teachers.links" position="center" />
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
                            No teacher has been enrolled
                        </p>
                        <p v-else class="text-center text-sm text-gray-500">
                            Your search query "<span
                                class="font-medium text-black"
                                >{{ searchKey }}</span
                            >" did not match
                            <span class="block">any teacher's name</span>
                        </p>
                    </div>
                </div>
            </template>

            <template #session-column="{ data }">
                <SecondaryButton
                    v-if="data.batch_session_id"
                    title="Absentee"
                    class="!rounded-2xl bg-zinc-700 text-white"
                    @click="toggleDialogBox(data.id, data.batch_session_id)"
                />
            </template>
        </TeacherTableElement>
    </div>

    <DialogBox
        v-if="isDialogBoxOpen"
        title="Absent"
        description="confirm if you want to mark this teacher as absent"
        open
        accent="gray"
        @abort="isDialogBoxOpen = false"
        @confirm="markTeacherAsAbsent"
    >
        <template #action> Absent</template>
        <template #icon>
            <MinusCircleIcon />
        </template>
        <template #content>
            <TextInput
                v-model="form.reason"
                label="Reason"
                placeholder="ex: sick"
            />
        </template>
    </DialogBox>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import TeacherTableElement from "@/Components/TableElement.vue";
import {
    ExclamationTriangleIcon,
    MinusCircleIcon,
} from "@heroicons/vue/24/outline/index";
import TextInput from "@/Components/TextInput.vue";
import { debounce } from "lodash";
import Pagination from "@/Components/Pagination.vue";
import Title from "@/Views/Teacher/Views/Title.vue";
import DialogBox from "@/Components/DialogBox.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";

const isDialogBoxOpen = ref(false);

const teachers = computed(() => {
    return usePage().props.teachers;
});

const subjects = computed(() => usePage().props.subjects);

const batches = computed(() => usePage().props.batches);

const selectedSubject = ref(null);
const selectedBatch = ref(null);

const subjectOptions = computed(() => {
    return subjects.value?.map((subject) => {
        return {
            value: subject.id,
            label: subject.full_name,
        };
    });
});

const batchOptions = computed(() => {
    return batches.value?.map((batch) => {
        return {
            value: batch.id,
            label: `Grade ${batch.level.name}`,
        };
    });
});

watch(selectedSubject, () => {
    applySubjectFilter();
});

function applySubjectFilter() {
    router.get(
        "/admin/teachers/",
        {
            subject_id: selectedSubject.value,
        },
        {
            preserveState: true,
        }
    );
}

watch(selectedBatch, () => {
    applyBatchFilter();
});

function applyBatchFilter() {
    router.get(
        "/admin/teachers/",
        {
            batch_id: selectedBatch.value,
        },
        {
            preserveState: true,
        }
    );
}

const selectedBatchSessionId = ref(null);

function toggleDialogBox(id, batch_session_id) {
    isDialogBoxOpen.value = !isDialogBoxOpen.value;
    form.batch_session_id = batch_session_id;
    form.user_id = id;
}

const selectedTeacherUserId = ref(null);

const formattedTeachersData = computed(() => {
    return teachers.value.data.map((teacher) => {
        // Use Set to get distinct values
        const subjectsSet = new Set();
        teacher.batch_subjects.forEach((bs) =>
            subjectsSet.add(`${bs.subject.full_name}(${bs.batch.level.name})`)
        );
        const subjects = Array.from(subjectsSet).join(", ");

        return {
            id: teacher.id,
            name: teacher.user.name,
            email: teacher.user.email,
            gender: teacher.user.gender,
            homerooms: teacher.homeroom
                .map((hr) => `${hr.batch.level.name}${hr.batch.section}`)
                .join(", "),
            subjects: subjects,
            session: {
                id: teacher.user.id,
                batch_session_id: teacher.batch_sessions.length
                    ? teacher.batch_sessions[0].id
                    : null,
            },
        };
    });
});

const searchKey = ref("");
const perPage = ref(15);

const search = debounce(() => {
    router.get(
        "/admin/teachers/",
        {
            search: searchKey.value,
            perPage: perPage.value,
        },
        {
            only: ["teachers"],
            preserveState: true,
            replace: true,
        }
    );
}, 300);

watch([searchKey, perPage], () => {
    search();
    selectedSubject.value = null;
    selectedBatch.value = null;
});

const form = useForm({
    batch_session_id: "",
    user_id: "",
    reason: "",
    type: "teacher",
});

const markTeacherAsAbsent = () => {
    form.post("/absentee/staff/add", {
        onSuccess: () => {
            isDialogBoxOpen.value = false;
            form.reset();
        },
    });
};

const config = [
    {
        name: "Name",
        key: "name",
        link: "/admin/teachers/{id}",
        align: "left",
        type: "custom",
    },
    {
        name: "Email",
        key: "email",
        type: "custom",
        align: "left",
    },
    {
        name: "Homerooms",
        key: "homerooms",
    },
    {
        name: "Gender",
        key: "gender",
        type: "enum",
        options: ["female", "male"],
    },
    {
        name: "Subjects",
        key: "subjects",
        align: "left",
        type: "custom",
    },
    {
        name: "Absentee",
        key: "session",
        type: "custom",
    },
];
</script>

<style scoped></style>
