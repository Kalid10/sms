<template>

    <div class="flex grid-cols-5 flex-col gap-3 lg:grid">
        <div
            v-for="(day, index) in days"
            :key="index"
            class="flex flex-col"
        >
            <h3 class="mb-2 w-full text-center text-xl">{{ day.name }}</h3>
            <div
                v-for="(item, index) in sortedAndFormattedTeacherPeriods"
                :key="index"
            >
                <Card
                    v-show="item && item.day_of_week && item.day_of_week === day.key"
                    class="mb-2 min-w-full"
                    :subtitle="'Period ' + item.school_period.name"
                    :title="item.batch_subject.batch.level.name + item.batch_subject.batch.section"
                >
                    <div class="font-medium">
                        {{ item.formattedStartTime }} - {{ item.formattedEndTime }}
                    </div>
                    <div>{{ item.batch_subject.subject.full_name }}</div>
                </Card>
            </div>
        </div>
    </div>
</template>

<script setup>
import Card from "@/Components/Card.vue";
import {usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import moment from "moment/moment";
import {useI18n} from "vue-i18n";
const {t} = useI18n()

const teacher = usePage().props.teacher;
const schedule = usePage().props.batchSchedules;
const days = [
    {name: t('common.days[1]'), key: 'monday'},
    {name: t('common.days[2]'), key: 'tuesday'},
    {name:t('common.days[3]'), key: 'wednesday'},
    {name:t('common.days[4]'), key: 'thursday'},
    {name: t('common.days[5]'), key: 'friday'},
];


const sortedAndFormattedTeacherPeriods = computed(() => {
    const daysOfWeek = [t('common.days[0]'), t('common.days[1]'), t('common.days[2]'), t('common.days[3]'), t('common.days[4]'), t('common.days[5]'), t('common.days[6]')];

    return teacher.batch_schedules.slice()
        .sort((a, b) => {
            const dayComparison = daysOfWeek.indexOf(a.day_of_week) - daysOfWeek.indexOf(b.day_of_week);
            return dayComparison === 0 ? parseInt(a.school_period.name) - parseInt(b.school_period.name) : dayComparison;
        })
        .map((item) => {
            const startTime = moment(item.school_period.start_time, 'HH:mm:ss');
            const endTime = startTime.clone().add(item.school_period.duration, 'minutes');
            return {
                ...item,
                formattedStartTime: startTime.format('h:mm A'),
                formattedEndTime: endTime.format('h:mm A'),
            };
        });
});
</script>

<style scoped>

</style>
