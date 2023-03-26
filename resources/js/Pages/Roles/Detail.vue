<template>
    <div class="flex w-full flex-col space-x-2 p-4 md:flex-row">
<!--    Assigned rols -->
    <Card subtitle="" class="mb-2  md:w-3/5 ">
        <h1 class="text-xl font-semibold text-slate-700">Assigned roles:
        </h1>
        <div class="">
            <div>
            <div class="">
                <ul class="h-64 overflow-y-scroll pr-5">
                    <li v-for="role in newUserRoles" :key="role.id" class="m-2 flex flex-row justify-between p-1 hover:bg-gray-100">
                       <div>
                           <p>{{role.name}}</p>
                       </div>
                        <div>
                            <TertiaryButton title="Remove" @click="deleteRole(role)"></TertiaryButton>
                        </div>
                    </li>
                </ul>
            </div>
                <div class="flex justify-end">
                    <PrimaryButton title="Update" class="m-4" @click="showDialog=true"></PrimaryButton>
                </div>
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
                <SearchRoleTextInput v-model="query" placeholder="Search role"></SearchRoleTextInput>
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
</template>

<script setup>
import { onMounted, ref, computed } from "vue"
import Card from "@/Components/Card.vue"
import DialogBox from "@/Components/DialogBox.vue"
import SearchRoleTextInput from "@/Components/TextInput.vue"
import PrimaryButton from "@/Components/PrimaryButton.vue"
import TertiaryButton from "@/Components/TertiaryButton.vue"
import { MinusIcon } from "@heroicons/vue/24/outline"
import { usePage } from "@inertiajs/vue3"

const user_roles = computed(() => usePage().props.user_roles)
const roles = computed(() => usePage().props.roles)
const activities = computed(() => usePage().props.activities)


const availableRoles = computed(() => {
    return roles.value.filter(a_role => !newUserRoles.value.map(role => role.name).includes(a_role.name))
})
const newUserRoles = ref([...user_roles.value])

const query = ref('')

function deleteRole(role) {
    newUserRoles.value = newUserRoles.value.filter((item) => item !== role)
}

onMounted(() => {



})

</script>
