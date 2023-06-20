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
            class="flex h-screen w-full justify-between space-x-10"
            :class="isTeacher() ? 'px-2 py-3' : ''"
        >
            <div class="flex w-8/12 flex-col space-y-10">
                <WelcomeHeader v-if="isTeacher()" />

                <div class="flex w-full justify-between">
                    <div class="w-7/12">
                        <Announcements
                            url="/teacher/announcements"
                            view="teacher"
                        />
                    </div>
                    <div
                        class="flex w-5/12 flex-col justify-evenly space-y-4 pl-10"
                    >
                        <SummaryItem
                            class-style="bg-orange-100 text-black"
                            icon-style="bg-orange-500/20 text-white"
                            :title="'Assessments'"
                            value="10 /10 Completed"
                            :icon="ClipboardIcon"
                            :url="
                                isTeacher()
                                    ? '/teacher/assessments'
                                    : '/admin/teachers/assessments?teacher_id=' +
                                      teacher.id
                            "
                        />
                        <SummaryItem
                            class-style="bg-zinc-100 text-black"
                            icon-style="bg-zinc-500/20 text-white"
                            :title="'Students'"
                            value="75 Total Students"
                            :icon="UsersIcon"
                            :url="
                                isTeacher()
                                    ? '/teacher/students'
                                    : '/admin/teachers/students?teacher_id=' +
                                      teacher.id
                            "
                        />
                        <SummaryItem
                            class-style="bg-fuchsia-100 text-black"
                            icon-style="bg-fuchsia-500/20 text-white"
                            :title="'LessonPlans'"
                            value="10 /10 Completed"
                            :icon="CalendarIcon"
                            :url="
                                isTeacher()
                                    ? '/teacher/lesson-plan'
                                    : '/admin/teachers/lesson-plan?teacher_id=' +
                                      teacher.id
                            "
                        />
                        <SummaryItem
                            class-style="bg-green-100 text-black"
                            icon-style="bg-green-500/20 text-white"
                            :title="'Homeroom Classes'"
                            value="10 /10 Completed"
                            :icon="CalendarIcon"
                            :url="
                                isTeacher()
                                    ? '/teacher/homeroom'
                                    : '/admin/teachers/homeroom?teacher_id=' +
                                      teacher.id
                            "
                        />
                    </div>
                </div>
                <CurrentDaySchedule
                    v-if="teacherSchedule?.length"
                    :schedule="teacherSchedule"
                    class-style="px-4 !h-fit py-2 space-y-2"
                />

                <div
                    class="flex w-full items-center justify-between space-x-6"
                ></div>
            </div>
            <div
                class="flex h-full w-4/12 flex-col items-center space-y-8 p-0 px-3"
            >
                <div class="w-full rounded-lg bg-white p-3 shadow-sm">
                    <div class="flex w-full justify-between px-2">
                        <div class="py-2 text-center text-xl font-medium">
                            Upcoming Schedules
                        </div>
                        <LinkCell
                            class="flex w-fit items-center justify-center"
                            value="VIEW ALL"
                            :href="
                                isAdmin()
                                    ? '/admin/schedules'
                                    : '/teacher/school-schedule'
                            "
                        />
                    </div>
                    <div class="flex w-full flex-col justify-center">
                        <div
                            v-for="(item, index) in schoolSchedule"
                            :key="index"
                            class="rounded-lg px-1"
                            :class="index % 2 === 0 ? 'bg-gray-50/50 ' : ''"
                        >
                            <SchoolScheduleItem
                                class="!py-2"
                                :school-schedule="item"
                                view="teacher"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex w-full items-center justify-between">
                    <div class="flex h-full w-4/12 flex-col justify-between">
                        <div
                            class="flex h-2/5 w-full flex-col items-center justify-center space-y-3 rounded-lg bg-green-300 text-center text-sm shadow-sm"
                        >
                            <div class="text-center text-5xl font-bold">
                                100%
                            </div>

                            <div class="font-medium">Attendance</div>
                        </div>
                        <div
                            class="flex h-2/5 w-full flex-col items-center justify-center space-y-3 rounded-lg bg-green-300 text-center text-sm shadow-sm"
                        >
                            <div class="text-center text-5xl font-bold">
                                100%
                            </div>

                            <div class="font-medium">Attendance</div>
                        </div>
                    </div>
                    <div class="w-7/12">
                        <NextClass />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import NextClass from "@/Views/Teacher/Views/NextClass/Index.vue";
import moment from "moment/moment";
import { computed, ref } from "vue";
import { isAdmin, isTeacher } from "@/utils";
import WelcomeHeader from "@/Views/WelcomeHeader.vue";
import CurrentDaySchedule from "@/Views/CurrentDaySchedule.vue";
import Announcements from "@/Views/Announcements/Index.vue";
import {
    CalendarIcon,
    ClipboardIcon,
    UsersIcon,
} from "@heroicons/vue/24/solid";
import SummaryItem from "@/Views/Teacher/Views/SummaryItem.vue";
import SchoolScheduleItem from "@/Views/Admin/Schedule/SchoolScheduleItem.vue";
import LinkCell from "@/Components/LinkCell.vue";

const teacher = usePage().props.teacher;
const filters = computed(() => usePage().props.filters);
const nextClass = usePage().props.teacher.next_batch_session;
const nextClassSection = ref(null);
const teacherSchedule = computed(() => usePage().props.teacher_schedule);
const schoolSchedule = computed(() => usePage().props.school_schedule);
const scrollToNextClass = () => {
    nextClassSection.value.$el.scrollIntoView({ behavior: "smooth" });
};
</script>

<style scoped></style>
