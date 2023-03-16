<template>
    <div class="flex h-full items-center justify-center">
        <div class="h-4/6 w-5/12">
            <div v-if="showForgotPassword">
                <div class="rounded bg-white px-8 py-6 shadow-md">
                    <h2 class="mb-4 text-lg font-medium">Forgot Password</h2>
                    <div class="mb-4">
                        <text-input
                            v-model="form.emailOrPhone"
                            label="Email or Phone"
                            placeholder="jon@gmail.com / 0911.."
                        />
                    </div>
                    <div class="flex items-center justify-between">
                        <primary-button
                            :click="confirmCode"
                            title="Submit"
                        >
                            Submit
                        </primary-button>
                        <secondary-button
                            :click="loginUrl"
                            title="Back to Login"/>
                    </div>
                </div>
            </div>
            <div v-if="showConfirmCode">
                <div class="rounded bg-white px-8 py-6 shadow-md">
                    <h2 class="mb-4 text-lg font-medium">Account recovery</h2>
                    <p>Confirm the phone number you provided in your security settings: ••• ••• ••74</p>
                    <div class="mb-4">
                        <text-input
                            v-model="confirmationForm.confirmationCode"
                            label="Confirmation Code"
                            placeholder="****"/>
                    </div>
                    <div class="flex items-center justify-between">
                        <primary-button
                            :click="forgotPassword"
                            title="Submit"
                        >
                            Submit
                        </primary-button>
                        <!--                        <secondary-button-->
                        <!--                            title="Back to Login"-->
                        <!--                            @click="loginUrl"/>-->
                    </div>
                </div>
            </div>
            <div v-if="showResetPassword">
                <div class="rounded bg-white px-8 py-6 shadow-md">
                    <h2 class="mb-4 text-lg font-medium">Reset Password</h2>
                    <div class="mb-4">
                        <text-input
                            v-model="resetForm.password"
                            label="Password"
                            placeholder="*********"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <text-input
                            v-model="resetForm.password_confirmation"
                            label="Confirm Password"
                            placeholder="*********"
                            required/>
                    </div>
                    <div class="flex items-center justify-between">
                        <primary-button
                            :click="loginUrl"
                            title="Reset Password"
                        />
                        <secondary-button
                            :click="resetPassword"
                            title="Cancel"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {Inertia} from "@inertiajs/inertia";
import {useForm} from "@inertiajs/vue3";

const showForgotPassword = ref(true);
const showResetPassword = ref(false);
const showConfirmCode = ref(false);

function confirmCode() {
    // logic for confirming code goes here
    // once the code is confirmed, show the reset password card
    showForgotPassword.value = false;
    showResetPassword.value = false;
    showConfirmCode.value = true;
}

function resetPassword() {
    // logic for resetting password goes here
    // once the password is reset, show the forgot password card again
    showForgotPassword.value = true;
    showResetPassword.value = false;
    showConfirmCode.value = false;
}

function forgotPassword() {
    // logic for sending password reset email goes here
    // once the email is sent, show the reset password card
    showForgotPassword.value = false;
    showResetPassword.value = true;
    showConfirmCode.value = false;
}

function loginUrl() {
    return Inertia.get('/login');
}

const form = useForm({
    emailOrPhone: null,
    password: null,
});

const confirmationForm = useForm({
    confirmationCode: null,
});
const resetForm = useForm({
    password: null,
    password_confirmation: null,
});
</script>
