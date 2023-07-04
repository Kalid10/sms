<template>
    <TabElement v-model:active="activeTab" :tabs="tabs">
        <template #subjects>
            <TableElement
                :columns="config"
                :row-actionable="true"
                :actionable="true"
                :selectable="false"
                :data="formattedSubjects"
            >
                <template #filter>
                    <div class="flex justify-between gap-2">
                        <TextInput
                            v-model="query"
                            class="w-full lg:max-w-lg"
                            placeholder="Search for a subject by name or tags"
                        />
                        <PrimaryButton @click="$emit('new')">
                            <span class="flex gap-2">
                                <PlusIcon
                                    class="h-4 w-4 stroke-white stroke-2"
                                />
                                <span>New Subject</span>
                            </span>
                        </PrimaryButton>
                    </div>
                </template>

                <template #footer>
                    <Pagination :links="subjects.links" position="center" />
                </template>

                <template #empty-data>
                    <div class="flex flex-col items-center justify-center">
                        <ExclamationTriangleIcon
                            class="mb-2 h-6 w-6 text-negative-50"
                        />
                        <p class="mb-0.5 text-sm font-semibold">
                            No data found
                        </p>
                        <p v-if="query === null" class="text-sm text-gray-500">
                            No subject has been enrolled in this section
                        </p>
                        <p v-else class="text-center text-sm text-gray-500">
                            Your search query "<span
                                class="font-medium text-black"
                                >{{ query }}</span
                            >" did not match
                            <span class="block"
                                >any subject, category or tag</span
                            >
                        </p>
                    </div>
                </template>

                <template #full_name-column="{ data }">
                    <Link
                        :href="`/admin/subjects/${data['id']}`"
                        class="text-xs font-semibold hover:underline hover:underline-offset-2"
                    >
                        {{ data["full_name"] }}
                    </Link>
                </template>
                <template #short_name-column="{ data }">
                    <span class="text-xs text-gray-500">{{ data }}</span>
                </template>
                <template #tags-column="{ data }">
                    <div class="flex w-full items-center justify-center gap-2">
                        <span
                            v-for="(tag, t) in data"
                            :key="t"
                            class="text-xs"
                            >{{ toHashTag(tag) }}</span
                        >
                    </div>
                </template>
                <template #priority-column="{ data }">
                    <div
                        :class="priorityLabel[data - 1]"
                        class="mx-auto h-2.5 w-2.5 rounded-full"
                    />
                </template>
                <template #teacher-column="{ data }">
                    <div class="flex justify-end gap-2 text-xs">
                        <span>{{ data["name"] }}</span>
                        <span class="text-gray-500">{{ data["email"] }}</span>
                    </div>
                </template>
                <template #updated_at-column="{ data }">
                    <span class="text-xs text-gray-500">
                        {{ moment(data).fromNow() }}
                    </span>
                </template>
                <template #deleted_at-column="{ data }">
                    <BooleanCell :value="!!!data" />
                </template>
                <template #row-actions="{ row }">
                    <template v-if="!!!row['deleted_at']">
                        <button
                            class="flex flex-col items-center gap-1"
                            @click="$emit('update', row)"
                        >
                            <PencilIcon
                                class="h-2.5 w-2.5 stroke-2 transition-all duration-150 hover:scale-125 hover:stroke-blue-700"
                            />
                        </button>
                        <button
                            class="flex flex-col items-center gap-1"
                            @click="$emit('archive', row['id'])"
                        >
                            <ArchiveBoxXMarkIcon
                                class="h-3 w-3 stroke-2 transition-all duration-150 hover:scale-125 hover:stroke-red-700"
                            />
                        </button>
                    </template>
                    <template v-else>
                        <Link
                            :href="`subjects/restore/${row['id']}`"
                            class="flex flex-col items-center gap-1"
                        >
                            <ArrowPathIcon
                                class="h-2.5 w-2.5 stroke-2 transition-all duration-150 hover:scale-125 hover:stroke-blue-700"
                            />
                        </Link>
                        <button class="flex flex-col items-center gap-1">
                            <TrashIcon
                                class="h-2.5 w-2.5 stroke-2 transition-all duration-150 hover:scale-125 hover:stroke-blue-700"
                            />
                        </button>
                    </template>
                </template>
            </TableElement>
        </template>

        <template #grades>
            <div class="my-2 flex flex-row">
                <label class="mx-2 flex w-32 items-center text-sm text-gray-800"
                    >Select a grade:</label
                >
                <SelectInput
                    v-model="selectedBatchId"
                    :options="batchOptions"
                    placeholder="Select a batch"
                    class="max-h-28 w-4/12 cursor-pointer"
                />
            </div>
            <TableElement
                :row-actionable="false"
                :actionable="false"
                :selectable="false"
                :filterable="false"
                :footer="false"
                :columns="batch_subject_config"
                :data="batchSubjects"
            >
                <template #full_name-column="{ data }">
                    <div
                        class="font-semibold hover:underline hover:underline-offset-2"
                    >
                        {{ data }}
                    </div>
                </template>
                <template #short_name-column="{ data }">
                    <span class="text-xs text-gray-500">{{ data }}</span>
                </template>
                <template #tags-column="{ data }">
                    <div class="flex w-full items-center justify-center gap-2">
                        <span
                            v-for="(tag, t) in data"
                            :key="t"
                            class="text-xs"
                            >{{ toHashTag(tag) }}</span
                        >
                    </div>
                </template>
                <template #updated_at-column="{ data }">
                    <span class="text-xs text-gray-500">
                        {{ moment(data).fromNow() }}
                    </span>
                </template>
            </TableElement>
        </template>
    </TabElement>
