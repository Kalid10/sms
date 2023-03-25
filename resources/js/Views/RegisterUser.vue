<template>
    <RegisterModal v-model:view="isOpen">
        <div class="grid w-full space-y-6 overflow-hidden rounded-lg bg-white px-10">
            <h1 class="flex justify-center pt-4 text-3xl font-bold">Add User</h1>

            <RegisterCard
                v-show="!anyRegistrationActive"
                v-if="hasRole('manage-students')"
                icon
                title="Student"
                subtitle="Register students to efficiently manage their academic progress, track achievements, and
                facilitate communication between students, teachers, and guardians."
                class="min-w-full max-w-full cursor-pointer transition duration-500 ease-in-out hover:-translate-y-1 hover:scale-110"
                @click="showRegisterStudent">
                <template #icon>
                    <AcademicCapIcon class="h-6 w-6"/>
                </template>
            </RegisterCard>

            <RegisterCard
                v-show="!anyRegistrationActive"
                v-if="hasRole('manage-teachers')"
                icon
                title="Teacher"
                subtitle="Enroll teachers to empower them with educational tools, monitor performance, and foster collaboration
                among educators, enhancing the learning environment."
                class="min-w-full max-w-full cursor-pointer transition duration-500 ease-in-out hover:-translate-y-1 hover:scale-110"
                @click="showRegisterTeacher">
                <template #icon>
                    <BookOpenIcon class="h-6 w-6"/>
                </template>
            </RegisterCard>

            <RegisterCard
                v-show="!anyRegistrationActive"
                v-if="hasRole('manage-guardians')"
                icon
                title="Guardian"
                subtitle="Add guardians to involve them in their child's education, keep them informed about school events,
                and strengthen the relationship between school and families."
                class="min-w-full max-w-full cursor-pointer transition duration-500 ease-in-out hover:-translate-y-1 hover:scale-110"
                @click="showRegisterGuardian">
                <template #icon>
                    <UserCircleIcon class="h-6 w-6"/>
                </template>
            </RegisterCard>

            <div class="w-full">
                <component :is="currentComponent" v-if="currentComponent" @close="resetRegisterState()"></component>
            </div>
        </div>
    </RegisterModal>
</template>

<script setup>
import {computed, ref} from 'vue'
import RegisterModal from "@/Components/Modal.vue";
import RegisterCard from "@/Components/Card.vue";
import {AcademicCapIcon, BookOpenIcon, UserCircleIcon} from "@heroicons/vue/24/outline";
import Student from "@/Pages/Users/Create/Student.vue";
import Teacher from "@/Pages/Users/Create/Teacher.vue";
import Guardian from "@/Pages/Users/Create/Guardian.vue";

const props = defineProps({
    toggle: {
        type: Boolean,
        required: true,
    },
    userRoles: {
        type: Object,
        default: null
    },
})

const hasRole = computed(() => {
    return (role) => {
        for (let i = 0; i < props
            .userRoles.length; i++) {
            if (props.userRoles[i].name === role) {
                return true;
            }
        }
        return false;
    };
});

const isOpen = ref(props.toggle)
const registerStudent = ref(false);
const registerTeacher = ref(false);
const registerGuardian = ref(false);

const currentComponent = computed(() => {
    if (registerStudent.value) return Student;
    if (registerTeacher.value) return Teacher;
    if (registerGuardian.value) return Guardian;
    return null;
});

function resetRegisterState() {
    registerStudent.value = false;
    registerTeacher.value = false;
    registerGuardian.value = false;
}

function showRegisterStudent() {
    resetRegisterState();
    registerStudent.value = true;
}

function showRegisterTeacher() {
    resetRegisterState();
    registerTeacher.value = true;
}

function showRegisterGuardian() {
    resetRegisterState();
    registerGuardian.value = true;
}

function goBack() {
    resetRegisterState();
}

const anyRegistrationActive = computed(() => {
    return registerStudent.value || registerTeacher.value || registerGuardian.value;
});

</script>

<style>
/* Add your custom Tailwind CSS styles here */
</style>
