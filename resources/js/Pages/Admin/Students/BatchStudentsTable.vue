<template>
    <div class="my-2 flex w-full flex-row gap-4">
        <div class="flex w-full items-center">
            <label class="mx-2 flex w-32 items-center text-sm text-gray-800"
                >Select a grade:</label
            >
            <SelectInput
                v-model="selectedBatchId"
                :options="batchOptions"
                placeholder="Select a batch"
                class="w-4/12 cursor-pointer"
            />
        </div>
        <SecondaryButton
            :title="
                'Go To Grade ' + selectedBatchLevel + ' ' + selectedBatchLabel
            "
            class="!rounded-2xl bg-zinc-700 text-white"
            @click="levelDetailLink"
        />
    </div>

    <TableElement
        :columns="config"
        :selectable="false"
        :header="false"
        :filterable="false"
        :title="'Students in ' + selectedBatchLabel"
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
                <p class="text-sm font-semibold">No data found</p>
                <div v-if="searchKey.length">
                    <p v-if="searchKey === null" class="text-sm text-gray-500">
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
import Pagination from "@/Components/Pagination.vue";

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
        name: "Name",
        key: "name",
        link: "/admin/students/{id}",
        align: "left",
        type: "custom",
    },
    {
        name: "Username",
        key: "username",
        type: "custom",
    },
    {
        name: "Gender",
        key: "gender",
        type: "enum",
        options: ["female", "male"],
    },
];
</script>
