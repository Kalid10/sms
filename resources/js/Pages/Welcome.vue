<template>
  <div class="">Welcome</div>
    <button class="bg-red-400 p-1" @click="login">Test Login</button>
    <button class="bg-red-400 p-1" @click="assignRoles">Test Assign Role</button>
    <button class="bg-red-400 p-1" @click="removeRoles">Test Remove Role</button>
    <button class="bg-red-400 p-1" @click="roleActivities">Test Role Activities</button>
    <button class="bg-red-400 p-1" @click="addSubject">Test Add Subject</button>
<button class="bg-red-400 p-1" @click="deleteSubject">Test Delete Subject</button>
</template>
<script setup>
import {router} from "@inertiajs/vue3";
function login() {
    router.post('/login', {
        emailOrPhone: "test@gmail.com",
        password: "secret"
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

function assignRoles() {
    router.post('/roles/assign', {
        user_id: 2,
        roles: ["manage-roles", "manage-subjects"]
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
function removeRoles() {
    router.post('/roles/remove', {
        user_id: 2,
        roles: ["manage-roles", "manage-subjects"]
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
function roleActivities() {
    router.get('/roles/activities', {
        roles: ["manage-roles"],
        user_id: 3
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
function addSubject() {
    router.post('/subject/create', {
        full_name: "Test Subject",
        short_name: "TS",
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
// Change the id to the id of the subject you want to delete
function deleteSubject(){
    router.delete('/subject/delete/'+ 2, {
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

<style>
</style>

