<template>
    <div class="flex justify-center">
        <div class="w-full max-w-4xl rounded-lg bg-white">
            <div class="flex flex-row">

                <StudentPrimaryButton class="h-8 w-12" @click="$emit('close')">
                    <ArrowUturnLeftIcon class="h-4 w-4"/>
                </StudentPrimaryButton>
                <h2 class="mb-6 ml-8 text-lg font-medium text-gray-900">Register new
                    Student</h2>
            </div>
            <div class="h-4/5 overflow-y-scroll">
                <StudentFormElement
                    subtitle="Fill in the information required about the new parent"
                    title="Register new Student"
                >
                    <StudentTextInput label="Name" placeholder="Full name of new student" required/>
                    <StudentTextInput label="Level (Grade)" placeholder="Grade of the student" required/>
                </StudentFormElement>
                <div class="mt-8">
                    <StudentFormElement title="Register students by mass" subtitle="only upload an excel or csv file">
                        <StudentFileInput @file-uploaded="onFileUploaded"/>
                    </StudentFormElement>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import StudentFormElement from "@/Components/FormElement.vue"
import StudentTextInput from "@/Components/TextInput.vue"
import StudentFileInput from "@/Components/FileInput.vue"
import StudentPrimaryButton from "@/Components/PrimaryButton.vue";
import {ArrowUturnLeftIcon} from "@heroicons/vue/20/solid";

defineEmits(['file-uploaded']);

const onFileUploaded = (file) => {
    console.log("File uploaded:", file);
};
</script>
