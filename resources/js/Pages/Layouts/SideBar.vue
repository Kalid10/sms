<template>
    <div
        class="bg-zinc-800 text-white"
        :class="[
            open
                ? 'min-w-[16rem] lg:w-96 lg:min-w-0'
                : 'w-3/12 min-w-0 lg:w-16',
            'transition-all duration-300 ease-in-out ',
        ]"
    >
        <div
            class="flex min-h-screen w-full flex-col items-center justify-between pt-1 pb-4 text-white lg:pt-5"
        >
            <div class="flex w-full flex-col items-center justify-center">
                <!-- Header -->
                <div
                    v-if="header && open"
                    class="flex w-full flex-col items-center justify-center space-x-4 space-y-2 py-2 px-1 lg:flex-row lg:space-y-1"
                >
                    <div class="h-full w-fit lg:w-20">
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
                <div
                    class="mt-1 flex w-full flex-col justify-evenly lg:mt-10 lg:space-y-1"
                >
                    <Link
                        v-for="(item, index) in sideBarItems"
                        :key="index"
                        :href="`${item.route}`"
                        class="flex h-14 w-full items-center justify-between lg:h-20"
                        :class="[
                            item.active
                                ? 'rounded-lg bg-zinc-700 font-medium'
                                : 'font-normal',
                            'transition-all duration-300 ease-in-out ',
                        ]"
                    >
                        <div
                            class="flex h-12 w-full cursor-pointer items-center justify-center hover:bg-zinc-700 hover:transition-all hover:duration-300 hover:ease-out lg:h-20"
                        >
                            <div
                                class="relative flex w-10/12 items-center text-center hover:cursor-pointer lg:w-2/3"
                            >
                                <div
                                    class="relative flex w-full items-center"
                                    :class="
                                        open
                                            ? 'justify-between'
                                            : 'justify-center'
                                    "
                                >
                                    <component
                                        :is="item.icon"
                                        class="h-4 lg:h-6"
                                    />
                                    <div
                                        class="absolute inset-x-2 whitespace-nowrap text-xs lg:text-base"
                                        :class="{
                                            hidden: !open,
                                            'lg:inline': open,
                                        }"
                                    >
                                        {{ item.name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            :class="
                                item.active
                                    ? 'h-3/5 lg:h-full w-1 lg:w-2 bg-neutral-50 pr-0.5 rounded-l-md'
                                    : ''
                            "
                        ></div>
                    </Link>
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
                        :class="open ? 'justify-between' : 'justify-center'"
                    >
                        <component :is="item.icon" class="h-4 lg:h-6" />
                        <div
                            class="absolute inset-x-2 text-xs lg:text-base"
                            :class="{ hidden: !open, 'lg:inline': open }"
                        >
                            {{ item.name }}
                        </div>
                    </div>
                    <div
                        class="relative flex w-4/5 items-center lg:w-2/3"
                        :class="open ? 'justify-between' : 'justify-center'"
                        @click="toggleView"
                    >
                        <Bars3BottomLeftIcon v-if="open" class="h-4 lg:h-6" />
                        <Bars3BottomRightIcon
                            v-else
                            class="h-4 rotate-180 lg:h-6"
                        />
                        <div
                            class="absolute inset-x-2 text-xs lg:text-base"
                            :class="{ hidden: !open, 'lg:inline': open }"
                        >
                            Shrink
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import { Link } from "@inertiajs/vue3";
import {
    Bars3BottomLeftIcon,
    Bars3BottomRightIcon,
    EnvelopeIcon,
    PhoneIcon,
} from "@heroicons/vue/20/solid";
import { computed } from "vue";

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
    open: {
        type: Boolean,
        required: true,
    },
});

const emits = defineEmits(["update:open"]);

const sideBarItems = computed(() => props.mainItems);

const view = computed({
    get() {
        return props.open;
    },
    set(value) {
        emits("update:open", value);
    },
});

function toggleView() {
    view.value = !view.value;
}
</script>
