<template>
    <div class="flex flex-col p-10">

        <!--        handle error message-->
        <div v-if="profileForm.errors.id" class="flex justify-end text-[0.55rem] text-red-600">
            {{ profileForm.errors.id }}
        </div>
        <div v-if="passwordForm.errors.id" class="flex justify-end text-[0.55rem] text-red-600">
            {{ passwordForm.errors.id }}
        </div>

        <!--        handle success message-->
        <div v-if="success" class="flex justify-end text-sm text-green-500">
            {{ success }}
        </div>

        <div>
            <h2 class="title-font mb-2 text-lg font-medium">
                Profile
            </h2>
            <UserFormElement
                title="Update your profile"
                @submit="submitProfileForm"
            >
                <UserTextInput v-model="profileForm.name" :placeholder=user.name label="Name"/>
                <div
                    v-if="profileForm.errors.name"
                    class=" text-sm  text-red-600"
                >
                    *{{ profileForm.errors.name }}
                </div>
                <UserTextInput v-model="profileForm.email" label="Email" :placeholder=user.email required/>
                <div
                    v-if="profileForm.errors.email"
                    class=" text-[0.55rem] text-red-600"
                >
                    *{{ profileForm.errors.email }}
                </div>
                <UserTextInput v-model="profileForm.username" :placeholder=user.username label="User Name" required/>
                <div
                    v-if="profileForm.errors.username"
                    class="text-[0.55rem] text-red-600"
                >
                    *{{ profileForm.errors.username }}
                </div>
                <UserTextInput
                    v-model="profileForm.phone_number" label="Phone Number" :placeholder=user.phone_number required/>
                <div
                    v-if="profileForm.errors.phone_number"
                    class="text-[0.55rem] text-red-600"
                >
                    *{{ profileForm.errors.phone_number }}
                </div>
            </UserFormElement>
        </div>
        <div class="py-5">
            <h2 class="title-font mb-2 text-lg font-medium">
                Password Update </h2>
            <UserFormElement
                label="Password" title="Update your password"
                @submit="submitPasswordForm">
                <UserTextInput
                    v-model="passwordForm.current_password" label="Current Password" type="password"
                    required/>
                <div
                    v-if="passwordForm.errors.current_password"
                    class="text-[0.55rem] text-red-600"
                >
                    *{{ passwordForm.errors.current_password }}
                </div>
                <UserTextInput v-model="passwordForm.password" label="New Password" type="password" required/>
                <div
                    v-if="passwordForm.errors.password"
                    class="text-[0.55rem] text-red-600"
                >
                    *{{ passwordForm.errors.password }}
                </div>
                <UserTextInput
                    v-model="passwordForm.password_confirmation" label="Confirm Password" type="password"
                    required/>
                <div
                    v-if="passwordForm.errors.password_confirmation"
                    class="text-[0.55rem] text-red-600"
                >
                    *{{ passwordForm.errors.password_confirmation }}
                </div>
            </UserFormElement>
        </div>
    </div>
</template>
<script setup>
import UserFormElement from "@/Components/FormElement.vue";
import UserTextInput from "@/Components/TextInput.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import {computed} from "vue";

const user = computed(() => usePage().props.auth.user);

const success = computed(() => usePage().props.flash.success);

const profileForm = useForm({
    id: user.value.id,
    name: user.value.name,
    email: user.value.email,
    username: user.value.username,
    phone_number: user.value.phone_number,
});


const submitProfileForm = () => {
    profileForm.post(route('user.update.profile'));
};

const passwordForm = useForm({
    id: user.value.id,
    current_password: '',
    password: '',
    password_confirmation: '',
});

const submitPasswordForm = () => {
    passwordForm.post(route('user.update.password'), {
        onFinish: () => passwordForm.reset(),
    });
}
</script>
