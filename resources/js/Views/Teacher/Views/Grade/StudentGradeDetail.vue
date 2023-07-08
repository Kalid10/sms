<template>
    <div class="flex flex-col space-y-3 rounded-lg bg-white p-4 text-center">
        <div class="flex w-full items-center justify-between">
            <div class="flex grow justify-between py-3 text-2xl font-medium">
                <ArrowLeftCircleIcon
                    v-if="showSubjectDetail"
                    class="w-6 cursor-pointer"
                    @click="showSubjectDetail = false"
                />
                <span class="grow">
                    {{ studentName.user.name }}'s

                    <span v-if="showSubjectDetail">
                        {{
                            selectedBatchSubjectDetail.batch_subject.subject
                                .full_name
                        }}
                    </span>
 {{ $t('studentGradeDetail.gradeReport')}}
                </span>
            </div>
            <SecondaryButton
                class="h-fit !rounded-2xl bg-red-600 !px-4 !py-1 !text-xs text-white"
                :title="$t('studentGradeDetail.flagStudent')"
                @click="emit('flag')"
            />
        </div>

        <GradeFilter />

        <Statistics v-if="!showSubjectDetail" />

        <TableElement
            v-if="!showSubjectDetail"
            :data="studentGrades"
            title=""
            :selectable="false"
            :columns="config"
            class="!!text-[0.5rem] border-none bg-red-400"
            :footer="true"
            :filterable="false"
            header-style="!bg-zinc-700 text-white !text-[0.65rem]"
        >
            <template #subject-column="{ data }">
                <span
                    class="cursor-pointer text-xs underline underline-offset-2 hover:font-semibold"
                    @click="
                        selectedBatchSubject = data.id;
                        showSubjectDetail = true;
                    "
                >
                    {{ data.name }}
                </span>
            </template>
        </TableElement>

        <div v-else>
            <Statistics :grade="selectedBatchSubjectDetail" />
            <AssessmentBreakDown
                :assessment-grade="
                    selectedBatchSubjectDetail.batch_subject.assessments_grades
                "
            />
        </div>
    </div>
</template>
<script setup>
import { ArrowLeftCircleIcon } from "@heroicons/vue/20/solid";
import TableElement from "@/Components/TableElement.vue";
import AssessmentBreakDown from "@/Views/Teacher/Views/Assessments/AssessmentBreakDown.vue";
import Statistics from "@/Views/Teacher/Views/Batches/BatchPerformance/Index.vue";
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import GradeFilter from "@/Views/Teacher/Views/Grade/GradeFilter.vue";

const emit = defineEmits(["flag"]);
import {useI18n} from "vue-i18n";
const {t} = useI18n()
const props = defineProps({
    studentName: {
        type: Object,
        required: true,
    },
});

const studentDetails = computed(() => usePage().props.student.grades);
const selectedBatchSubject = ref(null);
const showSubjectDetail = ref(false);
const selectedBatchSubjectDetail = computed(() => {
    if (selectedBatchSubject.value === null) return null;
    return studentDetails.value.find((item) => {
        return item.batch_subject.id === selectedBatchSubject.value;
    });
});

const studentGrades = computed(() => {
    return studentDetails.value.map((item) => {
        return {
            subject: {
                name: item.batch_subject.subject.full_name,
                id: item.batch_subject.id,
            },
            attendance: item.attendance ? item.attendance + "%" : "-",
            grade: item?.score?.toFixed(1) ?? "-",
            rank: item.rank ?? " -",
            conduct: item.conduct ?? "-",
        };
    });
});

const config = [
    {
        key: "subject",
        name: t('common.subjects'),
        align: "center",
        class: "h-12 !text-[0.6rem]",
        type: "custom",
    },
    {
        key: "attendance",
        name: t('studentGradeDetail.attendance'),
        align: "center",
        class: "h-12 !text-[0.65rem]",
    },
    {
        key: "grade",
        name: t('common.grade'),
        align: "center",
        class: "h-12 !text-[0.65rem]",
    },
    {
        key: "rank",
        name: t('studentGradeDetail.rank'),
        align: "center",
        class: "h-12 !text-[0.65rem]",
    },
    {
        key: "conduct",
        name: t('studentGradeDetail.conduct'),
        align: "center",
        class: "h-12 !text-[0.65rem]",
    },
];
</script>

<style scoped></style>
