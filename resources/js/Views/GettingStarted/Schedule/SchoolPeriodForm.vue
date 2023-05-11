<template>
    <div class="flex flex-col md:flex-row">
        <div class="m-2 w-full p-3 shadow-2xl xl:w-1/3">

            <h2 class="text-base font-semibold leading-7 text-gray-900">Class Schedule</h2>
            <p class="mt-1 text-sm leading-6 text-gray-600">
                Below the title, there's a brief description of what the user can do on this page.
            </p>

            <div class="flex w-full">
                <div
                    v-for="(schoolPeriod, index) in classScheduleFormData.school_periods" :key="index"
                    class="py-3">

                    <div class="space-y-4">
                        <TimePicker
                            v-model="schoolPeriod.start_time"
                            subtext="Specify when the start time of the day. The rest of the periods will be scheduled based on this starting time and the duration of each period, excluding custom periods.
                                        For example: 02:45 AM start time means the start time of a school period of the day"
                            placeholder="0"
                            label="Starting time"
                            :required="true"/>
                        <TextInput
                            v-model="schoolPeriod.minutes_per_period"
                            placeholder="0" label="Time for single period (minutes)"
                            subtext="Define how long each class period lasts in minutes.
                                     This could vary based on your school's specific needs, this also excludes custom periods.
                                     For example: if your input is 45, that means each period has 45 minutes"
                            :show-sub-text="false"
                            :toggle-subtext="true"
                            :required="true"/>

                        <TextInput
                            v-model="schoolPeriod.no_of_periods"
                            placeholder="0"
                            label="Time for single period (minutes)"
                            subtext="Enter the number of periods you have in a typical school day.
                            This will be the number of class sessions that are held in a day.
                            For example: if your input is is 8, that means there each day has 8 periods a day"
                            :show-sub-text="false"
                            :toggle-subtext="true"
                            :required="true"/>

                        <div class="my-2">
                            <label class="pl-0.5 text-sm font-semibold text-gray-500"> Select level
                                categories:</label>

                            <div class="flex flex-row gap-6 rounded border p-3">

                                <div
                                    v-for="(levelCategory, index) in levelCategories"
                                    :key="index"
                                    class="flex items-center space-x-2"
                                >
                                    <input
                                        v-model="schoolPeriod.level_category_ids"
                                        type="checkbox"
                                        :value="levelCategory.id"
                                        class="rounded border-gray-300 text-black focus:ring-black"
                                    />
                                    <label class="text-sm">{{ levelCategory.name }}</label>

                                </div>
                            </div>
                            <InformationCircleIcon class="inline-block h-4 w-4 stroke-2"/>

                            <span class="text-xs text-gray-500">
                                    Choose the levels to which this schedule applies, you can select more than one level.
                                For example: if you select "ElementarySchool" and "HighSchool" the schedule will apply for the selected only.
                                <span>
                                  N.B: "Kindergarten": K.G 1 - K.G - 3, "ElementarySchool": Grade 1 - Grade 8, "HighSchool": Grade 9 - Grade 12.
                                </span>
                                </span>
                        </div>
                        <div class="bg- flex items-center space-x-2">
                            <span class="grow pl-0.5 text-sm font-semibold text-gray-500">Custom Period</span>
                            <div class="flex justify-center" @click="addCustomTimeInput(index)">
                                <a class=" cursor-pointer text-sm text-blue-600 hover:text-blue-800"> + Add
                                    new</a>
                            </div>
                        </div>
                        <div
                            v-for="(customTimeInput, customIndex) in schoolPeriod.custom_periods"
                            :key="customIndex"
                        >
                            <div
                                class="relative flex flex-col border p-3">
                                <TextInput
                                    v-model="customTimeInput.name" type="text" label="Name"
                                    placeholder="Breakfast"
                                    :required="true"/>
                                <div class="flex flex-row gap-2">
                                    <TextInput
                                        v-model="customTimeInput.duration" type="number"
                                        label="Duration (minutes)"
                                        placeholder="0"
                                        :required="true"/>
                                    <TextInput
                                        v-model="customTimeInput.before_period" type="number" min="1"
                                        :max="schoolPeriod.minutes_per_period" label="Period before"
                                        placeholder="0"
                                        :required="true"/>
                                </div>
                                <div class="absolute top-0 right-0 p-2">
                                    <TrashIcon
                                        class="h-4 w-4 stroke-red-500"
                                        @click="removeCustomPeriod(index, customIndex)"/>
                                </div>
                            </div>
                        </div>

                        <div>
                            <InformationCircleIcon class="inline-block h-4 w-4 stroke-2"/>

                            <span class="text-xs text-gray-500">
                            Here, you can add custom periods such as lunch break, recess, or any other non-class times that occur regularly. Specify the name, duration, and which period it should come before.
                                For example: if you want to add a "Breakfast" break, put "Breakfast" on the name field, put how long it takes in number(in minutes) on the duration field and on the period before
                                field put before which period you want the breakfast break, like 4: meaning before the 4th period.
                            </span>
                        </div>

                    </div>

                    <PrimaryButton class="mt-3" @click="addClassSchedule">Submit
                    </PrimaryButton>

                </div>
            </div>
        </div>

        <div class="m-2 w-full p-3 shadow-2xl xl:w-2/3">
            <div class="w-full">
                <div class="flex flex-row">
                    <h2 class="mx-5 text-base font-semibold leading-7 text-gray-900">Schedule real time display </h2>

                    <h3
