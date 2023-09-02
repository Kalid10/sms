<template>
    <div class="my-2 flex w-full flex-col gap-4 lg:flex-row">
        <div class="flex w-full items-center">
            <label
                class="mx-2 flex w-32 items-center text-sm text-brand-text-50"
                >{{ $t("batchStudentTable.selectGrade") }}</label
            >
            <SelectInput
                v-model="selectedBatchId"
                :options="batchOptions"
                :placeholder="$t('batchStudentTable.selectBatch')"
                class="w-full cursor-pointer lg:w-4/12"
            />
        </div>
        <SecondaryButton
            :title="
                'Go To Grade ' + selectedBatchLevel + ' ' + selectedBatchLabel
            "
            class="!rounded-2xl bg-brand-400 text-white"
            @click="levelDetailLink"
        />
    </div>

    <TableElement
        :columns="config"
        :selectable="false"
        :header="false"
        :filterable="false"
        :title="$t('batchStudentTable.studentsIn') + selectedBatchLevel"
        :data="batchStudentData"
    >
        <template #name-column="{ data }">
            <div class="flex items-start gap-2">
                <span class="font-medium">{{ data }}</span>
            </div>
        </template>

        <template #username-column="{ data }">
            <div class="flex items-center gap-1">
                <div class="h-1.5 w-1.5 rotate-45 bg-emerald-400" />
                <span class="text-xs">
                    {{ data }}
                </span>
            </div>
        </template>

        <template #footer>
            <Pagination
                :links="batchStudents.links"
                preserve-state
                position="center"
            />
        </template>

        <template #empty-data>
            <div class="flex flex-col items-center justify-center">
                <ExclamationTriangleIcon
                    class="mb-2 h-6 w-6 text-negative-50"
                />
                <p class="text-sm font-semibold">
                    {{ $t("batchStudentTable.noDataFound") }}
                </p>
                <div v-if="searchKey.length">
                    <p
                        v-if="searchKey === null"
                        class="text-sm text-brand-text-300"
                    >
                        {{ $t("batchStudentTable.noTeacherEnrolled") }}
                    </p>
                    <p v-else class="text-center text-sm text-brand-text-300">
                        <span
                            v-html="
                                $t('batchStudentTable.yourSearchQuery', {
                                    searchKey,
                                })
                            "
                        >
                        </span>
                    </p>
                </div>
            </div>
        </template>
    </TableElement>
</template>
<script setup>
import TableElement from "@/Components/TableElement.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { debounce } from "lodash";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useI18n } from "vue-i18n";
import Pagination from "@/Components/Pagination.vue";

const { t } = useI18n();

const batchStudents = computed(() => {
    return usePage().props.batch_students;
});

// Map the batch_student data
const batchStudentData = computed(() => {
    return batchStudents.value.data.map((batch_student) => {
        return {
            id: batch_student.student.id,
            name: batch_student.student.user.name,
            username: batch_student.student.user.username,
            gender: batch_student.student.user.gender,
            grade: batch_student.level.name,
            section: batch_student.batch.section,
        };
    });
});

const batchOptions = computed(() => {
    return usePage().props.batches.map((batch) => {
        return {
            label: batch.level.name + " " + batch.section,
            value: batch.id,
        };
    });
});

const selectedBatchLabel = computed(() => {
    return (
        usePage().props.selected_batch.level.name +
        " " +
        usePage().props.selected_batch.section
    );
});

const selectedBatchLevel = computed(() => {
    return usePage().props.selected_batch.level.name;
});

const selectedBatchId = ref(usePage().props.selected_batch.id);

function levelDetailLink() {
    router.get("/admin/batches/" + selectedBatchId.value);
}

const searchKey = ref("");

watch(selectedBatchId, () => {
    router.get(
        "/admin/students",
        { batch_id: selectedBatchId.value },
        { preserveState: true, replace: true }
    );
});

const search = debounce(() => {
    router.get(
        "/admin/students/",
        { search: searchKey.value },
        {
            only: ["students"],
            preserveState: true,
            replace: true,
        }
    );
}, 300);

watch([searchKey], () => {
    search();
});
const config = [
    {
        name: t("common.name"),
        key: "name",
        link: "/admin/teachers/students/{id}",
        align: "left",
        type: "custom",
    },
    {
        name: "Username",
        key: "username",
        type: "custom",
    },
    {
        name: t("common.gender"),
        key: "gender",
        type: "enum",
        options: [t("common.female"), t("common.male")],
    },
];
</script>
