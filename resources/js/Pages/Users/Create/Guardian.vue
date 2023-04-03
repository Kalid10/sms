<template>
    <div class="grid-rows-12 grid sm:grid-cols-12">
        <div class="col-span-3 mb-6 flex shrink-0 flex-col md:mb-0 md:w-full">
            <Heading
                value="Register new
                    Student and parents"/>
            <Heading
                value="Register students and parents to efficiently manage academic progress, track achievements, and
                facilitate communications."
                size="sm" class="text-xs font-light text-gray-500"/>
        </div>
        <div class="col-span-8">
            <div class="w-full max-w-4xl rounded-lg bg-white">

                <GuardianFormElement title="Guardian's form" @submit="submit"
                >
                    <div class="flex gap-3">

                        <GuardianTextInput
                            v-model="form.name" class="w-full" label="Student's name"
                            placeholder="name" :error="form.errors.name" required/>

                    </div>
                    <div class="flex gap-3">
                        <GuardianTextInput
                            v-model="form.email" type="email" class="w-full" label="Student's email"
                            placeholder="email" :error="form.errors.email" required/>
                        <GuardianTextInput
                            v-model="form.gender" class="w-full" label="Student's gender"
                            placeholder="gender" :error="form.errors.gender" required/>
                    </div>

                    <GuardianDatePicker v-model="form.date_of_birth" label="Student's date of birth"/>

                    <div class="flex gap-3">

                        <GuardianTextInput
                            v-model="form.guardian_name" class="w-full" label="Guardian's name"
                            placeholder="name" :error="form.errors.guardian_name" required/>
                        <GuardianTextInput
                            v-model="form.guardian_phone_number"
                            class="w-full" label="Guardian's phone number" type="number" placeholder="phone number"
                            :error="form.errors.guardian_phone_number"
                            required/>

                    </div>
                    <div class="flex gap-3">
                        <GuardianTextInput
                            v-model="form.guardian_email" type="email" class="w-full" label="Guardian's email"
                            placeholder="email"
                            :error="form.errors.guardian_email"
                            required/>
                        <GuardianTextInput
                            v-model="form.guardian_gender" class="w-full" label="Guardian's gender"
                            placeholder="gender" :error="form.errors.guardian_gender" required/>
                    </div>
                </GuardianFormElement>

            </div>
        </div>
    </div>


    <div class="grid-rows-12 grid sm:grid-cols-12">
        <div class="col-span-3 mb-6 flex shrink-0 flex-col md:mb-0 md:w-full">
            <Heading
                value="Register student and parents in bulk"/>
            <Heading
                value="only upload an excel or csv file."
                size="sm" class="text-xs font-light text-gray-500"/>
        </div>
        <div class="col-span-8">
            <div class="relative w-full max-w-4xl flex-col rounded-lg bg-white">

                <GuardianFileInput max-file-size="10000000" @file-uploaded="handleFileUploaded"/>

                <div class="absolute right-0">
                    <GuardianPrimaryButton title="Submit"/>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import GuardianFormElement from "@/Components/FormElement.vue"
import GuardianTextInput from "@/Components/TextInput.vue"
import GuardianFileInput from "@/Components/FileInput.vue"
import Heading from "@/Components/Heading.vue"
import {useForm} from "@inertiajs/vue3";
import GuardianPrimaryButton from "@/Components/PrimaryButton.vue";
import GuardianDatePicker from "@/Components/DatePicker.vue";

defineEmits(['file-uploaded']);

const handleFileUploaded = (file) => {
    // Todo: Remove this console.log when notification is implemented
    console.log("File uploaded:", file);
};

const form = useForm({
    name: '',
    email: '',
    gender: '',
    date_of_birth: null,
    type: 'student',
    guardian_name: '',
    guardian_email: '',
    guardian_phone_number: '',
    guardian_gender: '',
})

const bulkForm = useForm({
    file: '',
})

const submit = () => {
    form.post(route('register.guardian'));
}
</script>
