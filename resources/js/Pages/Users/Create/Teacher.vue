<template>
    <div class="flex justify-center pb-5">
        <div class="w-full max-w-4xl rounded-lg bg-white">
            <div class="flex flex-row">
                <TeacherPrimaryButton class="h-8 w-12" @click="$emit('close')">
                    <ArrowUturnLeftIcon class="h-4 w-4"/>
                </TeacherPrimaryButton>
                <h2 class="mb-6 ml-8 text-lg font-medium text-gray-900">Register new teacher</h2>
            </div>

            <TeacherFormElement
                subtitle="Fill in the information required about the new teacher"
                title="Register new teacher"
            >
                <TeacherTextInput label="Name" placeholder="Full name of new teacher" required/>
                <TeacherTextInput label="Assign levels" required/>
            </TeacherFormElement>
        </div>
    </div>
</template>

<script setup>
import TeacherFormElement from "@/Components/FormElement.vue";
import TeacherTextInput from "@/Components/TextInput.vue";
import TeacherPrimaryButton from "@/Components/PrimaryButton.vue";
import {ArrowUturnLeftIcon} from "@heroicons/vue/20/solid";</script>
