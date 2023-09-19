<template>
    <div class="my-5 h-full w-full lg:w-10/12">
        <TeacherTableElement
            :columns="config"
            :selectable="false"
            :data="formattedTeachersData"
            :filterable="false"
            class="rounded-lg p-5 shadow-sm"
        >
            <template #table-header>
                <div class="flex w-full items-center justify-between pb-5">
                    <div class="flex w-4/12">
                        <Title :title="$t('common.teachers')" />
                    </div>

                    <div
                        class="flex w-full flex-col justify-between space-y-2 lg:flex-row lg:space-y-0 lg:space-x-2"
                    >
                        <TextInput
                            v-model="searchKey"
                            class="w-full lg:w-6/12"
                            :placeholder="$t('teachersIndex.searchTeacher')"
                        />
                        <div
                            class="flex w-full flex-col justify-between space-y-2 lg:flex-row lg:space-y-0 lg:space-x-2"
                        >
                            <SelectInput
                                v-model="selectedSubject"
                                class="h-fit w-full rounded-2xl !text-sm"
                                :options="subjectOptions"
                                placeholder="Filter by subject"
                            />
                            <SelectInput
                                v-model="selectedLevel"
                                class="h-fit w-full rounded-2xl !text-sm"
                                :options="levelsOptions"
                                placeholder="Filter by Grade"
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
            <template #homerooms-column="{ data }">
                <div class="flex text-xs">
                    {{ data }}
                </div>
            </template>

            <template #label-column="{ data }">
                <div
                    class="flex cursor-pointer rounded text-xs"
                    @click="getSelectedTeacher(data)"
                >
                    <PlusCircleIcon
                        class="h-5 w-5 stroke-black hover:scale-125"
                    />
                </div>
            </template>

            <template #footer>
                <Pagination
                    preserve-state
                    :links="teachers.links"
                    position="center"
                />
            </template>

            <template #empty-data>
                <div class="flex flex-col items-center justify-center">
                    <ExclamationTriangleIcon
                        class="mb-2 h-6 w-6 text-negative-50"
                    />
                    <p class="text-sm font-semibold">
                        {{ $t("common.noDataFound") }}
                    </p>
                    <div>
                        <p
                            v-if="searchKey === null"
                            class="text-sm text-brand-text-300"
                        >
                            {{ $t("teachersIndex.noTeacher") }}
                        </p>
                        <p
                            v-else
                            class="text-center text-sm text-brand-text-300"
                        >
                            Your search query "<span
                                class="font-medium text-black"
                                >{{ searchKey }}</span
                            >" did not match
                            <span class="block">any teacher's name</span>
                        </p>
                    </div>
                </div>
            </template>

            <template #row-column="{ data }">
                <div class="flex w-full items-center justify-center">
                    <UserMinusIcon
                        :title="$t('teachersIndex.absentee')"
                        class="w-5 cursor-pointer text-black hover:scale-125"
                        @click="toggleDialogBox(data.teacher)"
                    />
                </div>
            </template>

            <template #leave_info-column="{ data }">
                <div
                    v-if="data.leave_info === null"
                    class="flex justify-between"
                >
                    <span> Leave is not set! </span>
                    <div class="cursor-pointer hover:scale-125">
                        <PlusIcon
                            class="h-4 w-4"
                            @click="selectLeaveInfo(data)"
                        />
                    </div>
                </div>
                <div v-else class="flex justify-between">
                    <span>
                        {{ data.leave_info.remaining }}
                        /
                        {{ data.leave_info.total }}
                    </span>

                    <div class="cursor-pointer hover:scale-125">
                        <PencilSquareIcon
                            class="h-3 w-3"
                            @click="selectLeaveInfo(data)"
                        />
                    </div>
                </div>
            </template>
        </TeacherTableElement>
    </div>

    <DialogBox
        v-if="isDialogBoxOpen"
        :title="$t('teachersIndex.absent')"
        :description="$t('teachersIndex.confirmIf')"
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
                :label="$t('teachersIndex.reason')"
                placeholder="Ex: Sick"
                :error="usePage().props.errors.reason"
            />

            <Toggle
                v-if="selectedTeacher.batch_sessions.length > 0"
                v-model="form.batch_session_only"
                :label="
                    'Is ' +
                    selectedTeacher.user.name +
                    ' absent for ' +
                    selectedTeacher?.batch_sessions[0]?.batch_schedule.batch
                        .level.name +
                    ' ' +
                    selectedTeacher?.batch_sessions[0]?.batch_schedule.batch
                        .section +
                    ' ' +
                    selectedTeacher?.batch_sessions[0]?.batch_schedule
                        ?.batch_subject.subject.full_name +
                    ' Class Only?'
                "
            />

            <Toggle v-model="form.is_leave" label="Is this a valid leave?" />
        </template>
    </DialogBox>

    <Modal v-model:view="showAssignModal">
        <AssignHomeroom
            :teacher-id="selectedTeacherId"
            :teacher-name="selectedTeacherName"
            @close="showAssignModal = false"
        />
    </Modal>

    <Modal v-model:view="showLeaveInfoModal">
        <FormElement title="Leave Info" @submit="submitLeaveInfo">
            <TextInput
                v-model="leaveInfoForm.leave_info.total"
                label="Total Leave"
                placeholder="Total Leave"
                type="number"
                required
            />
        </FormElement>
    </Modal>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import TeacherTableElement from "@/Components/TableElement.vue";
