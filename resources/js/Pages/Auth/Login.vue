<template>
    <div
        class="flex min-h-screen justify-center py-2"
    >
        <div
            class="mx-2 mt-[5rem] flex h-fit w-full flex-col items-center py-4 sm:w-9/12 md:w-7/12 lg:w-5/12 xl:w-4/12"
        >
            <div class="text-3xl font-light italic">
                Rigel SMS
            </div>
            <div class="my-3 mb-6 text-xs font-light">
                welcome back, please login to your account
            </div>

            <!--        email or phone input section-->
            <div class="mt-4 w-11/12">
                <div class="flex items-center justify-center">
                    <input
                        v-model="form.emailOrPhone"
                        class="h-8 w-full rounded-md pl-2 text-[0.6rem] font-light"
                        placeholder="Email or Phone"
                        type="text"/>
                </div>
                <div

                    class="text-dark-50 mt-1 ml-1 text-[0.55rem]"
                >
                    Email or Phone
                </div>

            </div>

            <!--      Password input section-->
            <div class="mt-4 w-11/12">
                <div class="flex items-center justify-center">
                    <input
                        v-model="form.password"
                        class="h-8 w-full rounded-md pl-2 text-[0.6rem] font-light"
                        placeholder="Password"
                        type="password"/>
                </div>
                <div
                    class="text-dark-50 ml-1 text-[0.55rem]"
                >
                    Password
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
