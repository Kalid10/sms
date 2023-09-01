<template>
    <div class="flex w-full flex-col rounded-lg bg-white">
        <div class="flex justify-center">
            <div class="flex w-full justify-center px-4 lg:order-2 lg:w-3/12">
                <img
                    :src="
                        imagePreview ??
                        user?.profile_image ??
                        'https://avatars.dicebear.com/api/open-peeps/' +
                            props.user.name +
                            '.svg'
                    "
                    alt="avatar"
                    class="mx-auto w-16 rounded-full object-cover md:h-40 md:w-40"
                />
            </div>
        </div>
        <div class="mt-12 flex flex-col items-center justify-center">
            <h3
                class="text-blueGray-700 mb-2 text-4xl font-semibold leading-normal"
            >
                {{ props.user.name }}
            </h3>
            <div class="mt-0 mb-2 text-sm font-bold uppercase leading-normal">
                <span class="mr-2 text-lg"></span>
                {{ props.user.address }}
            </div>
            <div class="mb-2 mt-10">
                <span v-if="admin" class="mr-2 text-lg">
                    {{ props.user.admin.position }}
                </span>
            </div>
            <div class="mb-2 flex w-full items-center justify-center gap-2">
                <span><AtSymbolIcon class="h-4 w-4" /></span>
                <span> {{ props.user.email }}</span>
            </div>
            <div class="mb-2 flex w-full items-center justify-center gap-3">
                <div class="flex items-center gap-2 text-right">
                    <span><PhoneIcon class="h-4 w-4" /></span>
                    <span> {{ props.user.phone_number }}</span>
                </div>
            </div>
        </div>
        <div class="border-blueGray-200 border-t py-5 text-center">
            <div
                v-if="admin"
                class="mx-auto flex w-full items-center justify-center space-y-3 px-3 lg:w-11/12"
            >
                <div
                    class="flex h-80 w-full flex-col items-center justify-center gap-2 px-4"
                >
                    <span
                        class="flex items-center justify-start text-lg font-bold underline"
                        >Roles of {{ props.user.name }}</span
                    >
                    <div
                        v-if="props.user.roles.length > 0"
                        class="scrollbar-hide flex w-full flex-col items-center overflow-y-scroll bg-red-800"
                    >
                        <ul
                            v-for="(role, index) in props.user.roles"
                            :key="index"
                            class="flex h-full w-full cursor-pointer justify-center py-4 text-xs hover:rounded-lg hover:bg-brand-400 hover:text-white"
                            :class="
                                index % 2 === 0
                                    ? 'bg-brand-50 px-10 hover:bg-brand-200'
                                    : 'bg-white px-10 hover:bg-brand-200'
                            "
                        >
                            <li class="">
                                <span>{{ role.description }}</span>
                            </li>
                        </ul>
                    </div>
                    <EmptyView v-else title="Roles are not assigned yet.">
                        <PrimaryButton title="Assign roles" />
                    </EmptyView>
                </div>
            </div>

            <div v-if="guardian" class="flex w-full flex-col space-y-3 px-3">
                <div class="w-full">
                    <p
                        v-if="props.user.guardian.children.length > 1"
                        class="text-black"
                    >
                        Children of {{ props.user.name }}
                    </p>
                    <p
                        v-if="props.user.guardian.children.length === 1"
                        class="text-black"
                    >
                        Child of {{ props.user.name }}
                    </p>
                </div>

                <div class="flex w-full justify-evenly space-x-2">
                    <div
                        v-for="(child, index) in props.user.guardian.children"
                        :key="index"
                        class="flex w-4/12 justify-between rounded-lg bg-gradient-to-br from-brand-300 to-brand-350 p-4 text-white md:p-6"
                    >
                        <div
                            class="relative flex w-full flex-col justify-center space-x-2"
                        >
                            <div
                                class="absolute top-0 right-0 cursor-pointer hover:scale-125"
                                @click="selectChild(child)"
                            >
                                <ArrowTopRightOnSquareIcon
                                    class="h-4 w-4 stroke-white"
                                />
                            </div>

                            <img
                                :src="
                                    imagePreview ??
                                    child?.user?.profile_image ??
                                    'https://avatars.dicebear.com/api/open-peeps/' +
                                        props.user.name +
                                        '.svg'
                                "
                                alt="avatar"
                                class="mx-auto h-8 w-8 rounded-full object-cover"
                            />
                            <h2 class="mt-4 text-base font-medium">
                                {{ child.user.name }}
                            </h2>
                            <p class="mt-2 text-sm">
                                {{ child.batches[0].batch.level.name }}
                                {{ child.batches[0].batch.section }}
                            </p>
                            <p class="mt-2 text-sm">
                                Guardian relation: {{ child.guardian_relation }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import {
    ArrowTopRightOnSquareIcon,
    AtSymbolIcon,
    PhoneIcon,
} from "@heroicons/vue/24/outline";
import { useI18n } from "vue-i18n";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import EmptyView from "@/Views/EmptyView.vue";
import { router } from "@inertiajs/vue3";

const { t } = useI18n();

const props = defineProps({
    user: {
        type: Number,
        default: null,
    },
});

const admin = props.user.admin;
const guardian = props.user.guardian;

const selectedChild = ref(null);

function selectChild(child) {
    selectedChild.value = child;
    childUrl();
}

function childUrl() {
    router.get("/admin/teachers/students/" + selectedChild.value.id);
}
</script>

<style scoped></style>
