<template>
    <div class="grid-rows-12 grid h-screen p-6 sm:grid-cols-12 md:w-full">
        <div class="col-span-3 mb-6 flex shrink-0 flex-col md:mb-0">
            <Heading value="Register new teacher" />
            <Heading
                value="Fill in the information required."
                size="sm"
                class="text-xs !font-light text-gray-500"
            />
        </div>
        <div class="col-span-8">
            <div class="w-full max-w-4xl rounded-lg bg-white">
                <TeacherFormElement title="Register Teacher" @submit="submit">
                    <TeacherTextInput
                        v-model="form.name"
                        label="Name"
                        placeholder="Full name of new teacher"
                        :error="form.errors.name"
                        required
                    />

                    <div class="flex gap-3">
                        <TeacherTextInput
                            v-model="form.username"
                            class="w-full"
                            label="User Name"
                            :error="form.errors.username"
                            placeholder="username"
                            required
                        />

                        <TeacherTextInput
                            v-model="form.phone_number"
                            class="w-full"
                            label="Phone number"
                            placeholder="phone number"
                            :error="form.errors.phone_number"
                            required
                        />
                    </div>

                    <div class="flex gap-3">
                        <TeacherTextInput
                            v-model="form.email"
                            class="w-full"
                            label="Email"
                            type="email"
                            placeholder="email"
                            :error="form.errors.email"
                            required
                        />
                        <TeacherSelectInput
                            v-model="form.gender"
                            class="w-full cursor-pointer"
                            label="Gender"
                            placeholder="Select Gender"
                            :error="form.errors.gender"
                            :options="genderOptions"
                            required
                        />
                    </div>
                </TeacherFormElement>
            </div>
        </div>
    </div>
</template>

<script setup>
import TeacherFormElement from "@/Components/FormElement.vue";
import TeacherTextInput from "@/Components/TextInput.vue";
import TeacherSelectInput from "@/Components/SelectInput.vue";
import Heading from "@/Components/Heading.vue";
import { useForm } from "@inertiajs/vue3";

const genderOptions = [
    { value: "male", label: "Male" },
    { value: "female", label: "Female" },
];

const form = useForm({
    name: "",
    type: "teacher",
    email: "",
    phone_number: "",
    username: "",
    gender: "",
});

const submit = () => {
    form.post(route("register.admin"));
};
</script>
