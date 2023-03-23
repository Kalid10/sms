<template>
    <RegisterModal v-model:view="isOpen">
        <div class="mt-10 flex min-h-screen flex-col items-center">

            <div class="flex w-full flex-col items-center space-y-5 overflow-hidden rounded-lg bg-white p-4">
                <h1 class="mb-6 text-3xl font-bold">Register as *</h1>
                <RegisterCard
                    icon
                    title="Student" subtitle="Register as a student
                    a small description for register as student, guardian and teacher
                    a small description for register as student, guardian and teacher
                    a small description for register as student, guardian and teacher
                    a small description for register as student, guardian and teacher"
                    class="max-w-full cursor-pointer transition duration-300 hover:skew-y-2"
                    @click="studentRegistrationLink">
                    <template #icon>
                        <AcademicCapIcon class="h-6 w-6"/>
                    </template>

                </RegisterCard>

                <RegisterCard
                    icon
                    title="Teacher" subtitle="Register as a teacher
                    a small description for register as student, guardian and teacher
                    a small description for register as student, guardian and teacher
                    a small description for register as student, guardian and teacher
                    a small description for register as student, guardian and teacher"
                    class="max-w-full cursor-pointer transition duration-300 hover:skew-y-2"
                    @click="teacherRegistrationLink">
                    <template #icon>
                        <BookOpenIcon class="h-6 w-6"/>
                    </template>

                </RegisterCard>
                <RegisterCard
                    icon title="Guardian" subtitle="Register as a student
                    a small description for register as student, guardian and teacher
                    a small description for register as student, guardian and teacher
                    a small description for register as student, guardian and teacher
                    a small description for register as student, guardian and teacher"
                    class="max-w-full cursor-pointer transition duration-300 hover:skew-y-2"

                    @click="guardianRegistrationLink">
                    <template #icon>
                        <UserCircleIcon class="h-6 w-6"/>
                    </template>

                </RegisterCard>

                <div class="space-x-2">
                    <snap class="text-sm font-light">Already have an account?</snap>
                    <RegisterPrimaryButton class="mt-10" @click="loginLink">
                        Log In
                    </RegisterPrimaryButton>
                </div>
            </div>
        </div>
    </RegisterModal>
</template>

<script setup>
import {ref} from 'vue'
import RegisterCard from "@/Components/Card.vue";
import RegisterPrimaryButton from "@/Components/PrimaryButton.vue";
import {AcademicCapIcon, BookOpenIcon, UserCircleIcon} from "@heroicons/vue/24/outline"
import RegisterModal from "@/Components/Modal.vue";
import {router} from "@inertiajs/vue3";


const props = defineProps({
    toggle: {
        type: Boolean,
        required: true
    }
})

const isOpen = ref(props.toggle)

// link to student registration
function studentRegistrationLink() {
    router.get("/users/create/student");
}

// link to login
function loginLink() {
    router.get("/login");
}

// link to teacher registration
function teacherRegistrationLink() {
    router.get('/users/create/teacher')
}

// link for parent registration
function guardianRegistrationLink() {
    router.get('/users/create/guardian')
}

</script>

<style>
/* Add your custom Tailwind CSS styles here */
</style>
