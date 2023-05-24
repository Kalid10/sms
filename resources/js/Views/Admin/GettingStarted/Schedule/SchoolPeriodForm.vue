<template>
    <div class="flex flex-col md:flex-row md:justify-evenly">
        <div class="w-full lg:w-3/12">
            <PeriodForm />
        </div>

        <div class="min-h-full w-0.5 bg-zinc-100"></div>
        <div class="w-full lg:w-7/12">
            <!--                        <ViewPeriod />-->
        </div>
    </div>
</template>
<script setup>
import { computed, onMounted, ref } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import PeriodForm from "@/Views/Admin/GettingStarted/SchoolPeriod/PeriodForm.vue";

const levelCategories = computed(() => usePage().props.level_categories || []);

onMounted(() => {
    router.reload({
        only: ["level_categories"],
        preserveState: true,
    });
});

const customTimeInputs = ref([
    { name: "", duration: null, before_period: null },
]);

const classScheduleFormData = useForm({
    school_periods: [
        {
            no_of_periods: null,
            minutes_per_period: null,
            start_time: null,
            level_category_ids: [],
            custom_periods: [{ name: "", duration: null, before_period: null }],
        },
    ],
});

const selectedLevelCategories = computed(() => {
    return classScheduleFormData.school_periods[0].level_category_ids.map(
        (id) => levelCategories.value.find((lc) => lc.id === id)?.name
    );
});

function addCustomTimeInput(index) {
    classScheduleFormData.school_periods[index].custom_periods.push({
        name: "",
        duration: null,
        before_period: null,
    });
}

function removeCustomPeriod(periodIndex, customIndex) {
    classScheduleFormData.school_periods[periodIndex].custom_periods.splice(
        customIndex,
        1
    );
}

function addClassSchedule() {
    classScheduleFormData.post("/school-periods/create", {
        onSuccess: () => {
            router.get("/getting-started/class-schedule");
        },
    });
}

function submitForm() {
    router.get("/getting-started/class-schedule");
}

const clear = () => {
    classScheduleFormData.reset();
    classScheduleFormData.start_time = null;
    customTimeInputs.value = [
        {
            name: "",
            duration: null,
            before_period: null,
        },
    ];
};

const weekdays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];

const weekdaysClassSchedule = computed(() => {
    return weekdays.map((weekday, index) => {
        let periods = [];
        let currentTime = new Date(
            `1970-01-01T${classScheduleFormData.school_periods[0].start_time}`
        );
        for (
            let i = 0;
            i < classScheduleFormData.school_periods[0].no_of_periods;
            i++
        ) {
            let endTime = new Date(
                currentTime.getTime() +
                    classScheduleFormData.school_periods[0].minutes_per_period *
                        60000
            );
            periods.push({
                start: currentTime.toTimeString().substring(0, 5),
                end: endTime.toTimeString().substring(0, 5),
            });
            currentTime = endTime;
        }
        return {
            name: weekday,
            periods,
        };
    });
});

const classScheduleWithCustomPeriods = computed(() => {
    let classSchedule = [...weekdaysClassSchedule.value];
    for (let day of classSchedule) {
        let periods = [];
        for (let i = 0; i < day.periods.length; i++) {
            // Get custom periods that should come before this period
            let customPeriods =
                classScheduleFormData.school_periods[0].custom_periods.filter(
                    (p) => p.before_period === i + 1
                );

            // Convert custom periods to period format and insert them before this period
            for (let cp of customPeriods) {
                let start =
                    periods.length > 0
                        ? periods[periods.length - 1].end
                        : day.periods[0].start;
                let end = addMinutes(start, cp.duration);
                periods.push({
                    start,
                    end,
                    name: cp.name,
                });
            }
            // Insert this period
            periods.push(day.periods[i]);
        }
        day.periods = periods;
    }
    return classSchedule;
});

// Utility function to add minutes to a time string
function addMinutes(time, minutes) {
    let [hours, mins] = time.split(":").map(Number);
    mins += minutes;
    hours += Math.floor(mins / 60);
    mins %= 60;
    return `${hours.toString().padStart(2, "0")}:${mins
        .toString()
        .padStart(2, "0")}`;
}

function removeLevelCategory(item) {
    let index =
        classScheduleFormData.school_periods[0].level_category_ids.indexOf(
            levelCategories.value.find((lc) => lc.name === item)?.id
        );
    classScheduleFormData.school_periods[0].level_category_ids.splice(index, 1);
}
</script>
