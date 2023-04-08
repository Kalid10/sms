<template>
    <div class="grid-rows-12 grid sm:grid-cols-12">
        <div class="col-span-3 mb-6 flex shrink-0 flex-col md:mb-0 md:w-full">
            <Heading
                value="Register an Admin"/>
            <Heading
                value="Fill in the information required."
                size="sm" class="text-xs font-light text-gray-500"/>
        </div>
        <div class="col-span-8">
            <div class="w-full max-w-4xl rounded-lg bg-white">

                <AdminFormElement
                    @submit="submit"
                >
                    <AdminTextInput
                        v-model="form.name" class="w-full" label="Name" placeholder="full name"
                        :error="form.errors.name" required/>

                    <div class="flex gap-3">
                        <AdminTextInput
                            v-model="form.email" class="w-full" label="Email" type="email" :error="form.errors.email"
                            placeholder="email" required/>
                        <AdminTextInput
                            v-model="form.phone_number" class="w-full" label="Phone number"
                            :error="form.errors.phone_number"
                            placeholder="phone number" required/>
                    </div>
                    <AdminTextInput
                        v-model="form.username" class="w-full" label="User Name" placeholder="username"
                        :error="form.errors.username"
                        required/>

                    <div class="flex gap-3">
                        <AdminTextInput
                            v-model="form.position" class="w-full" label="Position" placeholder="position"
                            :error="form.errors.position"
                            required/>
                        <AdminSelectInput
                            v-model="form.gender" class="w-full cursor-pointer" :options="genderOptions"
                            placeholder="select gender"
                            label="Gender"
                            required/>
                    </div>
                </AdminFormElement>
            </div>
        </div>
    </div>
</template>

<script setup>
import AdminFormElement from "@/Components/FormElement.vue";
import AdminTextInput from "@/Components/TextInput.vue";
import Heading from "@/Components/Heading.vue";
import {useForm} from "@inertiajs/vue3";
import AdminSelectInput from "@/Components/SelectInput.vue";

const genderOptions = [
    {value: 'male', label: 'Male'},
    {value: 'female', label: 'Female'},
]

const form = useForm({
    name: "",
    username: "",
    type: "admin",
    email: "",
    phone_number: "",
    position: "",
    gender: "",
});

const submit = () => {
    form.post(route('register.admin'), {
        onSuccess: () => {
            form.reset();
        }
    });
}
</script>
