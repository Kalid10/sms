<template>
    <div class="flex min-h-screen justify-center py-10">
        <LoginCard
            title="Welcome Back :)" subtitle="Hello! Please enter details."
            class="mx-2 mt-[5rem] flex h-fit w-full flex-col items-center py-20 sm:w-9/12 md:w-7/12 lg:w-5/12 xl:w-4/12">

            <!--        User input section-->
            <div class="mt-4 w-11/12">
                <div class="flex items-center justify-center">
                    <div class="mt-5 flex items-center rounded-l-md">
                        <UserIcon class="h-6 w-6"/>
                    </div>
                    <LoginTextInput
                        v-model="form.emailOrPhone"
                        class="w-full rounded-md pl-2 text-[0.6rem] font-light"
                        label="Email or Phone"
                        placeholder="Email or Phone"
                        required
                        :error="form.errors.emailOrPhone"
                    />
                </div>
            </div>

            <!--      Password input section-->
            <div class="mt-4 w-11/12">
                <div class="flex items-center justify-center">
                    <div class="mt-10 flex items-center rounded-l-md">
                        <KeyIcon class="h-6 w-6"/>
                    </div>
                    <LoginTextInput
                        v-model="form.password"
                        class="h-8 w-full rounded-md pl-2 text-[0.6rem] font-light"
                        label="Password"
                        placeholder="*********"
                        required
                        type="password"
                        :error="form.errors.password"
                    />
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
                        <a class="font-bold" href="/forgot-password">
                            Forgot Password?
                        </a>
                    </div>
                </div>

                <!--      Login and signup button section-->
                <LoginPrimaryButton
                    class="my-4 w-full"
                    title="Login"
                    @click="submit"
                />
                <div class="text-xs font-light">
                    Don't have an account?
                    <a class="text-dark-50 cursor-pointer font-bold"> Sign up </a>
                </div>
            </div>
        </LoginCard>
        <div
            class="mb-6 hidden items-center justify-center lg:my-0 lg:mr-4 lg:flex xl:mr-1"
        >
            <div>
                <div class="flex justify-center text-3xl font-light italic">
                    School Name
                </div>
                <img alt="place holder" src="/assets/educator.svg"/>
            </div>
        </div>
    </div>
</template>

<script setup>
import {useForm} from "@inertiajs/vue3";
import LoginTextInput from "@/Components/TextInput.vue";
import LoginPrimaryButton from "@/Components/PrimaryButton.vue";
import {KeyIcon, UserIcon} from "@heroicons/vue/24/outline";
import LoginCard from "@/Components/Card.vue";

const form = useForm({
    emailOrPhone: "",
    password: "",
});

// Submit form
const submit = () => {
    form.post("/login", {
        onFinish: () => {
            console.log("Login finished");
        },
        onSuccess: () => {
            console.log("Success");
        },
        onError: () => {
            console.log("Error");
        },
    });
};
</script>

<style scoped></style>
