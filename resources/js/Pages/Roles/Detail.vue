<template>
    <div class="flex w-full flex-col space-x-2 p-4 md:flex-row">

<!--    Assigned rols -->
    <Card title="Assigned roles:" subtitle="" class="mb-2  md:w-3/5 ">
        <div class="">
            <div>
            <div class="">
                <ul>
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

<!--        <p>{{ newUserRoles }}</p>-->

        <!-- list of roles   -->
        <div class=" md:p-2 lg:w-full">
            <div class="mx-auto my-10 max-w-2xl rounded-xl bg-white p-8 shadow shadow-slate-300">
                <div class="flex flex-row items-center justify-between space-x-reverse">
                    <h1 class="text-2xl font-semibold text-slate-700">Roles</h1>
                </div>
                <p class="text-slate-500">List of roles available</p>
                <SearchRoleTextInput placeholder="Search role"></SearchRoleTextInput>

                <div v-for="role in all_roles" :key="role.id" class="my-3 ">
                    <div class="flex items-center justify-between border-b border-l-4 border-slate-200 border-l-transparent bg-gradient-to-r  from-transparent to-transparent py-3 px-2 transition duration-150 ease-linear hover:from-slate-100">
                        <div class="inline-flex items-center space-x-2">
                            <div>
                                <input v-model="newUserRoles" type="checkbox" :value="role.name" class="h-5 w-5 text-blue-600 transition duration-150 ease-in-out">
                            </div>
                            <div>{{ role.name }}</div>
                        </div>

                    </div>

                </div>
            </div>
        </div>


<!--    assign new role -->
    <div class=" mt-2 h-fit  md:w-2/5 ">
<!--        <div class="">-->
<!--            <PrimaryButton class="inline-flex items-center justify-between px-4 py-3 text-center text-sm font-medium text-white"  type="button" @click="showRolesDropdown=!showRolesDropdown">Assign new Role-->
<!--                <svg class="ml-2 h-5 w-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>-->
<!--            </PrimaryButton>-->


<!--            &lt;!&ndash; Dropdown menu &ndash;&gt;-->
<!--            <div v-if="showRolesDropdown" class="z-10 w-full bg-white">-->
<!--                <div class="p-3">-->
<!--                        <SearchRoleTextInput type="text" class="block w-full text-sm text-gray-900 " placeholder="Search roles"></SearchRoleTextInput>-->
<!--                </div>-->
<!--                <ul class="h-48 overflow-y-auto px-3 pb-3 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownSearchButton">-->
<!--                    <li>-->
<!--                        <div class="flex items-center rounded p-2 hover:bg-gray-100 dark:hover:bg-gray-600">-->
<!--                            <input id="checkbox-item-11" type="checkbox" value="" class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-500 dark:bg-gray-600 dark:ring-offset-gray-700 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-700">-->
<!--                            <label for="checkbox-item-11" class="ml-2 w-full rounded text-sm font-medium text-gray-900 dark:text-gray-300">Admin</label>-->
<!--                        </div>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <div class="flex items-center rounded p-2 hover:bg-gray-100 dark:hover:bg-gray-600">-->
<!--                            <input id="checkbox-item-12" checked type="checkbox" value="" class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-500 dark:bg-gray-600 dark:ring-offset-gray-700 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-700">-->
<!--                            <label for="checkbox-item-12" class="ml-2 w-full rounded text-sm font-medium text-gray-900 dark:text-gray-300">Manage Teachers</label>-->
<!--                        </div>-->
<!--                    </li>-->
<!--                </ul>-->
<!--                <div class="flex justify-end">-->
<!--                    <PrimaryButton title="Add" class="m-4" @click="showDialog = true"></PrimaryButton>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
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
import DialogBox from "@/Components/DialogBox.vue";
import Card from "@/Components/Card.vue"
import {now} from "@vueuse/core";

const showDialog = ref(false)
const updatedRoles = ref([])




onMounted(() =>{
    showAllRoles()
    // roleActivities();
})

function showAllRoles() {
    router.get('/roles', {
    }, {
        preserveState: true,
        onSuccess: () =>{
            console.log("Success")
        },
        onError: (error) =>{
            console.log("Error")
            console.log(error)
        }
    })
}


function roleActivities(row) {
    alert("Role activity")
    router.get('/roles/activities', {
        roles: ["manage-roles"],
        user_id:1
    }, {
        preserveState: true,
        onSuccess: () =>{
            console.log("Success")
        },
        onError: (error) =>{
            console.log("Error")
            console.log(error)
        }
    })
}


const user_roles = computed(() => {
    return usePage().props.user_roles;
});

const all_roles = computed(() =>{
    return usePage().props.roles;
});

const activities = computed(()=>{
    return usePage().props.activities;
});






function handleConfirm(confirmationType) {
    assignRoles();
    showDialog.value = false

}


const newUserRoles = ref([...user_roles.value]);
// alert(newUserRoles.value);

    function deleteRole(role){
        const index = newUserRoles.value.indexOf(role);
        if (index > -1) {
            newUserRoles.value.splice(index, 1);
        }
        role.deleted_at = now();
        updatedRoles.value.pop(role);
    }


function assignRoles() {
    router.post('/roles/assign', {
        user_id: 2,
        roles: newUserRoles.value
    }, {
        onSuccess: () =>{
            console.log("Success")
        },
        onError: (error) =>{
            console.log("Error")
            console.log(error)
        }
    })
}
</script>
