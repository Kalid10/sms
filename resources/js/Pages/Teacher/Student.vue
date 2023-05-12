<template>

    <div class="flex w-full flex-col space-y-3 px-5 py-3 lg:space-y-10 lg:px-12">

        <!-- Header-->
        <div
            class="flex items-start gap-3 rounded-lg py-5 text-xl font-light lg:py-7 lg:pl-0 lg:text-4xl"
        >
            <span class="ml-1 font-semibold lg:ml-2">
                {{ student.user.name }}
            </span>
        </div>

        <div class="grid w-full grid-cols-12">

            <div class="col-span-6 h-fit w-full rounded-lg">
                <div class="flex w-full justify-between">
                    <div class="font-medium lg:text-2xl">Recent Assessments for {{ student.user.name }}</div>
                    <div
                        class="flex w-fit items-center justify-center space-x-1 rounded-md px-3 text-xs font-medium underline underline-offset-2 hover:scale-105 hover:cursor-pointer lg:text-sm"
                    >
                        <div>SEE ALL</div>
                    </div>
                </div>

                <div v-for="(batchSubject, ass) in assessments" :key="ass" class="flex w-full flex-col">
                    <div
                        v-if="batchSubject.assessments.length > 0"
                        class="mt-1 flex w-full flex-col justify-center divide-y-2 py-2 lg:mt-2"
                    >
                        <div
                            v-for="(item, index) in batchSubject.assessments.slice(0, 4)"
                            :key="index"
                            class="mt-2 flex items-center justify-evenly py-2"
                        >
                            <div
                                class="flex w-2/12 flex-col items-center justify-center text-center"
                            >
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl"
                                >
                                    <component
                                        :is="
                                    getIconAndColor(item.assessment_type.name)
                                        .icon
                                "
                                        :class="
                                    getIconAndColor(item.assessment_type.name)
                                        .color
                                "
                                        class="w-5"
                                    />
                                </div>
                                <div class="mt-1.5 text-xs font-light uppercase">
                                    {{ batchSubject.batch.level.name
                                    }}{{ batchSubject.batch.section }}
                                </div>
                            </div>

                            <div
                                class="flex w-7/12 flex-col space-y-4 text-center lg:w-8/12 lg:text-start"
                            >
                                <div
                                    class="flex w-full flex-col justify-between space-x-4"
                                >
                                    <div class="text-xs font-medium lg:text-base">
                                        {{ item.title }}
                                    </div>
                                </div>
                                <div
                                    class="flex flex-col items-center space-y-0.5 text-[0.65rem] font-light lg:flex-row lg:space-x-1.5 lg:space-y-0 lg:text-start lg:text-sm"
                                >
                                    <div class="flex space-x-1">
                                        <div>
                                            {{ batchSubject.subject.full_name }}
                                        </div>
                                        <div class="font-medium">
                                            {{ item.assessment_type.name }}
                                        </div>
                                    </div>
                                    <div>
                                <span class="font-base hidden lg:inline-block"
                                >on,
                                </span>
                                        {{
                                            moment(item.due_date).format("dddd MMMM Do")
                                        }}
                                    </div>
                                </div>
                            </div>

                            <div
                                class="flex w-2/12 flex-col items-center space-y-2 lg:items-end"
                            >
                                <div
                                    class="flex flex-col font-light uppercase lg:flex-row"
                                >
                                    <div class="mr-2 text-2xl font-bold lg:text-3xl">
                                        {{ item.maximum_point }}
                                    </div>
                                    <div
                                        class="flex flex-col space-y-0.5 text-[0.6rem] font-light lg:text-xs lg:font-medium"
                                    >
                                        <div>MAX</div>
                                        <div>POINTS</div>
                                    </div>
                                </div>
                                <div
                                    class="hidden text-xs text-neutral-600 underline-offset-1 hover:cursor-pointer hover:text-black hover:underline lg:inline-block"
                                >
                                    LessonPlan #{{ item.id }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div
                class="col-span-5 col-start-8 flex h-full w-full flex-col items-center justify-evenly space-y-1 rounded-xl bg-black text-white"
            >

                <div
                    class="flex h-full w-full flex-col items-center justify-evenly space-y-4 py-3 lg:w-5/12 lg:space-y-5 lg:py-0"
                >
                    <div
                        class="w-1/2 text-center text-sm font-light opacity-60 lg:text-base"
                    >
                        Next Class
                    </div>
                    {{ upcomingSession }}
<!--                    <span class="text-4xl font-bold lg:text-7xl">-->
<!--                        {{ nextClass.batch_subject.batch.level.name }}{{ nextClass.batch_subject.batch.section }}-->
<!--                    </span>-->
<!--                    <span class="text-sm font-medium lg:text-xl">-->
<!--                        {{ nextClass.batch_subject.subject.full_name }}-->
<!--                    </span>-->
<!--                    <span class="text-sm lg:text-base">-->
<!--                        {{ nextClass.school_period.name }}th Period-->
<!--                        {{ moment(nextClass.date).fromNow() }}-->
<!--                    </span>-->

<!--                    <div-->
<!--                        class="text-sm font-light hover:cursor-pointer hover:font-medium hover:underline lg:text-base"-->
<!--                    >-->
<!--                    <span v-if="nextClass.lesson_plan">-->
<!--                        Lesson Plan #{{ nextClass.lesson_plan_id }}</span-->
<!--                    >-->
<!--                        <span v-else> Add LessonPlan</span>-->
<!--                    </div>-->

                    <PrimaryButton class="w-lg:w-11/12 bg-neutral-800"
                    >View Full Schedule
                    </PrimaryButton>
                </div>

            </div>

        </div>

    </div>

</template>

<script setup>
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import moment from "moment";
import {ExclamationTriangleIcon} from "@heroicons/vue/24/outline/index.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {
    BookOpenIcon,
    ClipboardDocumentCheckIcon,
    DocumentChartBarIcon,
    DocumentTextIcon, HomeIcon,
    PencilIcon
} from "@heroicons/vue/24/solid/index.js";

const student = computed(() => usePage().props.student)
const assessments = computed(() => usePage().props.assessments)
const batchSessions = computed(() => usePage().props.batchSessions)
const upcomingSession = computed(() => batchSessions.value[0])

function getIconAndColor(name) {
    switch (name) {
        case "Tests":
            return { icon: DocumentTextIcon, color: "fill-orange-500" };
        case "Homework":
            return { icon: ClipboardDocumentCheckIcon, color: "fill-red-500" };
        case "Classwork":
            return { icon: PencilIcon, color: "fill-cyan-600" };
        case "Final Quarterly Exam":
            return { icon: DocumentChartBarIcon, color: "fill-yellow-500" };
        case "Final Exam":
            return { icon: BookOpenIcon, color: "fill-teal-600" };
        default:
            return { icon: HomeIcon, color: "fill-emerald-600" };
    }
}
</script>

<style scoped>

</style>
