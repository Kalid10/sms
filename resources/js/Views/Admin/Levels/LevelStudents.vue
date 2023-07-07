<template>
    <div class="flex w-full flex-col gap-3">
        <TableElement
            v-if="!!students.data"
            :title="
                (selectedSection &&
                    `Section ${selectedSection} Students List`) ||
                'Students List'
            "
            :subtitle="`Students enrolled in ${parseLevel(level.name)} for ${
                schoolYear.name
            }`"
            class="w-fit"
            :row-actionable="true"
            :selectable="false"
            :columns="studentsConfig"
            :data="students.data"
        >
            <template #date_of_birth-column="{ data }">
                {{ Math.abs(moment(data).diff(new Date(), "years")) }}
            </template>

            <template #filter>
                <div class="flex w-full gap-4">
                    <div class="grow">
                        <RadioGroup
                            v-model="selectedSection"
                            :options="sectionsRadioButtons"
                            name="sections"
                        />
                    </div>
                    <TextInput
                        v-model="searchKey"
                        class="w-full"
                        :placeholder="$t('levelStudent.searchKeyPlaceHolder')"
                    />
                </div>
            </template>

            <template #empty-data>
                <div class="flex flex-col items-center justify-center">
                    <ExclamationTriangleIcon
                        class="mb-2 h-6 w-6 text-negative-50"
                    />
                    <p class="text-sm font-semibold">
                        {{ $t('levelStudent.noDataFound') }}
                    </p>
                    <div v-if="searchKey.length">
                        <p
                            v-if="searchKey === null"
                            class="text-sm text-gray-500"
                        >
                            {{ $t('levelStudent.noStudentEnrolled') }}
                        </p>
                        <p v-else class="text-center text-sm text-gray-500">
                            {{ $t('levelStudent.yourSearchQuery') }} "<span
                                class="font-medium text-black"
                                >{{ searchKey }}</span
                            >" {{ $t('levelStudent.didNotMatch') }}
                            <span class="block">{{ $t('levelStudent.anyStudentName') }}</span>
                        </p>
                    </div>
                </div>
            </template>

            <template #row-actions="{ row }">
                <Link
                    :href="'/students/' + row['student_id']"
                    class="flex flex-col items-center gap-1"
                >
                    <EyeIcon
                        class="h-3 w-3 stroke-2 transition-transform duration-150 hover:scale-125"
                    />
                </Link>
                <Link
                    :href="'/users/' + row['student_id'] + '/edit'"
                    class="flex flex-col items-center gap-1"
                >
                    <ArrowPathIcon
                        class="h-3 w-3 stroke-2 transition-all duration-150 hover:scale-125 hover:stroke-blue-700"
                    />
                </Link>
                <Link
                    :href="'/users/' + row['student_id'] + '/delete'"
                    class="flex flex-col items-center gap-1"
                >
                    <ArchiveBoxXMarkIcon
                        class="h-3 w-3 stroke-2 transition-all duration-150 hover:scale-125 hover:stroke-red-700"
                    />
                </Link>
            </template>

            <template #footer>
                <Pagination
                    :preserve-state="true"
                    :links="students.links"
                    position="center"
                />
            </template>
        </TableElement>
    </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import RadioGroup from "@/Components/RadioGroup.vue";
import { parseLevel } from "@/utils.js";
import {
    ArchiveBoxXMarkIcon,
    ArrowPathIcon,
    ExclamationTriangleIcon,
    EyeIcon,
} from "@heroicons/vue/24/outline/index.js";
import moment from "moment";
import TextInput from "@/Components/TextInput.vue";
import Pagination from "@/Components/Pagination.vue";

const props = {
    pagination: {
        type: Object,
        default: () => null,
    },
};

function changePage(url) {
    router.get(url, {
        only: ["students"],
        preserveState: true,
        replace: true,
    });
}

const level = computed(() => usePage().props.level);
const students = computed(() => usePage().props.students || []);
const batches = computed(() => usePage().props.batches);
const schoolYear = computed(() => usePage().props.school_year);

const selectedSection = ref();
const sectionsRadioButtons = computed(() =>
    usePage().props.batches.map((batch) => {
        return {
            label: `Section ${batch.section}`,
            value: batch.section,
        };
    })
);

const searchKey = ref("");
const perPage = ref(10);
const perPageOptions = [
    { value: 10, label: "10" },
    { value: 25, label: "25" },
    { value: 50, label: "50" },
    { value: 100, label: "100" },
];

const search = () => {
    const currentPage = usePage().props.page || 1; // Add this line
    router.get(
        "/admin/levels/" + level.value.id,
        {
            search: searchKey.value,
            section: selectedSection.value,
            per_page: perPage.value,
            page: currentPage, // Add this line
        },
        {
            only: ["students"],
            preserveState: true,
            replace: true,
        }
    );
};

const page = computed(() => usePage().props.page || 1);
watch([selectedSection, searchKey, perPage, page], () => {
    search();
});

const studentsConfig = [
    {
        name: "",
        key: "name",
        class: "font-semibold",
        align: "right",
    },
    {
        name: "",
        key: "email",
        class: "text-gray-500 text-xs w-full",
        align: "left",
    },
    {
        name: "",
        key: "username",
        class: "text-gray-500 text-xs font-semibold",
    },
    {
        name: "",
        key: "gender",
        type: "enum",
        options: ["male", "female"],
    },
    {
        name: "Age",
        key: "date_of_birth",
        type: "custom",
    },
    {
        name: "Last updated",
        key: "updated_at",
        class: "text-gray-500 text-xs",
        align: "right",
    },
];

onMounted(() => {
    router.reload({
        only: ["students"],
    });
});
</script>

<style scoped></style>
