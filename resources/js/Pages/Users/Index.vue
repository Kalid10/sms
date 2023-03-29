<template>

    <UsersStatistics/>

    <TableElement
        :data="users"
        actionable
        row-actionable
        selectable
        subtitle="List of personnel registered on your system, with user types and contact information"
        title="Users List"
    >

        <template #action="{ selected }">
            <div class="flex flex-row space-x-4">
                <div v-if="selected.selected" class="flex items-center gap-2">
                    <TertiaryButton
                        title="Move Items"
                        @click="moveItems(selected.items)"
                    />
                    <PrimaryButton
                        title="Update Items"
                        @click="updateItems(selected.items)"
                    />
                </div>
                <PrimaryButton v-else title="Create New User" @click="createUserForm">
                <span class="flex items-center gap-2">
                    <CloudArrowDownIcon class="h-4 w-4 stroke-white stroke-2"/>
                    <span>
                        Download
                        <span class="font-mono">CSV</span>
                    </span>
                </span>
                </PrimaryButton>
                <PrimaryButton @click="openRegisterOptions">
                    Add User
                </PrimaryButton>
            </div>
        </template>

        <template #row-actions="{ row }">
            <Link :href="'/users/' + row.id" class="flex flex-col items-center gap-1">
                <EyeIcon class="h-3 w-3 stroke-2 transition-transform duration-150 hover:scale-125"/>
            </Link>
            <Link :href="'/users/' + row.id + '/edit'" class="flex flex-col items-center gap-1">
                <ArrowPathIcon
                    class="h-3 w-3 stroke-2 transition-all duration-150 hover:scale-125 hover:stroke-blue-700"/>
            </Link>
            <Link :href="'/users/' + row.id + '/delete'" class="flex flex-col items-center gap-1">
                <ArchiveBoxXMarkIcon
                    class="h-3 w-3 stroke-2 transition-all duration-150 hover:scale-125 hover:stroke-red-700"/>
            </Link>
        </template>

    </TableElement>

    <Register v-if="showRegisterOptions" :user-roles="userRoles" :toggle="showRegisterOptions"></Register>

    <Modal v-model:view="showModal">
        <FormElement
            v-model:show-modal="showModal" modal subtitle="Update the selected user"
            title="Update user"
        >
            <TextInput v-model="formData.name" label="Name" placeholder="Update user name" required/>
            <TextInput v-model="formData.role" label="Role" placeholder="Update user role"/>
            <TextInput v-model="formData.position" label="Position" placeholder="Update user position"/>

        </FormElement>
    </Modal>

    <Modal v-model:view="showRegisterUser">
        <FormElement
            v-model:show-modal="showRegisterUser"
            modal
            subtitle="Fill in the information required about the new user"
            title="Register new User" @cancel="showRegisterUser = false"
        >

            <TextInput v-model="formData.name" label="Name" placeholder="Full name of new user" required/>

            <div class="flex gap-3">

                <TextInput v-model="formData.position" class="w-1/2 lg:w-3/5" label="Position" placeholder="Position of user"/>

                <DatePicker v-model:start-date="start_date" v-model:end-date="end_date" range placeholder="Select a Date" required label="Start Date" class="w-1/2 lg:w-2/5" />

            </div>

            <RadioGroupPanel v-model="userType" :options="user_types" label="User Type" name="user_type"/>

        </FormElement>
    </Modal>

</template>

<script setup>
import {computed, ref} from "vue"
import {ArchiveBoxXMarkIcon, ArrowPathIcon, BugAntIcon, CloudArrowDownIcon, EyeIcon} from "@heroicons/vue/24/outline"
import {Link, usePage} from '@inertiajs/vue3'
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue"
import TextInput from "@/Components/TextInput.vue"
import SelectInput from "@/Components/SelectInput.vue"
import TableElement from "@/Components/TableElement.vue"
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";
import Card from "@/Components/Card.vue"
import UsersStatistics from "@/Views/UsersStatistics.vue";
import RadioGroupPanel from "@/Components/RadioGroupPanel.vue";
import DatePicker from "@/Components/DatePicker.vue";
import Register from "@/Views/RegisterUser.vue";

const showRegisterOptions = ref(false);

function openRegisterOptions() {
    showRegisterOptions.value = !showRegisterOptions.value;
}

const formData = ref({
    name: '',
    position: '',
    role: ''
})

const roleOptions = [
    {value: 'admin', label: 'Administrator'},
    {value: 'teacher', label: 'Teacher'},
    {value: 'student', label: 'Student'},
];

const userType = ref('admin')
const user_types = [
    {
        id: 'admin',
        value: 'admin',
        label: 'Administrator',
        description: 'Manages the school resources based on access level',
    },
    {
        id: 'teacher',
        value: 'teacher',
        label: 'Teacher',
        description: 'Teachers employed by the school, tasked with taking over classes and managing students',
    },
    {
        id: 'student',
        value: 'student',
        label: 'Student',
        description: 'Students registered in the school, with access to their own assignment and assessment resources',
    },
    {
        id: 'guardian',
        value: 'guardian',
        label: 'Guardian',
        description: 'Guardians of students registered in the school, have access their children\'s resources i.e., grade reports, accessment results and more'
    }
]

function updateItems(items) {
    console.log(`Items to update are `, items.map((item) => item.id))
}

function moveItems(items) {
    console.log(`Items to move are `, items.map((item) => item.id))
}

// Get all users
const users = computed(() => {
    return usePage().props.users.data;
});

const userRoles = computed(() => usePage().props.user_roles);


const showRegisterUser = ref(true)
const showModal = ref(false)

function createUserForm() {
    showRegisterUser.value = true
}

const start_date = ref(null)
const end_date = ref(null)
</script>

<style scoped>

</style>
