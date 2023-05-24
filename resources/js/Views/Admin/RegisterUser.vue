<template>
    <RegisterModal v-model:view="isOpen">

        <div class="grid w-full space-y-6 overflow-hidden rounded-lg bg-white p-5">
            <h1 class="flex justify-center pt-4 text-lg font-bold">Choose user type</h1>
            <RegisterCard
                v-if="hasRole('manage-students')"
                icon
                title="Guardian and Student" subtitle="Students and their guardians are required to provide personal information such as name,
                contact information and any special needs or accommodations required. Depending on the institution, guardians may also need to
                 provide legal documentation such as proof of guardianship or custody."
                class="hover:scale-11 min-w-full max-w-full cursor-pointer transition duration-500 ease-in-out hover:-translate-y-1"
                @click="familyRegistrationLink">
                <template #icon>
                    <AcademicCapIcon class="h-6 w-6"/>
                </template>

            </RegisterCard>

            <RegisterCard
                v-if="hasRole('manage-guardians')"
                icon
                title="Teacher" subtitle=" Teachers are typically required to provide personal information such as name,
                contact information, and educational qualifications.
                They may also need to provide teaching experience, references, and any specialized training or
                certifications."
                class="hover:scale-11 min-w-full max-w-full cursor-pointer transition duration-500 ease-in-out
                hover:-translate-y-1"
                @click="teacherRegistrationLink">
                <template #icon>
                    <BookOpenIcon class="h-6 w-6"/>
                </template>
            </RegisterCard>

            <RegisterCard
                v-if="hasRole('manage-admins')"
                icon
                title="Admin" subtitle="Administrators may need to provide personal information such as name, contact
                information, and job position.
                They may also need to provide professional qualifications and references."
                class="hover:scale-11 min-w-full max-w-full cursor-pointer transition duration-500 ease-in-out
                hover:-translate-y-1"
                @click="adminRegistrationLink">
                <template #icon>
                    <BuildingLibraryIcon class="h-6 w-6"/>
                </template>
            </RegisterCard>

        </div>
    </RegisterModal>
</template>

<script setup>
import {computed, ref} from 'vue'
import RegisterCard from "@/Components/Card.vue";
import {AcademicCapIcon, BookOpenIcon, BuildingLibraryIcon} from "@heroicons/vue/24/outline"
import RegisterModal from "@/Components/Modal.vue";
import {router} from "@inertiajs/vue3";


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

// link to student registration
function familyRegistrationLink() {
    router.get("register/guardian");
}

// link to teacher registration
function teacherRegistrationLink() {
    router.get('register/teacher')
}

// link to admin registration
function adminRegistrationLink() {
    router.get('register/admin')
}
</script>

<style>
/* Add your custom Tailwind CSS styles here */
</style>
