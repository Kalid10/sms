<template>

    <div class="mx-auto grid w-full grid-cols-12 gap-4">

        <div class="col-span-12 flex h-full flex-col p-2 md:col-span-3">

            <div class="flex flex-col items-center gap-5">

                <div class="flex w-full flex-col gap-3">

                    <img
                        :src="`https://xsgames.co/randomusers/avatar.php?g=${gender}`"
                        alt="avatar"
                        class="mx-auto aspect-square w-3/4 rounded-full"
                    />

                    <div class="flex flex-col items-center">
                        <Heading class="!font-semibold !uppercase" size="lg">Adil Abdu Bushra</Heading>
                        <div class="flex gap-2 leading-none">
                            <Heading class="!font-normal" size="">12B</Heading>
                            <h3>
                                <span class="text-gray-500">(05)</span>
                            </h3>
                        </div>
                    </div>


                </div>

                <Card class="!min-w-full">

                    <ul class="flex w-full flex-col gap-2">

                        <li class="flex gap-2">
                            <IdentificationIcon class="h-5 w-5 stroke-gray-500"/>
                            <span class="flex gap-1 text-sm">
                            <span>GSN/2201/19</span>
                        </span>
                        </li>

                        <li class="flex gap-2">
                            <CakeIcon class="h-5 w-5 stroke-gray-500"/>
                            <span class="flex gap-1 text-sm">
                            <span>January 27, 1997</span>
                        </span>
                        </li>

                        <li class="flex gap-2">
                            <RectangleStackIcon class="h-5 w-5 stroke-gray-500"/>
                            <span class="flex gap-1 text-sm">
                            <span>12th Semester</span>
                            <span class="text-gray-500">at Gibson Youth</span>
                        </span>
                        </li>

                        <li class="flex gap-2">
                            <AtSymbolIcon class="h-5 w-5 stroke-gray-500"/>
                            <span class="flex gap-1 text-sm">
                            <a
                                class="text-blue-500 underline-offset-2 hover:underline"
                                href="mailto:adilabdu68@gmail.com">adilabdu68@gmail.com</a>
                        </span>
                        </li>

                        <li class="flex gap-2">
                            <DevicePhoneMobileIcon class="h-5 w-5 stroke-gray-500"/>
                            <span class="flex gap-1 text-sm">
                            <a
                                class="text-blue-500 underline-offset-2 hover:underline"
                                href="#">+251 91 2272 145</a>
                        </span>
                        </li>

                    </ul>


                </Card>

                <div class="flex w-full flex-col gap-1">

                    <Heading class="!font-semibold !uppercase text-gray-500" size="xs">Guardians</Heading>

                    <div class="flex flex-col gap-3">
                        <Card class="min-w-full">

                            <div class="flex flex-col items-center gap-3">

                                <div class="flex w-full items-center justify-between gap-3">
                                    <Heading size="sm">Abdu Bushra Omer</Heading>
                                    <Heading class="whitespace-nowrap !font-normal" size="sm">Father</Heading>
                                </div>

                                <div class="flex w-full gap-3">

                                    <TertiaryButton class="w-1/2">Contact</TertiaryButton>
                                    <TertiaryButton class="w-1/2">Details</TertiaryButton>

                                </div>

                            </div>

                        </Card>
                        <Card class="min-w-full">

                            <div class="flex flex-col items-center gap-3">

                                <div class="flex w-full items-center justify-between gap-3">
                                    <Heading class="truncate whitespace-nowrap" size="sm">
                                        Anissa Ibrahim
                                    </Heading>
                                    <Heading class="whitespace-nowrap !font-normal" size="sm">Mother</Heading>
                                </div>

                                <div class="flex w-full gap-3">

                                    <TertiaryButton class="w-1/2">Contact</TertiaryButton>
                                    <TertiaryButton class="w-1/2">Details</TertiaryButton>

                                </div>

                            </div>

                        </Card>
                    </div>

                </div>


            </div>

        </div>

        <div class="col-span-9 h-full overflow-auto p-2">

            <div class="flex flex-col gap-4">

                <ul class="flex w-full items-center gap-4">

                    <li class="rounded-full bg-brand-50 py-2 px-4">
                        <button class="w-full whitespace-nowrap text-sm font-semibold text-brand-100">
                            Overview
                        </button>
                    </li>

                    <li class="rounded-full py-2 px-4">
                        <button class="w-full whitespace-nowrap text-sm font-semibold text-gray-500">
                            Personal Details
                        </button>
                    </li>

                    <li class="rounded-full py-2 px-4">
                        <button class="w-full whitespace-nowrap text-sm font-semibold text-gray-500">
                            Attendance
                        </button>
                    </li>

                    <li class="rounded-full py-2 px-4">
                        <button class="w-full whitespace-nowrap text-sm font-semibold text-gray-500">
                            Academic Performance
                        </button>
                    </li>

                    <li class="rounded-full py-2 px-4">
                        <button class="w-full whitespace-nowrap text-sm font-semibold text-gray-500">
                            Conduct Record
                        </button>
                    </li>

                    <li class="rounded-full py-2 px-4">
                        <button class="w-full whitespace-nowrap text-sm font-semibold text-gray-500">
                            Schedule
                        </button>
                    </li>

                </ul>

                <div class="h-52 w-full rounded-lg bg-gray-200">

                </div>

            </div>

        </div>

    </div>

</template>

<script setup>
import {ref} from "vue"
import {
    CakeIcon,
    RectangleStackIcon,
    AtSymbolIcon,
    DevicePhoneMobileIcon,
    IdentificationIcon
} from "@heroicons/vue/24/outline";
import Heading from "@/Components/Heading.vue";
import Card from "@/Components/Card.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";

const gender = ref(Math.floor(Math.random() * 100) % 2 === 0 ? 'male' : 'female')
</script>

<style scoped>

</style>
