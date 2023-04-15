<template>
    <div class="grid-rows-12 grid sm:grid-cols-12">
        <div class="col-span-3 mb-6 flex shrink-0 flex-col md:mb-0 md:w-full">
            <Heading value="Profile" />
            <Heading
                value="Update your personal information and make sure your profile accurately reflects who you are."
                size="sm"
                class="text-xs font-normal text-gray-500"
            />
        </div>
        <div class="col-span-8">
            <div class="w-full max-w-4xl rounded-lg bg-white">
                <UserFormElement
                    class="shadow-none"
                    @submit="submitProfileForm"
                >
                    <UserTextInput
                        v-model="profileForm.name"
                        :placeholder="user.name"
                        label="Name"
                        :error="profileForm.errors.name"
                    />

                    <div class="flex gap-3">
                        <UserTextInput
                            v-model="profileForm.email"
                            class="w-full"
                            label="Email"
                            type="email"
                            :placeholder="user.email"
                            :error="profileForm.errors.email"
                            required
                        />

                        <UserTextInput
                            v-model="profileForm.username"
                            class="w-full"
                            :placeholder="user.username"
                            :error="profileForm.errors.username"
                            label="User Name"
                            required
                        />
                    </div>
                    <div class="flex gap-3">
                        <UserTextInput
                            v-model="profileForm.phone_number"
                            class="w-full"
                            label="Phone Number"
                            :placeholder="user.phone_number"
                            :error="profileForm.errors.phone_number"
                            required
                        />
                        <UserSelectInput
                            v-model="profileForm.gender"
                            class="w-full cursor-pointer"
                            :options="genderOptions"
                            placeholder="select gender"
                            label="Gender"
                            required
                        />
                    </div>
                </UserFormElement>
            </div>
        </div>
    </div>
    <div class="grid-rows-12 grid pt-5 sm:grid-cols-12">
        <div class="col-span-3 mb-6 flex shrink-0 flex-col md:mb-0 md:w-full">
            <Heading value="Password" />
            <Heading
                value="Stay ahead of potential security threats by updating your password now."
                size="sm"
                class="text-xs font-normal text-gray-500"
            />
        </div>
        <div class="col-span-8">
            <div class="w-full max-w-4xl rounded-lg bg-white">
                <UserFormElement
                    class="shadow-none"
                    @submit="submitPasswordForm"
                >
                    <UserTextInput
                        v-model="passwordForm.current_password"
                        label="Current Password"
                        type="password"
                        :error="passwordForm.errors.current_password"
                        required
                    />

                    <UserTextInput
                        v-model="passwordForm.password"
                        label="New Password"
                        type="password"
                        :error="passwordForm.errors.password"
                        required
                    />

                    <UserTextInput
                        v-model="passwordForm.password_confirmation"
                        label="Confirm Password"
                        type="password"
                        :error="passwordForm.errors.password_confirmation"
                        required
                    />
                </UserFormElement>
            </div>
        </div>
    </div>
</template>
<script setup>
import UserFormElement from "@/Components/FormElement.vue";
import UserTextInput from "@/Components/TextInput.vue";
import Heading from "@/Components/Heading.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import UserSelectInput from "@/Components/SelectInput.vue";

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
