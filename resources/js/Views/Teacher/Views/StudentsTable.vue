<template>
    <div class="flex w-full justify-between">
        <!--        Table-->
        <TableElement
            :data="filteredStudents"
            :title="$t('common.students')"
            :selectable="false"
            :columns="config"
            class="!!text-[0.5rem] border-none bg-red-400"
            :footer="true"
            header-style="!bg-brand-400 text-white !text-[0.65rem]"
        >
            <template #filter>
                <div class="flex h-full w-full justify-between text-center">
                    <TextInput
                        v-model="searchText"
                        :placeholder="$t('studentsTable.search')"
                        class="w-6/12"
                    />
                    <div v-if="!homeroomTeacher && isAdmin()">
                        <SecondaryButton
                            :title="$t('studentsTable.assignHomeroom')"
                            class="!rounded-2xl bg-brand-450 text-white"
                            @click="showAssignModal = true"
                        />
                    </div>
                    <div v-if="homeroomTeacher" class="text-xs font-semibold">
                        <div class="mb-1 text-[0.55rem] font-light">
                            {{ $t("studentsTable.homeroomTeacher") }}
                        </div>
                        <div
                            class="cursor-pointer text-xs font-semibold underline-offset-2 hover:underline"
                        >
                            {{ homeroomTeacher.user.name }}
                        </div>
                    </div>
                    <AdjustmentsHorizontalIcon
                        v-if="showFilter"
                        class="w-4 cursor-pointer text-brand-400 hover:scale-125 hover:text-brand-450"
                        @click="$emit('filter')"
                    />
                </div>
            </template>
            <template #name-column="{ data }">
                <span
                    class="cursor-pointer text-xs underline underline-offset-2 hover:font-semibold"
                    @click="$emit('click', data.id)"
                >
                    {{ data.name }}
                </span>
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
    <Modal v-model:view="showAssignModal">
        <AssignHomeroom />
    </Modal>
</template>
<script setup>
import Pagination from "@/Components/Pagination.vue";
import TextInput from "@/Components/TextInput.vue";
import TableElement from "@/Components/TableElement.vue";
import { computed, ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import debounce from "lodash/debounce";
import Modal from "@/Components/Modal.vue";
import AssignHomeroom from "@/Views/Teacher/Views/Homeroom/AssignHomeroom.vue";
import { isAdmin } from "@/utils";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { AdjustmentsHorizontalIcon } from "@heroicons/vue/20/solid";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const emit = defineEmits(["click", "search", "filter"]);
const props = defineProps({
    showHomeroomDetail: {
        type: Boolean,
        default: true,
    },
    showFilter: {
        type: Boolean,
        default: true,
    },
});

const teacher = usePage().props.teacher;

const batches = computed(() => usePage().props.batches);

const showAssignModal = ref(false);

const searchText = ref(usePage().props.filters?.search);
const students = computed(() => {
    return usePage().props.students;
});
const homeroomTeacher = computed(() => {
    return usePage().props.homeroom_teacher;
});

const filteredStudents = computed(() => {
    return students.value.data.map((item) => {
        return {
            name: {
                name: item.student.user.name,
                id: item.student.id,
            },
            flags: item.student.flags?.length,
            attendance: item.attendance_percentage + "%",
            grade: item.quarterly_grade?.score
                ? `${item.quarterly_grade.score.toFixed(1)} (${
                      item.quarterly_grade.grade_scale?.label || ""
                  })`
                : "-",

            rank: item.rank ?? " -",
            id: item.student.id,
            conduct: item.conduct ?? "-",
        };
    });
});

const config = [
    {
        key: "name",
        name: t("studentsTable.name"),
        align: "center",
        class: "h-12 !text-[0.7rem]",
        type: "custom",
    },
    {
        key: "flags",
        name: "Flags",
        align: "center",
        class: "h-12 !text-[0.7rem]",
    },
    {
        key: "attendance",
        name: t("studentsTable.attendance"),
        align: "center",
        class: "h-12 !text-[0.7rem]",
    },
    {
        key: "grade",
        name: t("studentsTable.grade"),
        align: "center",
        class: "h-12 !text-[0.7rem]",
    },
    {
        key: "rank",
        name: t("studentsTable.rank"),
        align: "center",
        class: "h-12 !text-[0.7rem]",
    },
    {
        key: "conduct",
        name: t("studentsTable.conduct"),
        align: "center",
        class: "h-12 !text-[0.7rem]",
    },
];

function emitSearch() {
    emit("search", null, searchText.value);
}

const debouncedUpdate = debounce(emitSearch, 300);

watch(searchText, () => {
    debouncedUpdate();
});
</script>

<style scoped></style>
