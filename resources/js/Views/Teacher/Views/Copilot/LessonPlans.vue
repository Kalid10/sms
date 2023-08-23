<template>
    <div
        class="flex w-full flex-col items-center space-y-2 rounded-lg bg-white p-5"
    >
        <div class="w-fit px-2 text-center text-2xl capitalize">
            {{ $t("lessonPlans.yourLessonPlans") }}
        </div>
        <div
            class="flex w-full flex-col space-y-2 p-2 text-black lg:flex-row lg:space-y-0 lg:space-x-5"
        >
            <div v-if="subjectOptions?.length > 0" class="w-full lg:w-4/12">
                <SelectInput
                    v-model="selectedSubject"
                    :options="subjectOptions"
                />
            </div>
            <div v-if="monthOptions?.length > 0" class="w-full lg:w-4/12">
                <SelectInput v-model="selectedMonth" :options="monthOptions" />
            </div>
            <div class="w-full lg:w-4/12">
                <SelectInput v-model="selectedWeek" :options="weekOptions" />
            </div>
        </div>
        <div v-if="lessonPlansData" class="flex w-full flex-col space-y-1">
            <div
                v-if="selectedWeekPlans.length"
                class="flex w-full flex-col space-y-1 py-2"
            >
                <div
                    class="flex w-full flex-col space-x-1 lg:flex-row lg:flex-wrap lg:justify-between"
                >
                    <div
                        v-for="(item, index) in selectedWeekPlans"
                        :key="index"
                        :class="[
                            'my-3 flex w-full cursor-pointer flex-col items-center justify-evenly space-y-3 rounded-lg p-2 px-3 text-center text-xs shadow-sm lg:w-5/12',
                            isSelected(item?.lesson_plan?.id)
                                ? 'border border-zinc-700 bg-gradient-to-bl from-violet-500 to-purple-500 text-white'
                                : 'border border-zinc-700 bg-white from-violet-500 to-purple-500 hover:scale-105 hover:border-zinc-700 hover:bg-gradient-to-bl hover:text-white',
                        ]"
                        @click="handleClick(item)"
                    >
                        <span class="font-medium">
                            {{ item.lesson_plan?.topic }}
                        </span>

                        <span class="flex w-full justify-between px-2">
                            <EyeIcon
                                class="w-3 hover:scale-125"
                                @click="
                                    selectedLessonPlan = item;
                                    showModal = true;
                                "
                            />
                            <span class="text-[0.65rem] font-light">
                                {{ moment(item.date).fromNow() }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
            <div v-else class="w-full p-5">
                <EmptyView
                    class="capitalize"
                    :title="
                        'No Lesson Plans found in ' +
                        moment(selectedMonth).format('MMMM YYYY') +
                        ' - ' +
                        selectedWeek
                    "
                />
            </div>
        </div>
    </div>
    <Loading v-if="isLoading" is-full-screen />
    <Modal v-model:view="showModal">
        <div class="flex flex-col space-y-4 rounded-lg bg-white p-5">
            <div class="px-3 text-center text-xl font-medium">
                {{ selectedLessonPlan.lesson_plan.topic }}
            </div>

            <div class="text-xs leading-6 text-brand-text-350">
                {{ selectedLessonPlan.lesson_plan.description }}
            </div>

            <div class="flex w-full justify-end">
                <span class="text-xs font-light">
                    {{
                        moment(selectedLessonPlan.date).format(
                            "dddd, MMMM DD, YYYY"
                        )
                    }}
                </span>
            </div>
        </div>
    </Modal>
</template>
<script setup>
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import moment from "moment";
import SelectInput from "@/Components/SelectInput.vue";
import EmptyView from "@/Views/EmptyView.vue";
import Loading from "@/Components/Loading.vue";
import Modal from "@/Components/Modal.vue";
import { EyeIcon } from "@heroicons/vue/24/outline";

const emit = defineEmits(["select"]);

const isLoading = ref(false);
const lessonPlansData = computed(() => usePage().props.lesson_plans_data);

const selectedLessonPlan = ref();
const showModal = ref();
const selectedLessonPlans = ref([]);

const selectedSubject = ref();
const selectedWeek = ref();
const selectedMonth = ref();
const selectedBatchSubject = ref();

const subjectOptions = computed(() => {
    return lessonPlansData?.value?.subjects.map((item) => {
        return {
            label:
                item.batch.level.name +
                item.batch.section +
                " - " +
                item.subject.full_name,
            value: item.id,
        };
    });
});

const monthOptions = computed(() => {
    return lessonPlansData.value?.months;
});

const weekOptions = [
    { label: "Week-1", value: "week1" },
    { label: "Week-2", value: "week2" },
    { label: "Week-3", value: "week3" },
    { label: "Week-4", value: "week4" },
    { label: "Week-5", value: "week5" },
];

const selectedWeekPlans = computed(() => {
    let selectedWeekPlans =
        lessonPlansData.value?.batch_sessions?.[selectedWeek.value] || [];
    return selectedWeekPlans.filter((item) => item.lesson_plan_id);
});

watch(lessonPlansData, (value) => {
    if (value) {
        const weekFound = Object.entries(value.batch_sessions).find(
            ([key, val]) => val.length > 0
        );
        if (weekFound) {
            selectedWeek.value = weekFound[0];
        }

        selectedMonth.value = value.selected_month;
        selectedSubject.value = value.lesson_plan_subject.id;
    }
});

watch([selectedMonth, selectedSubject], (newValue, oldValue) => {
    if (oldValue !== undefined) handleMonthChange();
});

function handleMonthChange() {
    isLoading.value = true;
    router.get(
        "/teacher/copilot",
        {
            month: selectedMonth.value,
            batch_subject_id: selectedSubject.value,
        },
        {
            preserveState: true,
            onFinish: () => {
                isLoading.value = false;
            },
        }
    );
}

const handleClick = (item) => {
    // Check if the item.id is already in the array
    const index = selectedLessonPlans.value.findIndex(
        (id) => id === item.lesson_plan.id
    );

    // If the item is in the array, remove it
    if (index > -1) {
        selectedLessonPlans.value.splice(index, 1);
    } else {
        if (
            selectedBatchSubject.value &&
            selectedBatchSubject.value.subject.full_name !==
                item.batch_schedule.batch_subject.subject.full_name
        ) {
            alert("Error! Please select the same subject");
            return;
        }

        // Add a selected batch subject
        selectedBatchSubject.value = item.batch_schedule.batch_subject;

        // Otherwise, add the item to the array
        selectedLessonPlans.value.push(item.lesson_plan.id);
    }

    emit("select", selectedLessonPlans.value, selectedBatchSubject.value);
};

const isSelected = (itemId) => {
    return selectedLessonPlans.value.includes(itemId);
};
</script>
<style scoped></style>
