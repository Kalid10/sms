<template>

    <UsersStatistics/>

    <Card icon subtitle="Teenage Ninja Mutant Spiders" title="Kylian Mbappe">

        <template #icon>
            <BugAntIcon/>
        </template>

    </Card>

    <TableElement
        :columns="config" :data="users"
        actionable
        row-actionable
        selectable
        subtitle="List of personnel registered on your system, with user types and contact information"
        title="Users List"
    >

        <template #action="{ selected }">
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
            <TextInput v-model="formData.position" label="Position" placeholder="Position of user"/>
            <SelectInput
                v-model="formData.role"
                :options="roleOptions" label="Role" placeholder="placeholder"
                required
            />

        </FormElement>
    </Modal>

</template>

<script setup>
import {ref} from "vue"
import {
    BugAntIcon,
    EyeIcon,
    ArrowPathIcon,
    ArchiveBoxXMarkIcon,
    CloudArrowDownIcon
} from "@heroicons/vue/24/outline"
import {users} from "@/fake";
import {Link} from '@inertiajs/vue3'
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue"
import TextInput from "@/Components/TextInput.vue"
import SelectInput from "@/Components/SelectInput.vue"
import TableElement from "@/Components/TableElement.vue"
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";
import Card from "@/Components/Card.vue"
import UsersStatistics from "@/Views/UsersStatistics.vue";

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

function updateItems(items) {
    console.log(`Items to update are `, items.map((item) => item.id))
}

function moveItems(items) {
    console.log(`Items to move are `, items.map((item) => item.id))
}

const config = [
    {
        name: 'Full Name',
        key: 'name',
        link: '/users/{id}',
    },
    {
        name: 'Email',
        key: 'email',
        link: 'mailto:{email}'
    },
    {
        name: 'Phone Number',
        key: 'phone',
    },
    {
        name: 'User Type',
        key: 'type',
        type: 'enum',
        options: ['admin', 'teacher', 'student']
    },
    {
        name: 'User Roles',
        key: 'roles',
        link: 'https://google.com'
    },
    {
        name: 'Active',
        key: 'active',
        type: Boolean
    },
]

const showRegisterUser = ref(false)
const showModal = ref(false)

function createUserForm() {
    showRegisterUser.value = true
}
</script>

<style scoped>

</style>
