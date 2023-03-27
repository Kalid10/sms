<template>
    <div class="mt-20 flex h-full items-center justify-center">
        <ForgotCard v-if="showForgotPassword" title="Forgot Password" subtitle="" class="w-full md:w-full">
            <ForgotTextInput
                v-model="form.emailOrPhone"
                label="Email or Phone"
                placeholder="jon@gmail.com / 0911.."
            />
            <div class="flex items-center justify-between">
                <ForgotPrimaryButton
                    title="Submit"
                    @click="confirmCode"
                >
                    Submit
                </ForgotPrimaryButton>
                <ForgotSecondaryButton
                    title="Back to Login"
                    @click="loginUrl"/>
            </div>
        </ForgotCard>


        <ForgotCard
            v-if="showConfirmCode"
            title="Account recovery"
            subtitle="Confirm the phone number you provided in your security settings: ••• ••• ••74"
            class="w-full md:w-full">
            <ForgotTextInput
                v-model="confirmationForm.confirmationCode"
                label="Confirmation Code"
                placeholder="****"/>
            <div class="flex items-center justify-between">
                <ForgotPrimaryButton
                    title="Submit"
                    @click="forgotPassword"
                >
                    Submit
                </ForgotPrimaryButton>
                <ForgotSecondaryButton
                    title="Back to Login"
                    @click="loginUrl"/>
            </div>
        </ForgotCard>


        <ForgotCard v-if="showResetPassword" title="Reset Password" subtitle="" class="w-full md:w-full">
            <div class="mb-4">
                <ForgotTextInput
                    v-model="resetForm.password"
                    label="Password"
                    placeholder="*********"
                    type="password"
                    required
                />
            </div>
            <div class="mb-4">
                <ForgotTextInput
                    v-model="resetForm.password_confirmation"
                    label="Confirm Password"
                    placeholder="*********"
                    type="password"
                    required/>
            </div>
            <div class="flex items-center justify-between">
                <ForgotPrimaryButton
                    title="Reset Password"
                    @click="loginUrl"
                />
                <ForgotSecondaryButton
                    title="Cancel"
                    @click="resetPassword"
                />
            </div>
        </ForgotCard>

    </div>
</template>

<script setup>
import {ref} from 'vue';
import ForgotTextInput from "@/Components/TextInput.vue";
import ForgotSecondaryButton from "@/Components/SecondaryButton.vue";
import ForgotPrimaryButton from "@/Components/PrimaryButton.vue";
import {router, useForm} from "@inertiajs/vue3";
import ForgotCard from "@/Components/Card.vue";

const showForgotPassword = ref(true);
const showResetPassword = ref(false);
const showConfirmCode = ref(false);

function confirmCode() {
    // Logic for confirming code goes here
    // Once the code is confirmed, show the reset password card
    showForgotPassword.value = false;
    showResetPassword.value = false;
    showConfirmCode.value = true;
}

function resetPassword() {
    // Logic for resetting password goes here
    // Once the password is reset, show the forgot password card again
    showForgotPassword.value = true;
    showResetPassword.value = false;
    showConfirmCode.value = false;
}

function forgotPassword() {
    // Logic for sending password reset email goes here
    // Once the email is sent, show the reset password card
    showForgotPassword.value = false;
    showResetPassword.value = true;
    showConfirmCode.value = false;
}

const loginUrl = () => {
    return router.get('/login');
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
