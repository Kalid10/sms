<template>
    <div
        class="flex min-h-screen w-full flex-col items-center justify-between pt-1 pb-10 text-white lg:pt-5"
    >
        <div class="flex w-full flex-col items-center justify-center">
            <!-- Header -->
            <div
                v-if="header && isOpen"
                class="flex w-full flex-col items-center justify-center space-x-4 space-y-2 py-2 px-1 lg:flex-row lg:space-y-1"
            >
                <div class="h-full w-fit lg:w-24">
                    <img
                        :src="`https://xsgames.co/randomusers/avatar.php?g=${header.user.gender}`"
                        alt="avatar"
                        class="mx-auto h-16 w-full rounded-full object-cover md:w-11/12 lg:h-24 lg:rounded-lg"
                    />
                </div>
                <div
                    class="flex flex-col space-y-2 text-xs font-light lg:text-sm"
                >
                    <Heading class="text-xs lg:text-xl"
                        >{{ header.user.name }}
                    </Heading>
                    <div class="flex items-center space-x-2 lg:space-x-1.5">
                        <EnvelopeIcon class="h-3.5 lg:h-5" />
                        <span>{{ header.user.email }}</span>
                    </div>
                    <div class="flex items-center space-x-2 lg:space-x-1.5">
                        <PhoneIcon class="h-3.5 lg:h-5" />
                        <span> 0943104396</span>
                    </div>
                </div>
            </div>

            <!-- Main Items -->
            <div class="mt-3 flex w-full flex-col justify-evenly lg:mt-10">
                <div
                    v-for="(item, index) in mainItems"
                    :key="index"
                    class="flex h-14 w-full items-center justify-between lg:h-20"
                    :class="
                        index === 0
                            ? 'font-medium bg-zinc-800 rounded-lg'
                            : 'font-normal'
                    "
                >
                    <div class="flex h-20 w-full items-center justify-center">
                        <div
                            class="relative flex w-10/12 items-center text-center hover:cursor-pointer lg:w-2/3"
                        >
                            <div
                                class="relative flex w-full items-center"
                                :class="
                                    isOpen
                                        ? 'justify-between'
                                        : 'justify-center'
                                "
                            >
                                <component :is="item.icon" class="h-4 lg:h-6" />
                                <div
                                    class="absolute inset-x-2 text-xs lg:text-base"
                                    :class="{
                                        hidden: !isOpen,
                                        'lg:inline': isOpen,
                                    }"
                                >
                                    {{ item.name }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        :class="
                            index === 0
                                ? 'h-3/5 lg:h-full w-1 lg:w-2 bg-neutral-50 pr-0.5 rounded-l-md'
                                : ''
                        "
                    ></div>
                </div>
            </div>
        </div>

        <div class="flex h-fit w-full flex-col justify-between">
            <!-- Footer -->
            <div
                class="flex w-full flex-col items-center space-y-10 text-center text-white"
            >
                <div
                    v-for="(item, index) in footerItems"
                    :key="index"
                    class="relative flex w-4/5 items-center lg:w-2/3"
                    :class="isOpen ? 'justify-between' : 'justify-center'"
                >
                    <component :is="item.icon" class="h-4 lg:h-6" />
                    <div
                        class="absolute inset-x-2 text-xs lg:text-base"
                        :class="{ hidden: !isOpen, 'lg:inline': isOpen }"
                    >
                        {{ item.name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import { EnvelopeIcon, PhoneIcon } from "@heroicons/vue/20/solid";

const props = defineProps({
    header: {
        type: Object,
        required: true,
    },
    mainItems: {
        type: Array,
        required: true,
    },
    footerItems: {
        type: Array,
        required: true,
    },
    isOpen: {
        type: Boolean,
        required: true,
    },
});
</script>
