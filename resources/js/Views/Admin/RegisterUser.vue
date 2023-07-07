<template>
    <RegisterModal v-model:view="isOpen">

        <div class="grid w-full space-y-6 overflow-hidden rounded-lg bg-white p-5">
            <h1 class="flex justify-center pt-4 text-lg font-bold">{{ $t('registerUser.header')}}</h1>
            <RegisterCard
                v-if="hasRole('manage-students')"
                icon
                :title="$t('registerUser.guardianStudentRegisterTitle')"
                :subtitle="$t('registerUser.guardianStudentRegisterSubtitle')"
                class="hover:scale-11 min-w-full max-w-full cursor-pointer transition duration-500 ease-in-out hover:-translate-y-1"
                @click="familyRegistrationLink">
                <template #icon>
                    <AcademicCapIcon class="h-6 w-6"/>
                </template>

            </RegisterCard>

            <RegisterCard
                v-if="hasRole('manage-guardians')"
                icon
                :title="$t('registerUser.teacherRegisterTitle')"
                :subtitle="$t('registerUser.teacherRegisterSubtitle')"
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
                :title="$t('registerUser.adminRegisterTitle')"
                :subtitle="$t('registerUser.adminRegisterSubtitle')"
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
import StudentsList from "@/Views/Teacher/Views/Batches/PerformanceHighlights/StudentsList.vue";


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
