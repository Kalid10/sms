<template>
    <div
        class="flex h-full flex-col items-center space-y-5 rounded-lg bg-white p-3 shadow-sm"
    >
        <!--        Header-->
        <div class="flex h-fit w-full items-center justify-between px-2">
            <InformationCircleIcon
                class="w-4 cursor-pointer text-gray-600 hover:scale-125 hover:text-black"
                @click="showInfoModal = true"
            />
            <span
                class="grow -skew-x-3 px-3 py-0.5 text-center font-semibold"
                :class="flags?.data?.length ? '' : ' '"
            >
                <span v-if="view === 'student'">
                    {{ student.user.name }}'s Flag List
                </span>
                <span v-else-if="view === 'homeroom'"> {{ title }} </span>
            </span>
            <SecondaryButton
                v-if="view === 'student'"
                class="h-fit !rounded-2xl bg-zinc-700 !px-4 !py-1 !text-xs text-white"
                title="Add"
                @click="showAddModal = true"
            />
        </div>

        <!--        List-->
        <div v-if="flags?.data?.length" class="flex w-full flex-col">
            <div
                class="mb-2 flex w-full bg-zinc-800 py-2 text-center text-xs text-white"
            >
                <span class="w-4/12 text-center"> Name</span>
                <span class="w-5/12 text-center">Duration </span>
                <span class="w-4/12"> Type </span>
            </div>
            <div
                v-for="(item, index) in flags.data"
                :key="index"
                class="flex w-full cursor-pointer justify-evenly space-x-2 px-2 py-4 text-xs hover:scale-105 hover:rounded-lg hover:bg-zinc-700 hover:text-gray-200"
                :class="index % 2 === 1 ? 'bg-gray-100/70' : ''"
                @click="
                    selectedFlag = item;
                    showDetailModal = true;
                "
            >
                <span class="w-3/12 text-center">
                    {{ item.flaggable.user.name }}
                </span>

                <span
                    class="w-5/12 text-center text-[0.6rem] font-semibold italic"
                >
                    {{ moment(item.created_at).format("MMMM DD, YYYY") }} -
                    {{ moment(item.expires_at).fromNow() }}
                </span>
                <span class="flex w-4/12 justify-center">
                    <span
                        class="w-fit rounded-3xl bg-red-600 py-0.5 px-3 text-center text-[0.65rem] font-medium lowercase text-white"
                    >
                        {{ item.type }}
                    </span>
                </span>
            </div>
            <Pagination :links="flags.links" class="pt-3" position="center" />
        </div>
        <div v-else>
            <EmptyView title="No Flags Found" />
        </div>
    </div>

    <Modal v-model:view="showAddModal">
        <AddFlag
            :flaggable="view === 'student' ? props?.student : null"
            :batch-subject-options="batchSubjectOptions"
            @done="showAddModal = false"
        />
    </Modal>

    <Modal v-model:view="showDetailModal">
        <SelectedFlagDetail :selected-flag-item="selectedFlag" />
    </Modal>

    <Modal v-model:view="showInfoModal">
        <FlagInfo />
    </Modal>
</template>

<script setup>
import { InformationCircleIcon } from "@heroicons/vue/24/outline";
import { computed, ref } from "vue";
import Modal from "@/Components/Modal.vue";
import { usePage } from "@inertiajs/vue3";
import moment from "moment";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Pagination from "@/Components/Pagination.vue";
import EmptyView from "@/Views/EmptyView.vue";
import SelectedFlagDetail from "@/Views/Flag/SelectedFlagDetail.vue";
import AddFlag from "@/Views/Flag/AddFlag.vue";
import FlagInfo from "@/Views/Flag/FlagInfo.vue";

const props = defineProps({
    student: {
        type: Object,
        default: null,
    },
    batchSubjectOptions: {
        type: Object,
        default: null,
    },
    view: {
        type: String,
        default: "student",
    },
    title: {
        type: String,
        default: null,
    },
});
const showInfoModal = ref(false);
const showAddModal = ref(false);
const showDetailModal = ref(false);
const selectedFlag = ref(null);

const flags = computed(() => usePage().props.flags);
</script>

<style scoped></style>
