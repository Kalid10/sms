<template>
    <div
        class="flex min-h-screen w-full flex-col space-y-3 bg-gray-50"
        :class="isTeacher() ? '2xl:pl-4 2xl:pr-2 p-1' : ''"
    >
        <!--                 Next Class Header On Mobile Devices-->
        <div
            v-if="nextClass"
            class="flex h-16 w-full flex-col justify-center rounded-sm bg-black px-2 text-center text-xs font-light leading-5 text-white lg:hidden"
            @click="scrollToNextClass"
        >
            <div class="w-full">
                Your next class is
                <span class="font-semibold">
                    {{ nextClass.batch_subject.subject.full_name }}
                </span>
                with grade
                <span class="font-semibold">
                    {{ nextClass.batch_subject.batch.level.name }}
                    {{ nextClass.batch_subject.batch.section }}
                </span>
                during
                <span class="font-semibold">
                    period {{ nextClass.school_period.name }} , </span
                >approximately
                <span class="font-semibold"
                    >{{ moment(nextClass.date).fromNow() }}.
                </span>
            </div>
        </div>

        <div
            class="flex w-full flex-col justify-between p-2 lg:flex-row lg:p-8"
        >
            <div class="flex w-full flex-col space-y-8 lg:w-8/12">
                <WelcomeHeader v-if="isTeacher()" />
                <TabElement v-model:active="activeTab" :tabs="tabs">
                    <template #announcements>
                        <Announcements
                            url="/teacher/announcements"
                            view="teacher"
                        />
                    </template>
                    <template #school-schedules>
                        <SchoolSchedule class="!w-11/12" />
                    </template>
                    <template #todays-schedule>
                        <CurrentDaySchedule
                            ref="currentDayScheduleRef"
                            :schedule="teacherSchedule"
                            class-style="px-4 !h-fit py-2 space-y-2"
                        />
                    </template>
                </TabElement>
                <div class="flex w-full items-center justify-between space-x-6">
                    <div class="w-7/12"></div>
                </div>
                <Flags title="Recent Flags" view="teacher" />
            </div>
            <div
                class="flex h-full w-full flex-col items-center space-y-8 lg:w-3/12"
            >
                <NextClass
                    class="!w-11/12"
                    @view="activeTab = 'Todays-Schedule'"
                />
                <Summary class="!w-11/12" />

                <AttendanceCard class="!w-11/12" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import NextClass from "@/Views/Teacher/Views/NextClass/Index.vue";
import moment from "moment/moment";
import { computed, ref } from "vue";
import { isTeacher } from "@/utils";
import WelcomeHeader from "@/Views/WelcomeHeader.vue";
import CurrentDaySchedule from "@/Views/CurrentDaySchedule.vue";
import Announcements from "@/Views/Announcements/Index.vue";
import AttendanceCard from "@/Views/Teacher/Views/Home/AttendanceCard.vue";
import Flags from "@/Views/Flag/Index.vue";
import TabElement from "@/Components/TabElement.vue";
import SchoolSchedule from "@/Views/Teacher/Views/Home/SchoolSchedule.vue";
import Summary from "@/Views/Teacher/Views/Home/Summary.vue";

const teacher = usePage().props.teacher;
const filters = computed(() => usePage().props.filters);
const nextClass = usePage().props.teacher.next_batch_session;
const nextClassSection = ref(null);
const teacherSchedule = computed(() => usePage().props.teacher_schedule);
const scrollToNextClass = () => {
    nextClassSection.value.$el.scrollIntoView({ behavior: "smooth" });
};
const currentDayScheduleRef = ref(null);
const tabs = ["Announcements", "School-Schedules", "Todays-Schedule"];
const activeTab = ref("Announcements");
</script>

<style scoped></style>