import {
    ExclamationTriangleIcon,
    MinusCircleIcon,
    PencilSquareIcon,
    PlusCircleIcon,
    PlusIcon,
    UserMinusIcon,
} from "@heroicons/vue/24/outline/index";
import TextInput from "@/Components/TextInput.vue";
import { debounce } from "lodash";
import Pagination from "@/Components/Pagination.vue";
import Title from "@/Views/Teacher/Views/Title.vue";
import DialogBox from "@/Components/DialogBox.vue";
import SelectInput from "@/Components/SelectInput.vue";

import { useI18n } from "vue-i18n";
import Toggle from "@/Components/Toggle.vue";
import AssignHomeroom from "@/Views/Teacher/Views/Homeroom/AssignHomeroom.vue";
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue";

const showAssignModal = ref(false);

const showLeaveInfoModal = ref(false);

const selectedTeacherForLeaveInfo = ref(null);

const { t } = useI18n();
const isDialogBoxOpen = ref(false);

const teachers = computed(() => {
    return usePage().props.teachers;
});

const selectedTeacherId = ref(null);

const selectedTeacherName = ref(null);

function getSelectedTeacher(data) {
    selectedTeacherId.value = data.id;
    selectedTeacherName.value = data.name;
    showAssignModal.value = true;
}

const subjects = computed(() => usePage().props.subjects);

const levels = computed(() => usePage().props.levels);

const selectedLevel = ref(null);
const selectedSubject = ref(null);
const selectedTeacher = ref(null);

const levelsOptions = computed(() => {
    return levels.value?.map((level) => {
        return {
            value: level.id,
            label: `Grade ${level.name}`,
        };
    });
});

const subjectOptions = computed(() => {
    return subjects.value?.map((subject) => {
        return {
            value: subject.id,
            label: subject.full_name,
        };
    });
});

watch(selectedLevel, () => {
    applyLevelFilter();
});

function applyLevelFilter() {
    router.get(
        "/admin/teachers/",
        {
            level_id: selectedLevel.value,
        },
        {
            preserveState: true,
        }
    );
}

watch(selectedSubject, () => {
    applySubjectFilter();
});

const leaveInfoForm = useForm({
    leave_info: {
        total: null,
    },
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

function toggleDialogBox(teacher) {
    isDialogBoxOpen.value = !isDialogBoxOpen.value;
    form.batch_session_id = teacher.batch_sessions.length
        ? teacher?.batch_sessions[0].id
        : null;
    form.user_id = teacher.user.id;
    selectedTeacher.value = teacher;
}

function selectLeaveInfo(data) {
    showLeaveInfoModal.value = true;
    selectedTeacherForLeaveInfo.value = data.teacher_id;
    leaveInfoForm.leave_info.total = data.leave_info?.total;
}

const formattedTeachersData = computed(() => {
    return teachers.value.data.map((teacher) => {
        // Use Set to get distinct values
        const subjectsSet = new Set();
        teacher.batch_subjects.forEach((bs) =>
            subjectsSet.add(`${bs.subject.full_name}(${bs.batch.level.name})`)
        );
        const subjects = Array.from(subjectsSet).join(", ");

        return {
            label: {
                id: teacher.id,
                name: teacher.user.name,
            },
            id: teacher.id,
            name: teacher.user.name,
            email: teacher.user.email,
            gender: teacher.user.gender,
            homerooms: teacher.homeroom
                .map((hr) => `${hr.batch.level.name}${hr.batch.section}`)
                .join(", "),
            subjects: subjects,
            leave_info: {
                id: teacher.user.id,
                teacher_id: teacher.id,
                leave_info: teacher.leave_info,
            },
            row: { teacher },
        };
    });
});

const searchKey = ref(usePage().props.filters.search_key);
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
    selectedLevel.value = null;
});

const form = useForm({
    batch_session_id: "",
    user_id: "",
    reason: "",
    type: "teacher",
    is_leave: false,
    batch_session_only: false,
});

const markTeacherAsAbsent = () => {
    router.post(
        "/admin/absentee/staff/add",
        {
            user_id: form.user_id,
            reason: form.reason,
            type: form.type,
            batch_session_id: form.batch_session_only
                ? form.batch_session_id
                : null,
            is_leave: form.is_leave,
        },
        {
            onSuccess: () => {
                isDialogBoxOpen.value = false;
                form.reset();
                if (form.is_leave) {
                    submitLeaveInfo();
                }
            },
        }
    );
};

const submitLeaveInfo = () => {
    router.post(
        "/teachers/leave-info/",
        {
            teacher_id: selectedTeacherForLeaveInfo.value,
            leave_info: leaveInfoForm.leave_info,
        },
        {
            onSuccess: () => {
                showLeaveInfoModal.value = false;
                leaveInfoForm.reset();
            },
        }
    );
};

const config = [
    {
        name: t("common.name"),
        key: "name",
        link: "/admin/teachers/{id}",
        align: "left",
        type: "custom",
    },
    {
        name: t("common.email"),
        key: "email",
        type: "custom",
        align: "left",
    },
    {
        name: t("common.gender"),
        key: "gender",
        type: "enum",
        options: ["female", "male"],
    },
    {
        name: t("common.subjects"),
        key: "subjects",
        align: "left",
        type: "custom",
    },
    {
        name: t("common.leave"),
        key: "leave_info",
        type: "custom",
    },
    {
        name: t("teachersIndex.absentee"),
        key: "row",
        type: "custom",
    },
    {
        name: t("common.homeroom"),
        key: "homerooms",
        align: "right",
        type: "custom",
        class: "justify-end pt-4 flex",
    },
    {
        name: "",
        key: "label",
        type: "custom",
        align: "left",
    },
];
</script>

<style scoped></style>
