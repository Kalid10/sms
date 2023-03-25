<template>
    <div class="flex justify-center pb-5">
        <div class="w-full max-w-4xl rounded-lg bg-white">
            <div class="flex flex-row">
                <GuardianPrimaryButton class="h-8 w-12" @click="$emit('close')">
                    <ArrowUturnLeftIcon class="h-4 w-4"/>
                </GuardianPrimaryButton>

                <h2 class="mb-6 ml-8 text-lg font-medium text-gray-900">Register new Parent</h2>
            </div>

            <ParentFormElement
                subtitle="Fill in the information required about the new parent"
                title="Register new Parent"
            >
                <ParentTextInput label="Name" placeholder="Full name of new parent" required/>
                <ParentTextInput label="Child's Level (Grade)" placeholder="Grade of the child" required/>
            </ParentFormElement>
        </div>
    </div>
</template>

<script setup>
import ParentFormElement from "@/Components/FormElement.vue";
import ParentTextInput from "@/Components/TextInput.vue";
import GuardianPrimaryButton from "@/Components/PrimaryButton.vue";
import {ArrowUturnLeftIcon} from "@heroicons/vue/20/solid";</script>
