<template>
    <div class="flex h-screen items-center justify-center">
        <div class="w-full max-w-md">
            <div v-if="showForgotPassword">
                <div class="rounded bg-white px-8 py-6 shadow-md">
                    <h2 class="mb-4 text-lg font-medium">Forgot Password</h2>
                    <form>
                        <div class="mb-4">
                            <label class="mb-2 block font-bold text-gray-700" for="email">
                                Email
                            </label>
                            <input
                                id="email"
                                class="focus:shadow-outline w-full appearance-none rounded border py-2 px-3 leading-tight text-gray-700 shadow focus:outline-none"
                                placeholder="Enter your email address"
                                type="email"
                            />
                        </div>
                        <div class="flex items-center justify-between">
                            <button
                                class="focus:shadow-outline rounded bg-blue-500 py-2 px-4 font-bold text-white hover:bg-blue-700 focus:outline-none"
                                type="button"
                                @click="forgotPassword"
                            >
                                Submit
                            </button>
                            <!--                            <button-->
                            <!--                                class="inline-block align-baseline text-sm font-bold text-blue-500 hover:text-blue-800"-->
                            <!--                                @click="showForgotPassword = false"-->
                            <!--                            >-->
                            <!--                                Cancel-->
                            <!--                            </button>-->
                        </div>
                    </form>
                </div>
            </div>
            <div v-if="showResetPassword">
                <div class="rounded bg-white px-8 py-6 shadow-md">
                    <h2 class="mb-4 text-lg font-medium">Reset Password</h2>
                    <form>
                        <div class="mb-4">
                            <label class="mb-2 block font-bold text-gray-700" for="password">
                                Password
                            </label>
                            <input
                                class="focus:shadow-outline w-full appearance-none rounded border py-2 px-3 leading-tight text-gray-700 shadow focus:outline-none"
                                placeholder="Enter your new password"
                                type="password"
                            />
                        </div>
                        <div class="mb-4">
                            <label class="mb-2 block font-bold text-gray-700" for="password">
                                Confirm Password
                            </label>
                            <input
                                class="focus:shadow-outline w-full appearance-none rounded border py-2 px-3 leading-tight text-gray-700 shadow focus:outline-none"
                                placeholder="confirm password"
                                type="password"
                            />
                        </div>
                        <div class="flex items-center justify-between">
                            <button
                                class="focus:shadow-outline rounded bg-blue-500 py-2 px-4 font-bold text-white hover:bg-blue-700 focus:outline-none"
                                type="button"
                            >
                                <a href="/login">
                                    Reset Password
                                </a>

                            </button>
                            <button
                                class="inline-block align-baseline text-sm font-bold text-blue-500 hover:text-blue-800"
                                @click="resetPassword"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import {ref} from 'vue';

const showForgotPassword = ref(true);
const showResetPassword = ref(false);

function resetPassword() {
    // logic for resetting password goes here
    // once the password is reset, show the forgot password card again
    showForgotPassword.value = true;
    showResetPassword.value = false;
}

function forgotPassword() {
    // logic for sending password reset email goes here
    // once the email is sent, show the reset password card
    showForgotPassword.value = false;
    showResetPassword.value = true;
}
</script>
