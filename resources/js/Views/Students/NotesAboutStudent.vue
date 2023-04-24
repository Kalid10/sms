<template>

    <div class="flex w-full flex-col gap-3">

        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2">
                <div class="h-4 w-4 rounded-full bg-green-500"></div>
                <h3 class="font-semibold text-gray-500">Teachers' Notes</h3>
            </div>

            <ul class="flex w-full snap-x gap-4 overflow-auto pb-3">

                <li v-for="i in 3" :key="i" class="snap-start">
                    <Card
                        class="!h-72 !w-72 !justify-start "
                        title="Incessant disturbance"
                    >
                        <div class="flex grow flex-col justify-between">
                            <h3 class="text-gray-500">
                                This student has been disturbing in my classes non stop and no
                                amount of
                                warnings has been able to stop him. I would like to request that he
                                be
                                suspended for a week.
                            </h3>

                            <div class="flex flex-col">
                                <h3 class="">Teacher James Dolson</h3>
                                <h3 class="text-sm italic text-gray-500">2 weeks ago</h3>
                            </div>
                        </div>
                    </Card>
                </li>

            </ul>

        </div>

        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2">
                <div class="h-4 w-4 rounded-full bg-yellow-500"></div>
                <h3 class="font-semibold text-gray-500">Guardians' Notes</h3>
            </div>

            <ul class="flex w-full snap-x gap-4 overflow-auto pb-3">

                <li v-for="i in 3" :key="i" class="snap-start">
                    <Card
                        class="!h-72 !w-72 !justify-start "
                        title="Allergy to peanuts"
                    >
                        <div class="flex grow flex-col justify-between">
                            <div class="notes h-[144px] overflow-hidden text-ellipsis">
                                <h3 class="text-gray-500">
                                    My kid went to the doctor's last week and we were informed
                                    that he is allergic to peanuts. Please make sure that he is not
                                    given any food containing peanuts. Thank you.
                                    Also, make sure he has his inhaler with him at all times. He's
                                    always
                                    supposed to have one in his bag.
                                </h3>
                            </div>

                            <div class="flex flex-col">
                                <h3 class="">Teacher James Dolson</h3>
                                <h3 class="text-sm italic text-gray-500">4 months ago</h3>
                            </div>
                        </div>
                    </Card>
                </li>

            </ul>

        </div>

    </div>

</template>

<script setup>
import Card from "@/Components/Card.vue"
</script>

<style scoped>
.notes {
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 6;
    line-clamp: 6;
    -webkit-box-orient: vertical;
}
</style>
