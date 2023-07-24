<template>
    <Card :title="`Personal details`" class="!max-w-full !grow">
        <div class="flex h-full justify-between gap-3">
            <ul class="flex w-full flex-col gap-2">
                <li class="flex gap-2">
                    <IdentificationIcon class="h-5 w-5 stroke-gray-500" />
                    <span class="flex gap-1 text-sm">
                        <span>GSN/2201/19</span>
                    </span>
                </li>

                <li class="flex gap-2">
                    <CakeIcon class="h-5 w-5 stroke-gray-500" />
                    <span class="flex gap-1 text-sm">
                        <span>{{ student.user.date_of_birth }}</span>
                    </span>
                </li>

                <li class="flex gap-2">
                    <RectangleStackIcon class="h-5 w-5 stroke-gray-500" />
                    <span class="flex gap-1 text-sm">
                        <span class="flex"
                            >{{ student.batches.length }}
                            <span v-if="student.batches.length === 1">st</span>
                            <span class="ml-1">Year</span>
                            <span v-if="student.batches.length > 1">s</span>
                        </span>
                        <span class="text-brand-text-300">at Gibson Youth</span>
                    </span>
                </li>

                <li v-if="student.user.email" class="flex gap-2">
                    <AtSymbolIcon class="h-5 w-5 stroke-gray-500" />
                    <span class="flex gap-1 text-sm">
                        <a
                            :href="`mailto:${student.user.email}`"
                            class="text-blue-500 underline-offset-2 hover:underline"
                            >{{ student.user.email }}
                        </a>
                    </span>
                </li>

                <li v-if="student.user.phone_number" class="flex gap-2">
                    <DevicePhoneMobileIcon class="h-5 w-5 stroke-gray-500" />
                    <span class="flex gap-1 text-sm">
                        <a
                            class="text-blue-500 underline-offset-2 hover:underline"
                            href="#"
                            >{{ student.user.phone_number }}
                        </a>
                    </span>
                </li>
            </ul>

            <div
                class="flex aspect-square h-full items-center justify-center whitespace-nowrap rounded-full bg-brand-150 text-3xl font-semibold"
            >
                {{ activeBatch.level.name }}{{ activeBatch.section }}
            </div>
        </div>
    </Card>
</template>

<script setup>
import {
    AtSymbolIcon,
    CakeIcon,
    DevicePhoneMobileIcon,
    IdentificationIcon,
    RectangleStackIcon,
} from "@heroicons/vue/24/outline";
import Card from "@/Components/Card.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const student = computed(() => usePage().props.student);
const activeBatch = computed(() => usePage().props.active_batch);
</script>

<style scoped></style>
