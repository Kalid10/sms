<template>

    <div class="grid w-full grid-cols-12 gap-4">

        <div class="col-span-12 lg:col-span-8">

            <Card class="flex !min-w-full flex-col gap-3">

                <div class="flex w-full flex-col items-center justify-between gap-3 md:flex-row">

                    <div class="flex flex-col gap-3 md:flex-row">

                        <div class="h-16 w-16 overflow-hidden rounded-full">
                            <img :src="userImage" alt="Profile picture" />
                        </div>

                        <div class="flex flex-col items-center justify-center md:items-start">
                            <h3 class="flex items-baseline gap-1 font-semibold">
                            <span>
                               Adil Abdu Bushra
                            </span>
                            </h3>
                            <div class="flex gap-2">
                                <h3 class="text-sm text-gray-500">12B</h3>
                                <h3 class="text-sm text-gray-500">Section 1</h3>
                            </div>
                        </div>

                    </div>

                    <div class="flex gap-3">
                        <TertiaryButton class="">Update Details</TertiaryButton>
                        <PrimaryButton class="">Contact Guardian</PrimaryButton>
                    </div>

                </div>


            </Card>

        </div>

        <div class="col-span-12 lg:col-span-4">

            <Card icon class="!min-w-full" title="Recent Activities">

                <template #icon>
                    <RssIcon />
                </template>

                <ul class="flex flex-col">

                    <li class="flex justify-between py-3 px-2 text-gray-500">
                        <div class="flex gap-2">
                            <div class="grid h-6 w-6 place-items-center rounded-full bg-green-200">
                                <PuzzlePieceIcon class="h-4 w-4 stroke-green-600 stroke-2" />
                            </div>
                            <span class="text-sm text-black">
                                Biology Exam
                            </span>
                        </div>
                        <span class="text-xs">
                            February 21
                        </span>
                    </li>
                    <li class="flex justify-between py-3 px-2 text-gray-500">
                        <div class="flex gap-2">
                            <div class="grid h-6 w-6 place-items-center rounded-full bg-green-200">
                                <PuzzlePieceIcon class="h-4 w-4 stroke-green-600 stroke-2" />
                            </div>
                            <span class="text-sm text-black">
                                Math Exam
                            </span>
                        </div>
                        <span class="text-xs">
                            March 11
                        </span>
                    </li>
                    <li class="flex justify-between py-3 px-2 text-gray-500">
                        <div class="flex gap-2">
                            <div class="grid h-6 w-6 place-items-center rounded-full bg-red-200">
                                <XMarkIcon class="h-4 w-4 stroke-red-600 stroke-2" />
                            </div>
                            <span class="text-sm text-black">
                                Absent
                            </span>
                        </div>
                        <span class="text-xs">
                            April 21
                        </span>
                    </li>

                </ul>

                <TertiaryButton class="!min-w-full">View All</TertiaryButton>

            </Card>

        </div>



    </div>

</template>

<script setup>
import { RssIcon, PuzzlePieceIcon, XMarkIcon } from "@heroicons/vue/24/outline";
import Card from "@/Components/Card.vue";
import {onMounted, ref} from "vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Heading from "@/Components/Heading.vue";

const userImage = ref(null)

onMounted(() => {

    axios.get('https://randomuser.me/api/')
        .then(response => {
            userImage.value = response.data.results[0].picture.large
        })

})

</script>

<style scoped>

</style>
