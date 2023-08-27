<template>
    <div class="flex flex-col space-y-3 rounded-lg bg-white p-4 text-center">
        <div class="flex w-full items-center justify-between">
            <div class="flex grow justify-between py-3 text-2xl font-medium">
                <PrinterIcon
                    class="w-5 cursor-pointer hover:scale-110"
                    @click="print()"
                />

                <div id="grade-report" ref="printContainer" class="hidden">
                    <div
                        class="flex h-[297mm] w-[210mm] flex-col justify-between overflow-hidden bg-white"
                    >
                        <div>
                            <!-- header  -->
                            <div
                                class="header flex h-[20mm] items-center justify-center bg-gray-600"
                            >
                                <span
                                    class="header p-2 text-2xl font-extrabold uppercase text-white"
                                >
                                    Report card</span
                                >
                                <p
                                    class="header text-sm font-extrabold uppercase text-white"
                                >
                                    ({{ schoolName }})
                                </p>
                            </div>

                            <!-- student info  -->
                            <div class="flex h-[40] justify-between p-8">
                                <div class="font-medium">
                                    <p class="text-sm">
                                        Name:
                                        <span
                                            class="pl-2 text-sm font-normal underline"
                                        >
                                            {{ studentName.user.name }}</span
                                        >
                                    </p>
                                    <p class="text-sm">
                                        Level:
                                        <span
                                            class="pl-2 text-sm font-normal underline"
                                            >Grade {{ level.id }}</span
                                        >
                                    </p>
                                    <p class="text-sm">
                                        Homeroom Teacher:
                                        <span
                                            class="pl-2 text-sm font-normal underline"
                                            >{{ homeroomTeacher.name }}</span
                                        >
                                    </p>
                                    <p></p>
                                </div>

                                <div class="text-sm">
                                    <p>
                                        <span class="font-normal">{{
                                            activeYear.name
                                        }}</span>
                                    </p>
                                    <p>
                                        <span class="font-normal"
                                            >{{ activeSemester.name }}
                                        </span>
                                    </p>
                                    <p>
                                        Issued Date:
                                        <span class="font-normal">{{
                                            formattedDate
                                        }}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- grading system -->
                            <div class="px-8">
                                <!--                                todo:Check if the class is primary or secondary and change description-->
                                <p class="text-sm font-medium">
                                    Grading System:
                                    <span class="font-normal">
                                        Percentage grades: 90-100 = A, 80-89 =
                                        B, 70-79 = C, 60-69 = D, 59 and below =
                                        F
                                    </span>
                                </p>
                            </div>

                            <!-- table -->
                            <div class="flex justify-center px-8">
                                <table
                                    class="mt-8 min-w-full overflow-hidden bg-white shadow-md"
                                >
                                    <thead>
                                        <tr
                                            class="bg-gray-200 text-xs uppercase text-gray-600"
                                        >
                                            <th class="py-3 px-6 text-left">
                                                Subject Name
                                            </th>
                                            <th class="py-3 px-6 text-left">
                                                Grade
                                            </th>
                                            <th class="py-3 px-6 text-left">
                                                Conduct
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="text-xs font-light text-gray-600"
                                    >
                                        <tr
                                            v-for="(
                                                grade, index
                                            ) in studentGrades"
                                            :key="index"
                                            class="border-b border-gray-200 hover:bg-gray-100"
                                        >
                                            <td class="py-3 px-6 text-left">
                                                {{ grade.subject.name }}
                                            </td>
                                            <td class="py-3 px-6 text-left">
                                                {{ grade.grade }}
                                            </td>
                                            <td class="py-3 px-6 text-left">
                                                {{ grade.conduct }}
                                            </td>
                                        </tr>

                                        <!-- Add more rows as needed -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- note -->
                        </div>
                        <Statistics v-if="!showSubjectDetail" />

                        <!-- footer  -->
                        <div class="shrink-0 px-3 text-xs">
                            <div class="flex justify-between">
                                <div class="pl-3">
                                    <p>
                                        Email:
                                        <span>contact.fks@gmail.com</span>
                                    </p>
                                    <p>
                                        Phone: <span>+251-116-454919/20</span>
                                    </p>
                                    <p>P.O.Box: <span>257 CODE 1110</span></p>
                                    <p>
                                        Address:
                                        <span
                                            >CMC, in Bole Sub city, Woreda
                                            8.</span
                                        >
                                    </p>
                                </div>
                                <div>
                                    <img class="h-20 w-20" src="./fksQR.png" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--                test print end -->
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
                    {{ $t("studentGradeDetail.gradeReport") }}
                </span>
            </div>

            <FlagIcon
                class="w-4 cursor-pointer text-red-500 hover:scale-125 hover:text-red-600"
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
            header-style="!bg-brand-400 text-white !text-[0.65rem]"
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
import {
    ArrowLeftCircleIcon,
    FlagIcon,
    PrinterIcon,
} from "@heroicons/vue/20/solid";
import TableElement from "@/Components/TableElement.vue";
import AssessmentBreakDown from "@/Views/Teacher/Views/Assessments/AssessmentBreakDown.vue";
import Statistics from "@/Views/Teacher/Views/Batches/BatchPerformance/Index.vue";
import { computed, onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import GradeFilter from "@/Views/Teacher/Views/Grade/GradeFilter.vue";
import { useI18n } from "vue-i18n";

const emit = defineEmits(["flag"]);

const { t } = useI18n();
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
    return studentDetails.value?.map((item) => {
        return {
            subject: {
                name: item.batch_subject.subject.full_name,
                id: item.batch_subject.id,
            },
            attendance: item.attendance ? item.attendance + "%" : "-",
            grade: item.score
                ? item?.score?.toFixed(1) +
                  "/" +
                  item?.total_score +
                  " (" +
                  item?.grade_scale?.label +
                  ")"
                : "-",
            rank: item.rank ?? " -",
            conduct: item.conduct ?? "-",
        };
    });
});

