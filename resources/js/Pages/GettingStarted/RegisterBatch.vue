<template>
    <div>
    <div class="px-4 sm:px-6 lg:px-8">
        <Heading value="Batch Registration"></Heading>
        <h2 class="mb-4 text-2xl font-bold"></h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            <div v-for="(level, index) in updatedLevels" :key="index" class="m:mb-0 rounded-md bg-white/20 backdrop-blur-lg">
                <div class="flex flex-col">
                    <div class="flex items-center">
                        <div :class="{ 'grayscale-0': level.isSelected }"  class=" w-full cursor-pointer rounded-xl border bg-white from-sky-400 to-sky-200 shadow-lg grayscale transition-all duration-200 hover:grayscale-0 sm:shadow-none">
                            <div class="relative h-44 w-full">
                                <div class="m-2 rounded-xl p-2 text-left  font-bold">
                                    <div class=" flex flex-row justify-between">
                                        <p class="text-bold text-lg ">{{level.name}}</p>
                                        <Checkbox v-model="level.isSelected" class="h-6 w-6 !rounded-full"></Checkbox>
                                    </div>
                                </div>
                                <div class=" absolute bottom-3 left-3  rounded align-bottom">
                                    <div class="flex align-bottom">
                                        <p class=" text-sm font-bold text-gray-700">Sections: </p>
                                        <TextInput
                                            v-model="level.no_of_sections"
                                            class="h-fit w-fit pl-7 outline-0"
                                            :placeholder="level.no_of_sections"
                                        ></TextInput>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
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
        <AddBatchModal v-if="showModal" :view="true" >
            <FormElement  title="Add new Level" class="relative" @submit="createLevel">
                <XMarkIcon class="absolute top-0 right-0 m-2 h-7 w-7 cursor-pointer text-gray-500 transition-colors duration-300 ease-in-out hover:text-gray-900" @click="showModal=false"></XMarkIcon>
                <TextInput v-model="levelForm.name" label="Level Name" required placeholder="Name Ex:2" :error="levelForm.errors.name"></TextInput>
                <p v-if="!levelForm.name" class="text-sm text-red-500">{{error}}</p>
            </FormElement>
        </AddBatchModal>

        <div class="flex justify-end">
            <PrimaryButton type="submit" class="w-1/20" @click="createBatches">Create Batches</PrimaryButton>
        </div>
    </div>
</template>

<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FormElement from "@/Components/FormElement.vue";
import AddBatchModal from "@/Components/Modal.vue";
import Heading from "@/Components/Heading.vue";
import Checkbox from '@/Components/Checkbox.vue'
import TextInput from '@/Components/TextInput.vue'
import { PlusCircleIcon,XMarkIcon} from "@heroicons/vue/24/outline";
import {router, useForm, usePage} from "@inertiajs/vue3";
import { ref , computed } from 'vue'

const error = ref('')
const showModal =ref(false)

const levels = computed(() => usePage().props.levels)

const updatedLevels = ref(levels.value.map(level => {
    return {
        name: level.name,
        isSelected: true,
        no_of_sections: 1
    }
}));


const levelForm = useForm({
    name: null,
})
function createLevel() {
    levelForm.post('/levels/create', {
        onSuccess: () => {
            // Add new level to the list
            updatedLevels.value.push({
                name: levelForm.name,
                isSelected: true,
                no_of_sections: 1
            });
            levelForm.reset();
            showModal.value = false;
        },
    });
}

function createBatches(){
    router.post('/batches/create-bulk', {
        batches: updatedLevels.value,
        onSuccess: () => {
           showModal.value = false;
        },
    })
}

</script>
