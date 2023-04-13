<template>
    <div class="flex w-full flex-row">
        <div class="flex h-screen w-full justify-center bg-gray-50 md:w-1/2">
            <div class="mx-auto px-4 lg:gap-8 lg:py-16 xl:gap-0">
                <div class="mr-auto place-self-center lg:col-span-7">
                    <h1
                        class="mb-8 flex max-w-2xl justify-center text-4xl leading-none md:text-5xl xl:text-5xl"
                    >
                        Rigel SMS
                    </h1>
                    <div
                        class="w-full bg-gray-50 p-6 sm:max-w-md sm:p-8 md:mt-0"
                    >
                        <h2
                            class="mb-1 text-xl font-bold leading-tight tracking-tight text-gray-900 dark:text-white md:text-2xl"
                        >
                            Sign Up
                        </h2>
                        <form class="mt-4 space-y-6 md:space-y-5 lg:mt-5">
                            <div>
                                <label
                                    for="email"
                                    class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                                    >Your email</label
                                >
                                <input
                                    v-model="form.emailOrPhone"
                                    placeholder="ekele@gmail.com"
                                    type="email"
                                    class="focus:ring-primary-600 focus:border-primary-600 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm"
                                />
                            </div>
                            <div>
                                <label
                                    for="password"
                                    class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                                    >Password</label
                                >
                                <input
                                    v-model="form.current_password"
                                    type="password"
                                    placeholder="••••••••"
                                    class="focus:ring-primary-600 focus:border-primary-600 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm"
                                    required=""
                                />
                            </div>
                            <div>
                                <label
                                    for="password"
                                    class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                                    >New Password</label
                                >
                                <input
                                    v-model="form.password"
                                    type="password"
                                    placeholder="••••••••"
                                    class="focus:ring-primary-600 focus:border-primary-600 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm"
                                    required=""
                                />
                            </div>
                            <div>
                                <label
                                    for="confirm-password"
                                    class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                                    >Confirm password</label
                                >
                                <input
                                    v-model="form.password_confirmation"
                                    type="password"
                                    placeholder="••••••••"
                                    class="focus:ring-primary-600 focus:border-primary-600 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 sm:text-sm"
                                    required=""
                                />
                            </div>

                            <button
                                type="button"
                                class="w-full rounded-lg bg-black px-5 py-3 text-center text-sm font-medium text-white"
                                @click="submitForm"
                            >
                                Sign up
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="hidden h-screen w-1/2 flex-col items-center bg-gray-50 md:flex"
        >
            <div class="bg-gray-50">
                <div
                    class="mx-auto flex max-w-screen-xl flex-col px-4 py-8 lg:gap-8 lg:py-16 xl:gap-0"
                >
                    <div class="mr-auto place-self-center lg:col-span-7">
                        <h1
                            class="mb-4 max-w-2xl text-4xl font-extrabold leading-none tracking-tight dark:text-white md:text-5xl xl:text-6xl"
                        >
                            Empowering Education Through Innovation
                        </h1>
                        <p
                            class="mb-6 max-w-2xl font-light text-gray-500 dark:text-gray-400 md:text-lg lg:mb-8 lg:text-xl"
                        >
                            Discover Rigel, the state-of-the-art school
                            management system that streamlines administrative
                            tasks, fosters collaboration, and enhances the
                            educational experience for all. Join us in our
                            mission to revolutionize education with Rigel's
                            seamless, user-friendly platform.
                        </p>
                    </div>
                    <div class="hidden lg:col-span-5 lg:mt-0 lg:flex">
                        <img
                            alt="place holder"
                            src="/assets/back-to-school.svg"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { router, useForm } from "@inertiajs/vue3";

const form = useForm({
    emailOrPhone: "",
    current_password: "",
    password: "",
    password_confirmation: "",
});

function gettingStartedUrl() {
    router.get("getting-started/");
}

const submitForm = () => {
    form.post("/sign-up/", {
        onSuccess: () => {
            gettingStartedUrl();
        },
    });
};
</script>
