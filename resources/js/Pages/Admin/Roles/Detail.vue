<template>
    <div>
        <div class="flex w-full flex-col space-x-2 md:flex-row">
            <!--    Assigned roles -->
            <Card class="max-h-fit max-w-fit" icon title="Assigned Role to User" subtitle="Add or remove roles to update the user's abilities">
                <template #icon>
                    <IdentificationIcon />
                </template>
                <TextInput v-model="query" placeholder="Search roles" />
                <ul class="max-h-80 overflow-auto">
                    <li v-for="(role, r) in newUserRoles" :key="r" class="group flex items-center gap-2 rounded-md p-3 odd:bg-neutral-100">
                        <InformationCircleIcon class="h-3 w-3"></InformationCircleIcon>
                        <span class="grow font-mono text-sm">{{ role.name }}</span>
                        <TrashIcon class="h-4 w-4 stroke-gray-500 stroke-0 group-hover:stroke-2" @click="deleteRole(role)"></TrashIcon>
                    </li>
                </ul>
                <div class="flex w-full items-center justify-between">
                    <span class="text-xs text-gray-500">5 roles selected</span>
                    <div class="flex gap-2">
                        <TertiaryButton title="Reset" @click="resetRolesList" />
                        <PrimaryButton title="Update" @click="showDialog = true" />
                    </div>
                </div>
            </Card>
            <!-- list of roles   -->
            <div class=" max-w-md md:p-2 ">
                <div class="mx-auto my-10 max-w-2xl rounded-xl bg-white p-8 shadow shadow-slate-300">
                    <div class="flex flex-row items-center justify-between space-x-reverse">
                        <h1 class="text-xl font-semibold text-slate-700">Roles</h1>
                    </div>
                    <p class="text-slate-500">List of available roles </p>
                    <SearchRoleTextInput
                        v-model="searchKey"
                        class="w-full max-w-sm"
                        placeholder="Search role"
                        @keyup="search">
                    </SearchRoleTextInput>
                    <div v-for="role in availableRoles" :key="role.id" class="my-3 ">
                        <div class="flex items-center justify-between border-b border-l-4 border-slate-200 border-l-transparent bg-gradient-to-r  from-transparent to-transparent py-3 px-2 transition duration-150 ease-linear hover:from-slate-100">
                            <div class="inline-flex items-center space-x-2">
                                <div>
                                    <input v-model="newUserRoles" type="checkbox" :value="role" class="h-5 w-5 text-blue-600 transition duration-150 ease-in-out">
                                </div>
                                <div>{{ role.name }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <DialogBox
            v-if="showDialog"
            @confirm="handleConfirm"
            @close="showDialog= false">
        </DialogBox>

        <!-- previous role logs -->
        <div class="rounded-xl bg-white p-8 md:pt-14 ">
            <h3 class="pb-5 text-xl font-bold">Logs</h3>
            <ul class="flex max-w-2xl flex-col divide-y divide-gray-200 dark:divide-gray-700">
                <li  v-for="activityLog in activities" :key="activityLog.date" class="justify-center pb-3 sm:pb-4">
                    <div class="flex items-center space-x-5 p-1">
                        <div class="shrink-0">
                            <MinusIcon class="w-4 stroke-2"/>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-md truncate font-medium text-gray-900 dark:text-white">
                                <span class="font-bold">{{activityLog.role}}</span>
                            </p>
                            <div class="truncate text-sm text-gray-500 dark:text-gray-400">
                                Role <span> {{ activityLog.action }}</span> by <span class="">{{ activityLog.givenby }}</span>
                            </div>
                        </div>
                        <div class=" inline-flex items-center text-base font-normal text-gray-400 dark:text-white">
                            {{ activityLog.date  }}
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue"
import Card from "@/Components/Card.vue"
import DialogBox from "@/Components/DialogBox.vue"
import SearchRoleTextInput from "@/Components/TextInput.vue"
import PrimaryButton from "@/Components/PrimaryButton.vue"
import TertiaryButton from "@/Components/TertiaryButton.vue"
import { InformationCircleIcon, TrashIcon, IdentificationIcon, MinusIcon } from "@heroicons/vue/24/outline"
import { usePage, router } from "@inertiajs/vue3"
import TextInput from "@/Components/TextInput.vue";
import {debounce} from "lodash";
const showDialog = ref(false)
const user_roles = computed(() => usePage().props.user_roles)
const roles = computed(() => usePage().props.roles)
const activities = computed(() => usePage().props.activities)
const searchKey = ref(usePage().props.searchKey);
const search = debounce(() => {
    router.get(
        "/roles/users",
        { search: searchKey.value },
        { preserveState: true, replace: true }
    );
}, 300);


function handleConfirm(){
    const urlParams = new URLSearchParams(window.location.search);
    router.post('/roles/assign', {
        roles: newUserRoles.value.map(role => role.name),
        user_id: urlParams.get('user_id')
    }, {
        onSuccess: () =>{
            showDialog.value=false
            console.log("Success")
        },
        onError: (error) =>{
            console.log("Error")
        }
    })
}
const availableRoles = computed(() => {
    return roles.value.filter(a_role => !newUserRoles.value.map(role => role.name).includes(a_role.name))
})
const newUserRoles = ref([...user_roles.value])

function resetRolesList() {
    newUserRoles.value = [...user_roles.value]
}
const query = ref('')
function deleteRole(role) {
    newUserRoles.value = newUserRoles.value.filter((item) => item !== role)
}
</script>
