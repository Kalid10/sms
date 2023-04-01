<template>
    <div>
        <div>
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    <div v-for="subject in updatedSubjects" :key="subject.id" class="m:mb-0 rounded-md bg-white/20 backdrop-blur-lg">
                        <div class="flex flex-col">
                            <div class="flex justify-center">
                                <div class=" w-full cursor-pointer rounded-xl border bg-white shadow-lg hover:shadow-md sm:shadow-none">
                                    <div class=" flex h-44 w-full flex-col justify-center">
                                        <div class="absolute top-2 right-2 hover:text-red-500">
                                            <MinusCircleIcon class="h-6 w-6 stroke-[1]" @click="removeSubject(subject)" />
                                        </div>
                                        <div class="flex items-center justify-center pb-8">
                                            <LightBulbIcon class="h-9 w-9 "></LightBulbIcon>
                                        </div>
                                        <div class="  justify-center border-black text-center font-bold">
                                            <div class=" justify-center ">
                                                <p class="text-bold text-lg ">{{subject.name}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
<!--                    add new subject -->
                    <div class="m:mb-0 rounded-md bg-white/20 backdrop-blur-lg" @click="showModal=true">
                        <div class="flex flex-col">
                            <div class="flex items-center">
                                <div class=" w-full cursor-pointer rounded-xl border bg-white from-sky-400 to-sky-200 shadow-lg grayscale transition-all duration-200 hover:grayscale-0 sm:shadow-none">
                                    <div class="relative grid h-44 w-full place-items-center">
                                        <PlusCircleIcon class="h-6 w-6 stroke-[3]" />
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
            <AddSubjectModal v-if="showModal" view="true">
                <FormElement  title="Add new subject" subtitle="" class="relative" @submit="addNewSubject">
                    <XMarkIcon class="absolute top-0 right-0 m-2 h-7 w-7 cursor-pointer text-gray-500 transition-colors duration-300 ease-in-out hover:text-gray-900" @click="showModal=false"></XMarkIcon>
<!--                    I used @keydown... to prevent the default new line behavior, and to submit-->
                    <TextInput  v-model="formData.name" label="Subject Name" required @keydown.enter.prevent="addNewSubject"></TextInput>
                    <TextInput  v-model="formData.shortName" label="Short Name"  @keydown.enter.prevent="addNewSubject"></TextInput>
                    <p v-if="!formData.name" class="text-sm text-red-500">{{error}}</p>
                </FormElement>
            </AddSubjectModal>
        </div>
        <div class="flex justify-end">
            <PrimaryButton type="submit" class="w-1/20" @click="handleSubmit">Submit</PrimaryButton>
        </div>
    </div>
</template>


<script setup>
import {sbj} from "@/fake";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {computed, ref} from "vue"
import FormElement from "@/Components/FormElement.vue";
import {LightBulbIcon,PlusCircleIcon,MinusCircleIcon,XMarkIcon} from '@heroicons/vue/24/outline';
import AddSubjectModal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";
import {router, useForm} from "@inertiajs/vue3";

const error = ref()
const showModal =ref(false)
const subjects = computed(() => sbj);
const updatedSubjects = ref(subjects.value.map(subject => {
    return {
        ...subject,
        name: subject.name
    }
}))

function addNewSubject() {
    if (formData.name === "") {
        error.value = "Subject name is required.";
        return;
    }
    const newSubject = {
        // id: updatedSubjects.value.length + 1,
        name: formData.name,
    };
    updatedSubjects.value.push(newSubject);
    showModal.value = false;
    formData.reset();
}
//
// const shortName = computed(() => {
//     return name.value.slice(0, 3).toUpperCase();
// });
const formData = useForm({
    name: '',
    shortName: ''
})
function removeSubject(subject) {
    const index = updatedSubjects.value.indexOf(subject);
    updatedSubjects.value.splice(index, 1);
}

function handleSubmit() {
    router.post('/batches/subjects/assign', {
        batchesSubjects: updatedSubjects.value
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}
</script>
