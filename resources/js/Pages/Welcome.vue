<template>
  <div class="">Welcome</div>
    <button class="bg-red-400 p-1" @click="login">Test Login</button>
</template>
<script setup>
import {router} from "@inertiajs/vue3";
function login() {
    router.post('/login', {
        emailOrPhone: "test@gmail.com",
        password: "secret"
    }, {
        onFinish: () => {
            console.log("Login finished")
        },
        onSuccess: () =>{
            console.log("Success")
        },
        onError: () =>{
            console.log("Error")
        }
    })
}
</script>

<style>
</style>

