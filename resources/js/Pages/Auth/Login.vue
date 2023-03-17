<template>
    <div
        class="flex min-h-screen justify-center py-2"
    >
        <div
            class="mx-2 mt-[5rem] flex h-fit w-full flex-col items-center py-4 sm:w-9/12 md:w-7/12 lg:w-5/12 xl:w-4/12"
        >
            <div class="text-3xl font-light italic ">
                Welcome Back :)
            </div>

            <div class="my-3 mb-6 text-xs font-light">
                Hello! Please enter details.
            </div>

            <!--        User input section-->
            <div class="mt-4 w-11/12">
                <div class="flex items-center justify-center">
                    <div
                        class="mt-5 flex h-9 items-center rounded-l-md px-1"
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
                    <login-text-input
                        v-model="form.emailOrPhone"
                        class="w-full rounded-md pl-2 text-[0.6rem] font-light"
                        label="Email or Phone"
                        placeholder="Email or Phone"
                        required/>
                </div>
                <div
                    v-if="form.errors.emailOrPhone"
                    class="ml-16 mt-2 text-[0.55rem] text-red-600"
                >
                    *{{ form.errors.emailOrPhone }}
                </div>
            </div>

            <!--      Password input section-->
            <div class="mt-4 w-11/12">
                <div class="flex items-center justify-center">
                    <div
                        class="mt-10 flex h-9 items-center rounded-l-md px-1"
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
                    <login-text-input
                        v-model="form.password"
                        class="h-8 w-full rounded-md pl-2 text-[0.6rem] font-light"
                        label="Password"
                        placeholder="*********"
                        required
                        type="password"/>
                    <div
                        v-if="form.errors.password"
                        class="text-lightText-200 text-[0.55rem]"
                    >
                        *{{ form.errors.password }}
                    </div>
                </div>

            </div>

            <!--      Remember me and forgot password section-->
            <div
                class="mt-9 flex w-full justify-around text-xs font-light text-black"
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
                        class="font-bold"
                        href="/forgot-password"
                    >
                        Forgot Password?
                    </a>
                </div>
            </div>

            <!--      Login and signup button section-->
            <login-primary-button :click="submit" class="my-4 w-8/12" title="Login"/>
            <div class="text-xs font-light">
                Don't have an account?
                <a
                    class="text-dark-50 cursor-pointer font-bold "
                >
                    Sign up
                </a>
            </div>
        </div>
        <div class="mb-6 hidden items-center justify-center lg:my-6 lg:mr-4 lg:flex xl:mr-1">
            <img alt="place holder" src="/assets/back-to-school.svg"/>
        </div>
    </div>
</template>

<script setup>
import {useForm} from "@inertiajs/vue3";
import LoginTextInput from "@/Components/TextInput.vue";
import LoginPrimaryButton from "@/Components/PrimaryButton.vue";

const form = useForm({
    emailOrPhone: "",
    password: "",
});

// submit form
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