v-if="classScheduleWithCustomPeriods[0].periods.length < 1"
                        class="font-weight-light mx-5 text-sm leading-7 text-gray-900">
                        No periods found, enter the number (amount) of periods to display in real time.
                    </h3>

                </div>

                <div class="my-2 flex w-full flex-wrap space-x-2">

                    <div
v-for="(item , index) in selectedLevelCategories"
                         :key="index"
                         class=" rounded-md px-2 py-1 text-xs ">
                        <div class="rounded-md p-2" :class="categoryColors[item]">
                            {{ item }}
                        </div>
                    </div>

                </div>
                <div class="grid grid-cols-5 gap-3">
                    <div v-for="(day, index) in classScheduleWithCustomPeriods" :key="index" class="border p-2">
                        <h2 class="font-semibold">{{ day.name }}</h2>
                        <div v-for="(period, periodIndex) in day.periods" :key="periodIndex" class="mt-2 border p-2">
                            <h3 class="text-sm">{{ period.start }} - {{ period.end }}</h3>
                            <p class="text-xs">{{ period.name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import TimePicker from "@/Components/TimePicker.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {InformationCircleIcon, TrashIcon} from "@heroicons/vue/24/outline";
import TextInput from "@/Components/TextInput.vue";
import {computed, onMounted, ref} from "vue";
import {router, useForm, usePage} from "@inertiajs/vue3";


const levelCategories = computed(() => usePage().props.level_categories || [])

onMounted(() => {
    router.reload({
        only: ['level_categories'],
        preserveState: true
    })
})

const customTimeInputs = ref([{name: '', duration: null, before_period: null}])

const classScheduleFormData = useForm({
    school_periods: [
        {
            no_of_periods: null,
            minutes_per_period: null,
            start_time: null,
            level_category_ids: [],
            custom_periods: [{name: "", duration: null, before_period: null}],
        }
    ],
})

const selectedLevelCategories = computed(() => {
    return classScheduleFormData.school_periods[0].level_category_ids
        .map(id => levelCategories.value.find(lc => lc.id === id)?.name);
});

function addCustomTimeInput(index) {
    classScheduleFormData.school_periods[index].custom_periods.push({
        name: "",
        duration: null,
        before_period: null,
    });
}

function removeCustomPeriod(periodIndex, customIndex) {
    classScheduleFormData.school_periods[periodIndex].custom_periods.splice(customIndex, 1);
}


function addClassSchedule() {
    classScheduleFormData.post('/school-periods/create', {
            onSuccess: () => {
                router.get("/getting-started/class-schedule");
            }
        }
    )
}

function submitForm() {
    router.get("/getting-started/class-schedule");
}

const clear = () => {
    classScheduleFormData.reset();
    classScheduleFormData.start_time = null
    customTimeInputs.value = [
        {
            name: '',
            duration: null,
            before_period: null
        }
    ]
}

const weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

const weekdaysClassSchedule = computed(() => {
    return weekdays.map((weekday, index) => {
        let periods = [];
        let currentTime = new Date(`1970-01-01T${classScheduleFormData.school_periods[0].start_time}`);
        for (let i = 0; i < classScheduleFormData.school_periods[0].no_of_periods; i++) {
            let endTime = new Date(currentTime.getTime() + classScheduleFormData.school_periods[0].minutes_per_period * 60000);
            periods.push({
                start: currentTime.toTimeString().substring(0, 5),
                end: endTime.toTimeString().substring(0, 5)
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
            let customPeriods = classScheduleFormData.school_periods[0].custom_periods.filter(p => p.before_period === i + 1);

            // Convert custom periods to period format and insert them before this period
            for (let cp of customPeriods) {
                let start = periods.length > 0 ? periods[periods.length - 1].end : day.periods[0].start;
                let end = addMinutes(start, cp.duration);
                periods.push({
                    start,
                    end,
                    name: cp.name
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
    let [hours, mins] = time.split(':').map(Number);
    mins += minutes;
    hours += Math.floor(mins / 60);
    mins %= 60;
    return `${hours.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}`;
}

const categoryColors = {
    'Kindergarten': 'bg-gradient-to-r from-blue-200 via-blue-400 to-blue-200',
    'ElementarySchool': 'bg-gradient-to-r from-red-200 via-red-400 to-red-200',
    'HighSchool': 'bg-gradient-to-r from-green-200 via-green-400 to-green-200'
}

</script>
