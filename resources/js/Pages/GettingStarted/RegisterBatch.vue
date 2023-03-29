<template>
    <FormElement
    @submit="handleSubmit">
    <div class="px-4 sm:px-6 lg:px-8">
        <Heading value="Batch Registration"></Heading>
        <h2 class="mb-4 text-2xl font-bold"></h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">

            <div v-for="grade in grades" :key="grade.id" class="m:mb-0 rounded-md bg-white/20 backdrop-blur-lg">
                <div class="flex flex-col">
                    <div class="flex items-center">
                        <div :class="{ 'grayscale-0': grade.isChecked }"  class=" w-full cursor-pointer rounded-xl border bg-white from-sky-400 to-sky-200 shadow-lg grayscale transition-all duration-200 hover:grayscale-0 sm:shadow-none">
                            <div class="relative h-44 w-full">
                                <div class="m-2 rounded-xl p-2 text-left  font-bold">
                                    <div class=" flex flex-row justify-between">
                                        <p class="text-bold text-lg ">{{grade.name}}</p>
                                        <Checkbox :model-value="grade.isChecked" class="h-6 w-6 !rounded-full"></Checkbox>
                                    </div>
                                </div>
                                <div class=" absolute bottom-3 left-3  rounded align-bottom">
                                    <div class="flex align-bottom">
                                        <p class=" text-sm font-bold text-gray-700">Sections: <span v-modal="formData.level" contenteditable class="m-2 justify-center border text-xl font-bold underline">{{grade.noOfSection}}</span></p>
                                        <TextInput
                                            v-modal="formData.level"
                                            :disabled="!grade.isChecked"
                                            class="h-fit w-fit pl-7 text-white outline-0 placeholder:text-white"
                                            :placeholder="grade.noOfSection"
                                        ></TextInput>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
    </FormElement>
</template>


<script setup>
import FormElement from "@/Components/FormElement.vue";
import Heading from "@/Components/Heading.vue";
import Checkbox from '@/Components/Checkbox.vue'
import TextInput from '@/Components/TextInput.vue'
import {useForm} from "@inertiajs/vue3";
import { ref } from 'vue'


const formData = useForm({
    level: 12,
    section: null,
});

const grades = ref([
    {id: 1, name: 'Grade 0ne',noOfSection:0, isChecked:true},
    {id: 2, name: 'Grade Two',noOfSection:0, isChecked:true},
    {id: 3, name: 'Grade Three',noOfSection:0, isChecked:true},
    {id: 4, name: 'Grade Four',noOfSection:0, isChecked:true},
    {id: 5, name: 'Grade Five',noOfSection:0, isChecked:true},
    {id: 6, name: 'Grade Six',noOfSection:0,  isChecked:true},
    {id: 7, name: 'Grade Seven',noOfSection:0,  isChecked:true},
    {id: 8, name: 'Grade Eight',noOfSection:0,  isChecked:true},
    {id: 9, name: 'Grade Nine',noOfSection:0,  isChecked:true},
    {id: 10, name: 'Grade Ten',noOfSection:0,  isChecked:true},
    {id: 11, name: 'Grade Eleven',noOfSection:0,  isChecked:true},
    {id: 12, name: 'Grade Twelve',noOfSection:0,  isChecked:true},
])
console.log(grades.value.isChecked)
function addSection(){
    alert(grades)
}
function handleSubmit(){
alert("hello")
}



</script>
