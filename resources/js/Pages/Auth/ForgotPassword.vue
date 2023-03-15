<template>
    <div class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto flex flex-col items-center justify-center px-6 py-8 md:h-screen lg:py-0">
            <a href="#" class="mb-6 flex items-center text-2xl font-semibold text-gray-900 dark:text-white">
                Forget Password ?
            </a>
            <div class="w-full rounded-lg bg-white p-6 shadow dark:border dark:border-gray-700 dark:bg-gray-800 sm:max-w-md sm:p-8 md:mt-0">
                <h2 class="mb-1 text-xl font-bold leading-tight tracking-tight text-gray-900 dark:text-white md:text-2xl">
                    Change Password
                </h2>
                <form class="mt-4 space-y-4 md:space-y-5 lg:mt-5" action="#">
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Your email or phone</label>
                        <input id="email" type="email" name="email" class="focus:ring-primary-600 focus:border-primary-600 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm" placeholder="name@company.com / 0911..." required="">
                    </div>
                    <div class="mr-2 flex flex-col">
                        <label for="email" class="mb-1 block text-sm font-medium text-gray-900 dark:text-white">Send confirmation message via:  </label>

                        <div class="mb-4 flex items-center">
                            <input id="default-radio-1" type="radio" value="" name="default-radio" class="h-4 w-4 border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                            <label for="default-radio-1" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">email</label>
                        </div>
                        <div class="flex items-center">
                            <input id="default-radio-2" checked type="radio" value="" name="default-radio" class="h-4 w-4 border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                            <label for="default-radio-2" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">phone number</label>
                        </div>

                        <button class="mt-2 rounded border border-black py-1 px-4 text-black">
                            Send
                        </button>
                    </div>
                    <div>
                        <input placeholder="otp" class="focus:ring-primary-600 focus:border-primary-600 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm" required="">
                        <button class="mt-2 rounded border border-black py-1 px-4 text-black" @click="handleVerification">
                            verify
                        </button>
                    </div>
                    <div v-if="verification">
                        <div>
                            <label for="password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">New Password</label>
                            <input id="password" type="password" name="password" placeholder="••••••••" class="focus:ring-primary-600 focus:border-primary-600 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm" required="">
                        </div>

                        <div>
                            <label for="confirm-password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Confirm password</label>
                            <input id="confirm-password" type="confirm-password" name="confirm-password" placeholder="••••••••" class="focus:ring-primary-600 focus:border-primary-600 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm" required="">
                        </div>
                        <button
                            class="text-dark-50 my-4 mt-6 h-9 w-full rounded-md bg-gray-300 font-light"
                        >
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';

const verification = ref(false);

// Handle verification
const handleVerification = () => {
    verification.value = !verification.value;
}
</script>