</template>

<script setup>
import { toHashTag } from "@/utils.js";
import moment from "moment";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {
    ArchiveBoxXMarkIcon,
    ArrowPathIcon,
    ExclamationTriangleIcon,
    PencilIcon,
    PlusIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline/index.js";
import { Link, router, usePage } from "@inertiajs/vue3";
import BooleanCell from "@/Components/BooleanCell.vue";
import { computed, ref, watch } from "vue";
import { debounce } from "lodash";
import TextInput from "@/Components/TextInput.vue";
import TableElement from "@/Components/TableElement.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TabElement from "@/Components/TabElement.vue";
import Pagination from "@/Components/Pagination.vue";

const tabs = ["Subjects", "Grades"];
const activeTab = ref("Subjects");

// Map the batch_subjects data
const batchSubjects = computed(() => {
    return usePage().props.batch_subjects.map((batch_subject) => {
        return {
            full_name: batch_subject.subject.full_name,
            short_name: batch_subject.subject.short_name,
            tags: batch_subject.subject.tags,
            category: batch_subject.subject.category,
            updated_at: batch_subject.subject.updated_at,
        };
    });
});

// Map batch data
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
const selectedBatchId = ref(usePage().props.selected_batch.id);

watch(selectedBatchId, () => {
    router.get(
        "/admin/subjects",
        { batch_id: selectedBatchId.value },
        { preserveState: true, replace: true }
    );
});

const schoolName = computed(() => import.meta.env.SCHOOL_LONG_NAME);

const emits = defineEmits(["new", "update", "archive"]);

const subjects = ref(usePage().props.subjects);

const formattedSubjects = computed(() => {
    return subjects.value.data.map((subject) => {
        return {
            ...subject,
            full_name: {
                full_name: subject.full_name,
                id: subject.id,
            },
        };
    });
});

// const formattedSubjects = computed(() => {
//     return subjects.value.map((subject) => {
//         return {
//             id: subject.id,
//             priority: subject.priority,
//             full_name: subject.full_name,
//             short_name: subject.short_name,
//             tags: subject.tags,
//             updated_at: subject.updated_at,
//             archived_at: subject.archived_at,
//         };
//     });
// });

const query = ref(null);
const search = debounce(() => {
    router.get(
        "/admin/subjects",
        { search: query.value },
        { preserveState: true, replace: true }
    );
}, 300);

watch(query, () => {
    search();
});

const config = [
    {
        key: "priority",
        type: "custom",
        class: "w-fit",
    },
    {
        key: "full_name",
        name: "Subjects",
        type: "custom",
        align: "right",
    },
    {
        key: "short_name",
        type: "custom",
        align: "left",
    },
    {
        key: "tags",
        type: "custom",
        class: "w-full",
    },
    {
        key: "updated_at",
        name: "Last Updated",
        type: "custom",
        align: "right",
    },
    {
        key: "deleted_at",
        name: "Active",
        type: "custom",
    },
];

const batch_subject_config = [
    {
        key: "full_name",
        name: "Subjects",
        type: "custom",
        align: "left",
    },
    {
        key: "short_name",
        type: "custom",
        align: "left",
    },
    {
        key: "tags",
        type: "custom",
        class: "w-full",
    },
    {
        key: "updated_at",
        name: "Last Updated",
        type: "custom",
        align: "right",
    },
];

const priorityLabel = [
    "bg-red-100",
    "bg-red-300",
    "bg-red-500",
    "bg-red-700",
    "bg-red-900",
];
</script>

<style scoped></style>
