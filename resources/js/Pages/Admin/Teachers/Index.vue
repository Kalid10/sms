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
                    <div v-if="searchKey.length">
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

            <template #session-column="{ data }">
                <SecondaryButton
                    v-if="data.batch_session_id"
                    :title="$t('teachersIndex.absentee')"
                    class="!rounded-2xl bg-brand-400 text-white"
                    @click="toggleDialogBox(data.id, data.batch_session_id)"
                />
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

import { useI18n } from "vue-i18n";

const { t } = useI18n();
const isDialogBoxOpen = ref(false);

const teachers = computed(() => {
    return usePage().props.teachers;
});

const subjects = computed(() => usePage().props.subjects);

const levels = computed(() => usePage().props.levels);

const selectedLevel = ref(null);
const selectedSubject = ref(null);

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
    selectedLevel.value = null;
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
        name: t("common.homeroom"),
        key: "homerooms",
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
        name: t("teachersIndex.absentee"),
        key: "session",
        type: "custom",
    },
];
</script>

<style scoped></style>
