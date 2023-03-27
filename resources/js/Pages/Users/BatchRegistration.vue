<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <Heading value="Batch Registration"></Heading>
        <h2 class="mb-4 text-2xl font-bold"></h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">

            <div v-for="grade in grades" :key="grade.id" class="m:mb-0 rounded-md bg-white bg-opacity-20 backdrop-blur-lg">
                <div class="flex flex-col">
                    <div class="flex items-center">
                        <div :class="{ 'grayscale-0': grade.isChecked }"  class="w-full cursor-pointer rounded-xl bg-black  from-sky-400 to-sky-200 shadow-lg grayscale transition-all duration-200 hover:grayscale-0 sm:shadow-none">
                            <div class="relative h-44 w-full">
                                <div class="m-2 rounded-xl p-2 text-left  font-bold text-white">
                                    <div class=" flex flex-row justify-between">
                                        <p class="text-bold text-lg text-white">{{grade.name}}</p>
                                        <Checkbox :model-value="grade.isChecked" class="h-6 w-6 rounded-lg"></Checkbox>
                                    </div>
                                </div>
                                <div class=" absolute bottom-3 left-3 w-1/3 rounded align-bottom ">
                                    <TextInput :disabled="!grade.isChecked" label="Sections:" class="w-fit" :placeholder="grade.noOfSection"></TextInput>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <div class="flex justify-center md:justify-end">
            <PrimaryButton title="Submit" @click="addSection"></PrimaryButton>
        </div>
    </div>
</template>


<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Heading from "@/Components/Heading.vue";
import Checkbox from '@/Components/Checkbox.vue'
import TextInput from '@/Components/TextInput.vue'
import {useForm} from "@inertiajs/vue3";
import { ref } from 'vue'

//
// const formData = useForm(
//
// )

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



</script>
