<template>
    <div
        class="flex w-full flex-col items-center space-y-5 rounded-lg bg-white py-3 shadow-sm"
    >
        <div
            class="flex space-x-2 text-center text-xl font-semibold underline-offset-4"
        >
            <BookOpenIcon class="w-6" />
            <span>Notes</span>
        </div>

        <div class="flex w-10/12 flex-col space-y-6">
            <div
                v-for="(item, index) in notes"
                :key="index"
                class="flex w-full space-x-3 px-2"
            >
                <div
                    class="h-full w-0.5 rounded-t-lg rounded-b-md py-3"
                    :class="item.color"
                ></div>
                <div class="flex flex-col space-y-1">
                    <div class="2xltext-sm text-xs font-medium">
                        {{ item.title }}
                    </div>
                    <div
                        class="flex w-full justify-between text-[0.6rem] 2xl:text-xs"
                    >
                        <div class="font-light">
                            {{ item.date }}
                        </div>
                        <div>Mr.Abebe Kebede</div>
                    </div>
                </div>
            </div>
        </div>
        <LinkCell href="/cum" value="Show All Notes" />
    </div>
</template>
<script setup>
import { BookOpenIcon } from "@heroicons/vue/24/outline";
import moment from "moment";
import LinkCell from "@/Components/LinkCell.vue";

const notes = [
    {
        title: "Kalid Abdu Seid has shown massive improvement",
        date: moment().format(" dddd MMMM D YYYY"),
        color: "bg-emerald-500",
    },
    {
        title: "Kalid Abdu Seid has shown massive improvement",
        date: moment().format(" dddd MMMM D YYYY"),
        color: "bg-red-400",
    },
    {
        title: "Kalid Abdu Seid has shown massive improvement",
        date: moment().format(" dddd MMMM D YYYY"),
        color: "bg-cyan-400",
    },
    {
        title: "Kalid Abdu Seid has shown massive improvement",
        date: moment().format(" dddd MMMM D YYYY"),
        color: "bg-amber-400",
    },
    {
        title: "Kalid Abdu Seid has shown massive improvement",
        date: moment().format(" dddd MMMM D YYYY"),
        color: "bg-emerald-500",
    },
];
</script>

<style scoped></style>
