<template>

    <Heading>Register Batches</Heading>

    <div class="grid lg:grid-cols-4">

        <label>
            <Card class="!min-w-full">

                <div class="flex flex-col ">

                    <div class="flex items-center justify-between">

                        <h3 class="font-semibold">Grade 4</h3>

                        <Checkbox type="checkbox" class="h-5 w-5 !rounded-full checked:ring-0 focus:ring-0" />

                    </div>

                </div>

            </Card>
        </label>

    </div>

</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import Heading from "@/Components/Heading.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Card from "@/Components/Card.vue";

const levels = computed(() => usePage().props.levels)

</script>

<style scoped>
</style>
