<template>
    <div
        class="flex min-h-screen justify-center py-2"
    >
        <div
            class="mx-2 mt-[5rem] flex h-fit w-full flex-col items-center py-4 sm:w-9/12 md:w-7/12 lg:w-5/12 xl:w-4/12"
        >
            <!--            <div class="text-3xl font-light italic">-->
            <!--                School Management System-->
            <!--            </div>-->
            <img alt="place holder" src="/assets/school.png"/>
            <div class="my-3 mb-6 text-xs font-light">
                welcome back, please login to your account.
            </div>

            <!--        email or phone input section-->
            <div class="mt-4 w-11/12">
                <div class="flex items-center justify-center">
                    <div
                        class="bg-dark-50 dark:bg-dark-100 flex h-9 items-center rounded-l-md px-2"
                    >
                        <svg
                            class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                                stroke-linecap="round"
                                stroke-linejoin="round"/>
                        </svg>

                    </div>
                    <input
                        v-model="form.emailOrPhone"
                        class="h-8 w-full rounded-md pl-2 text-[0.6rem] font-light"
                        placeholder="Email or Phone"
                        type="text"/>
                </div>

            </div>

            <!--      Password input section-->
            <div class="mt-4 w-11/12">
                <div class="flex items-center justify-center">
                    <div
                        class="bg-dark-50 dark:bg-dark-100 flex h-9 items-center rounded-l-md px-2"
                    >
                        <svg
                            class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"
                                stroke-linecap="round"
                                stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <input
                        v-model="form.password"
                        class="h-8 w-full rounded-md pl-2 text-[0.6rem] font-light"
                        placeholder="Password"
                        type="password"/>
                </div>

            </div>

            <!--      Remember me and forgot password section-->
            <div
                class="text-dark-50 mt-6 flex w-full justify-around text-xs font-light"
            >
                <div class="mr-2 flex items-center">
                    <input
                        id="remember-me"
                        class="mr-1 rounded focus:ring-0"
                        type="checkbox"
                    />
                    <label for="remember-me">Remember Me</label>
                </div>
                <div class="ml-2 underline">

                    <a
                        class="text-dark-50 font-bold"
                        href="/forgot-password"
                    >
                        Forgot Password?
                    </a>
                </div>
            </div>

            <!--      Login and signup button section-->
            <button
                class="text-dark-50 my-4 mt-6 h-9 w-11/12 rounded-md bg-gray-300 font-light"
                @click="submit"
            >
                LOGIN
            </button>
            <div class="text-xs font-light">
                Don't have an account?
                <a
                    class="text-dark-50 font-bold"
                >
                    Sign up
                </a>
            </div>
        </div>
        <div class="lg:my-18 mb-6 md:mb-0 md:w-8/12 lg:mr-5 lg:w-5/12">
            <img alt="place holder" class="ml-36 w-8/12" src="/assets/Allura.png    "/>
        </div>
    </div>
</template>

<script setup>
import {useForm} from "@inertiajs/vue3";

const form = useForm({
    emailOrPhone: "bini@gmail.com",
    password: "secret",
});
const submit = () => {
    form.post(route("login"), {
        onFinish: () => {
            console.log("Login finished")
        },
        onSuccess: () => {
            console.log("Success")
        },
        onError: () => {
            console.log("Error")
        }
    });
};
</script>

<style scoped></style>