const config = [
    {
        key: "subject",
        name: t("common.subjects"),
        align: "center",
        class: "h-12 !text-[0.6rem]",
        type: "custom",
    },
    {
        key: "attendance",
        name: t("studentGradeDetail.attendance"),
        align: "center",
        class: "h-12 !text-[0.65rem]",
    },
    {
        key: "grade",
        name: t("common.grade"),
        align: "center",
        class: "h-12 !text-[0.65rem]",
    },
    {
        key: "rank",
        name: t("studentGradeDetail.rank"),
        align: "center",
        class: "h-12 !text-[0.65rem]",
    },
    {
        key: "conduct",
        name: t("studentGradeDetail.conduct"),
        align: "center",
        class: "h-12 !text-[0.65rem]",
    },
];

// test print start
const homeroomTeacher = computed(() => usePage().props.homeroom_teacher);
const level = computed(() => usePage().props.level);
const activeYear = computed(() => usePage().props.filters.school_year);
const activeSemester = computed(() => usePage().props.filters.semester);
const schoolName = ref("Fountain of Knowledge School");
const tagline = ref("Running for Excellence");
const printing = ref(false);

const today = new Date();
const formattedDate = today.toLocaleDateString("en-US", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
});

const printContainerRef = ref(null);

onMounted(() => {
    // Assign the template ref to the ref
    printContainerRef.value = document.querySelector("#grade-report");
});

function print() {
    printing.value = true;

    const printContainer = printContainerRef.value;

    if (printContainer) {
        // Store the original content
        const originalContent = document.body.innerHTML;

        // Replace the body content with the printContainer content
        document.body.innerHTML = printContainer.innerHTML;

        // Wait for the content to be rendered before printing
        setTimeout(() => {
            window.print();
            // Restore the original content
            document.body.innerHTML = originalContent;

            printing.value = false;
            refreshPage();
        }, 100);
    }
}

function refreshPage() {
    location.reload();
}
</script>

<style scoped>
@media print {
    .header {
        color: #5a5a5a;
    }
}
</style>
