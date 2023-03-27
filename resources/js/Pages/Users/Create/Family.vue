<template>
    <div class="grid-rows-12 grid sm:grid-cols-12">
        <!--        Handle success message-->
        <div v-if="success" class="flex justify-end text-sm text-green-500">
            {{ success }}
        </div>

        <div class="col-span-3 mb-6 flex shrink-0 flex-col md:mb-0 md:w-full">
            <Heading
                value="Register new
                    Student and parents"/>
            <Heading
                value="Register students and parents to efficiently manage academic progress, track achievements, and
                facilitate communications."
                size="sm" class="font-normal text-gray-500"/>
        </div>
        <div class="col-span-8">
            <div class="w-full max-w-4xl rounded-lg bg-white">

                <FamilyFormElement title="Guardian's form" @submit="submit"
                >
                    <div class="flex gap-3">

                        <FamilyTextInput
                            v-model="form.name" class="w-full" label="Student's name"
                            placeholder="name" :error="form.errors.name" required/>

                    </div>
                    <FamilyTextInput
                        v-model="form.email" type="email" class="w-full" label="Student's email"
                        placeholder="email" :error="form.errors.email" required/>
                    <div class="flex gap-3">

                        <FamilyTextInput
                            v-model="form.guardian_name" class="w-full" label="Guardian's name"
                            placeholder="name" :error="form.errors.guardian_name" required/>
                        <FamilyTextInput
                            v-model="form.guardian_phone_number"
                            class="w-full" label="Guardian's phone number" placeholder="phone number"
                            :error="form.errors.guardian_phone_number"
                            required/>

                    </div>
                    <FamilyTextInput
                        v-model="form.guardian_email" label="Guardian's email" placeholder="email"
                        :error="form.errors.guardian_email"
                        required/>
                </FamilyFormElement>

            </div>
        </div>
    </div>


    <div class="grid-rows-12 grid sm:grid-cols-12">
        <div class="col-span-3 mb-6 flex shrink-0 flex-col md:mb-0 md:w-full">
            <Heading
                value="Register student and parents in bulk"/>
            <Heading
                value="only upload an excel or csv file."
                size="sm" class="font-normal text-gray-500"/>
        </div>
        <div class="col-span-8">
            <div class="relative w-full max-w-4xl flex-col rounded-lg bg-white">

                <FamilyFileInput max-file-size="10000000" @file-uploaded="handleFileUploaded"/>

                <div class="absolute right-0">
                    <FamilyPrimaryButton title="Submit"/>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import FamilyFormElement from "@/Components/FormElement.vue"
import FamilyTextInput from "@/Components/TextInput.vue"
import FamilyFileInput from "@/Components/FileInput.vue"
import Heading from "@/Components/Heading.vue"
import {useForm, usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import FamilyPrimaryButton from "@/Components/PrimaryButton.vue";

defineEmits(['file-uploaded']);

const user = computed(() => usePage().props.auth.user);

const success = computed(() => usePage().props.flash.success);

const handleFileUploaded = (file) => {
    // Todo: Remove this console.log when notification is implemented
    console.log("File uploaded:", file);
};

const form = useForm({
    name: '',
    email: '',
    type: 'student',
    guardian_name: '',
    guardian_email: '',
    guardian_phone_number: '',
})

const bulkForm = useForm({
    file: '',
})

const submit = () => {
    form.post(route('register.family'),
        {
            onSuccess: () => {
                console.log("Success");
            },
            onError: (error) => {
                console.log("Error")
                console.log(error)
            }
        });
}
</script>
