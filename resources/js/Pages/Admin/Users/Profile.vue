<template>
    <div class="flex h-full w-full items-center justify-evenly bg-gray-100">
        <div
            class="flex h-5/6 w-7/12 flex-col items-center justify-between rounded-lg bg-white px-12 py-3"
        >
            <!--            Profile -->
            <div
                class="my-2 flex w-full flex-col items-center justify-center space-y-6"
            >
                <div
                    class="flex w-full shrink-0 flex-col justify-center space-y-2"
                >
                    <Heading value="Profile" />
                    <Heading
                        value="Update your personal information and make sure your profile accurately reflects who you are."
                        size="sm"
                        class="text-xs !font-light text-gray-500"
                    />
                </div>
                <div class="flex w-full justify-center">
                    <div class="w-full space-y-4 rounded-lg">
                        <UserTextInput
                            v-model="profileForm.name"
                            :placeholder="user.name"
                            label="Name"
                            :error="profileForm.errors.name"
                        />

                        <div class="flex justify-between">
                            <UserTextInput
                                v-model="profileForm.email"
                                class="w-5/12"
                                label="Email"
                                type="Email"
                                :placeholder="user.email"
                                :error="profileForm.errors.email"
                                required
                            />

                            <UserTextInput
                                v-model="profileForm.username"
                                class="w-5/12"
                                :placeholder="user.username || 'Johndoe'"
                                :error="profileForm.errors.username"
                                label="User Name"
                                required
                            />
                        </div>
                        <div class="flex justify-between">
                            <UserTextInput
                                v-model="profileForm.phone_number"
                                class="w-5/12"
                                label="Phone Number"
                                :placeholder="
                                    user.phone_number || '09123456789'
                                "
                                :error="profileForm.errors.phone_number"
                                required
                            />
                            <UserSelectInput
                                v-model="profileForm.gender"
                                class="w-5/12 cursor-pointer"
                                :options="genderOptions"
                                placeholder="select gender"
                                label="Gender"
                                required
                            />
                        </div>
                        <div class="flex justify-end py-3">
                            <PrimaryButton
                                title="Update Profile"
                                @click="submitProfileForm"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="h-[0.01rem] min-w-full bg-zinc-200/80"></div>
            <!--            Password-->
            <div class="flex w-full flex-col space-y-6">
                <div
                    class="mb-6 flex shrink-0 flex-col space-y-2 md:mb-0 md:w-full"
                >
                    <Heading value="Password" />
                    <Heading
                        value="Stay ahead of potential security threats by updating your password now."
                        size="sm"
                        class="text-xs !font-light text-gray-500"
                    />
                </div>
                <div class="flex w-full justify-center">
                    <div class="w-full space-y-4 rounded-lg">
                        <UserTextInput
                            v-model="passwordForm.current_password"
                            label="Current Password"
                            type="password"
                            :error="passwordForm.errors.current_password"
                            placeholder="Enter your current password"
                            required
                        />

                        <UserTextInput
                            v-model="passwordForm.password"
                            label="New Password"
                            type="password"
                            :error="passwordForm.errors.password"
                            placeholder="Enter your new password"
                            required
                        />

                        <UserTextInput
                            v-model="passwordForm.password_confirmation"
                            label="Confirm Password"
                            type="password"
                            :error="passwordForm.errors.password_confirmation"
                            placeholder="Confirm your new password"
                            required
                        />
                        <div class="flex justify-end py-3">
                            <PrimaryButton
                                title="Update Password"
                                @click="submitPasswordForm"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="hidden h-full w-4/12 flex-col items-center justify-center space-y-2 text-center lg:flex"
        >
            <div>
                <h1
                    class="w-full text-3xl font-extrabold leading-none lg:text-6xl"
                >
                    <span class="w-full">Hello 👋🏼 {{ user.name }}</span>
                </h1>

                <h3 class="py-1 font-light text-gray-500">
                    Welcome to your profile page, where you can view and update
                    your personal information and security settings. Enjoy your
                    journey with us!
                </h3>
            </div>

            <div class="h-3/6 min-w-full"></div>
        </div>
    </div>
</template>
<script setup>
import UserTextInput from "@/Components/TextInput.vue";
import Heading from "@/Components/Heading.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import UserSelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const genderOptions = [
    { value: "male", label: "Male" },
    { value: "female", label: "Female" },
];

const user = computed(() => usePage().props.auth.user);

const success = computed(() => usePage().props.flash.success);

const profileForm = useForm({
    id: user.value.id,
    name: user.value.name,
    email: user.value.email,
    username: user.value.username,
    phone_number: user.value.phone_number,
    gender: user.value.gender,
});

// Submit profile form
const submitProfileForm = () => {
    profileForm.post(route("user.update.profile"));
};

const passwordForm = useForm({
    id: user.value.id,
    current_password: "",
    password: "",
    password_confirmation: "",
});

// Submit password form
const submitPasswordForm = () => {
    passwordForm.post("/user/update-password/", {
        onFinish: () => passwordForm.reset(),
        onSuccess: () => {
            passwordForm.reset();
        },
    });
};
</script>
