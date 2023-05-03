<template>
    <div class="mx-auto grid w-full grid-cols-12">

        <div class="col-span-12 flex h-fit p-2 md:col-span-10">

            <div class="flex w-fit items-center lg:gap-5">
                <div class="mr-2 w-fit lg:w-full">
                    <img
                        :src="`https://xsgames.co/randomusers/avatar.php?g=${teacher.user.gender}`"
                        alt="avatar"
                        class="mx-auto aspect-square w-full rounded-full md:w-3/4"
                    />
                </div>


                <div class="mx-auto flex w-fit flex-col items-center">
                    <Heading class="!font-semibold" size="lg">{{ teacher.user.name }}</Heading>
                    <div class="flex w-full lg:gap-2">
                        <h3 class="max-w-full text-gray-500" size="">{{ teacher.user.email }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 flex flex-col space-y-3 lg:mt-10 lg:space-y-8">
        <Assessments/>

        <Subjects/>

        <HomeroomClasses/>

        <Feedbacks/>
    </div>
</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import Subjects from "@/Views/Teachers/Subjects.vue";
import HomeroomClasses from "@/Views/Teachers/HomeroomClasses.vue";
import Feedbacks from "@/Views/Teachers/Feedbacks.vue";
import {usePage} from "@inertiajs/vue3";
import Assessments from "@/Views/Teachers/Assessments.vue";

const teacher = usePage().props.teacher;
</script>

<style scoped>

</style>
