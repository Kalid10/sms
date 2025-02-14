<template>
    <h1 class="my-4 text-lg font-semibold text-brand-text-500">Management</h1>
    <div class="my-4 flex flex-col justify-evenly gap-2 md:flex-row">
        <Card
            class="group max-w-2xl cursor-pointer hover:border-black md:col-span-1 md:row-span-1 md:w-full"
            title="Schedule"
            icon
            @click="schedulesLink"
        >
            <h3 class="text-sm text-brand-text-300">
                Here, you can review the comprehensive plan of classes and
                educational events we have meticulously curated. Take a look,
                get acquainted with the timetable, and witness the seamless
                orchestration of our learning journey.
            </h3>

            <template #icon>
                <CalendarDaysIcon />
            </template>
        </Card>

        <Card
            class="group max-w-2xl hover:border-black md:col-span-1 md:row-span-1 md:w-full"
        >
            <div class="flex flex-col items-center justify-between gap-2">
                <div class="flex w-full items-center justify-center">
                    <h3 class="text-lg font-semibold">Admission</h3>
                    <div
                        class="flex max-w-0 grow justify-end opacity-0 transition-all duration-150 ease-in group-hover:max-w-full group-hover:opacity-100"
                    >
                        <button
                            v-if="hasRole('manage-students')"
                            class="m-2 scale-100 rounded-lg bg-brand-500 px-4 text-sm text-white transition-colors duration-150"
                            @click="studentRegistrationLink"
                        >
                            Student
                        </button>

                        <button
                            v-if="hasRole('manage-admins')"
                            class="m-2 scale-100 rounded-lg bg-brand-500 px-4 text-sm text-white transition-colors duration-150"
                            @click="adminRegistrationLink"
                        >
                            Admin
                        </button>
                        <button
                            v-if="hasRole('manage-teachers')"
                            class="m-2 scale-100 rounded-lg bg-brand-500 px-4 text-sm text-white transition-colors duration-150"
                            @click="teacherRegistrationLink"
                        >
                            Teacher
                        </button>
                    </div>
                </div>

                <h3 class="text-sm text-brand-text-300">
                    Welcome to the core of our Admissions and Registration
                    Administration panel. As an administrator, your pivotal role
                    encompasses the registration of new users, ensuring the
                    smooth flow of our educational journey.
                </h3>
            </div>
        </Card>

        <Card
            class="group max-w-2xl cursor-pointer hover:border-black md:col-span-1 md:row-span-1 md:w-full"
            title="SMS"
            icon
            @click="smsLink"
        >
            <h3 class="text-sm text-brand-text-300">
                With a single click, you can instantly broadcast crucial message
                to any select teacher, parent, or any stakeholder actively
                involved in the educational process.
            </h3>
            <template #icon>
                <ChatBubbleBottomCenterTextIcon />
            </template>
        </Card>
    </div>
</template>
<script setup>
import Card from "@/Components/Card.vue";
import { router } from "@inertiajs/vue3";
import { computed } from "vue";
import {
    CalendarDaysIcon,
    ChatBubbleBottomCenterTextIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    userRoles: {
        type: Array,
        required: true,
    },
});

function schedulesLink() {
    router.get("admin/schedules");
}

const hasRole = computed(() => {
    return (role) => {
        for (let i = 0; i < props.userRoles.length; i++) {
            if (props.userRoles[i].name === role) {
                return true;
            }
        }
        return false;
    };
});

function studentRegistrationLink() {
    router.get("register/student");
}

function teacherRegistrationLink() {
    router.get("register/teacher");
}

function adminRegistrationLink() {
    router.get("register/admin");
}

function smsLink() {
    router.get("/sms");
}
</script>
