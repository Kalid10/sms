<template>
    <div class="flex flex-col space-x-2 p-4 md:flex-row">
<!--    assigned rols -->
    <Card title="Assigned roles:" subtitle="" class="mb-2 md:w-3/5">
        <div class="">
            <div class="hover:~bg-red-500/10">
            <div>
                <ul>
                    <li v-for="role in user_roles" :key="role.id" class="m-2 flex flex-row justify-between p-1 hover:bg-gray-100">
                       <div>
                           <p :class="deleted">{{role.name}}</p>
                       </div>
                        <div>
                            <TertiaryButton title="Remove"></TertiaryButton>
                        </div>
                    </li>

                </ul>
            </div>
                <div class="flex justify-end">
                    <PrimaryButton title="Submit" class="m-4"></PrimaryButton>
                </div>
            </div>
        </div>
    </Card>





<!--    assign new role -->
    <div class=" mt-2 h-fit border border-blue-400 md:w-2/5 ">
        <div class=" ">
            <PrimaryButton class="inline-flex items-center justify-between px-4 py-3 text-center text-sm font-medium text-white"  type="button" @click="showRolesDropdown=!showRolesDropdown">Assign new Role
                <svg class="ml-2 h-5 w-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </PrimaryButton>


            <!-- Dropdown menu -->
            <div v-if="showRolesDropdown" class="z-10 w-full bg-white">
                <div class="p-3">
                        <SearchRoleTextInput type="text" class="block w-full text-sm text-gray-900 " placeholder="Search roles"></SearchRoleTextInput>
                </div>
                <ul class="h-48 overflow-y-auto px-3 pb-3 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownSearchButton">
                    <li>
                        <div class="flex items-center rounded p-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <input id="checkbox-item-11" type="checkbox" value="" class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-500 dark:bg-gray-600 dark:ring-offset-gray-700 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-700">
                            <label for="checkbox-item-11" class="ml-2 w-full rounded text-sm font-medium text-gray-900 dark:text-gray-300">Admin</label>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center rounded p-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <input id="checkbox-item-12" checked type="checkbox" value="" class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-500 dark:bg-gray-600 dark:ring-offset-gray-700 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-700">
                            <label for="checkbox-item-12" class="ml-2 w-full rounded text-sm font-medium text-gray-900 dark:text-gray-300">Manage Teachers</label>
                        </div>
                    </li>
                </ul>
                <div class="flex justify-end">
                    <PrimaryButton title="Add" class="m-4"></PrimaryButton>
                </div>
            </div>
        </div>
    </div>

    </div>



    <p>{{activities}} activity logs </p>



<!-- previous role logs -->
    <div class="rounded-xl bg-white p-8 md:pt-14 ">
        <h3 class="pb-5 text-xl font-bold">Logs</h3>
        <ul class="flex max-w-2xl flex-col divide-y divide-gray-200 dark:divide-gray-700">
            <li  v-for="activityLog in activityLogs" :key="activityLog.date" class="justify-center pb-3 sm:pb-4">
                <div class="flex items-center space-x-5 p-1">
                    <div class="shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                        </svg>
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
import {computed, onMounted,ref} from "vue";
import {router, usePage} from "@inertiajs/vue3";

import PrimaryButton from "@/Components/PrimaryButton.vue"
import TertiaryButton from "@/Components/TertiaryButton.vue";
import SearchRoleTextInput from "@/Components/TextInput.vue"
import TableElement from "@/Components/TableElement.vue";
import RolesGroupedCheckBox from "@/Components/GroupedCheckBox.vue"
import Card from "@/Components/Card.vue"

const showRolesDropdown =  ref(false)

const user_roles = computed(() => {
    return usePage().props.user_roles;
});

// onMounted(() =>{
//     roleActivities();
// })


const activities = computed(()=>{
    return usePage().props.activities;
})

// function roleActivities() {
//     alert("Role activity")
//     router.get('/roles/activities', {
//         roles: ["manage-roles"],
//         user_id: 1
//     }, {
//         preserveState: true,
//         onSuccess: () =>{
//             console.log("Success")
//         },
//         onError: (error) =>{
//             console.log("Error")
//             console.log(error)
//         }
//     })
// }
</script>
