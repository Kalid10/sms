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
            <div v-if="!flags.data?.length">
                <SecondaryButton
                    v-if="view === 'student'"
                    class="h-fit !rounded-2xl bg-zinc-700 !px-4 !py-1 !text-xs text-white"
                    :title="'Add'"
                    @click="showAddModal = true"
                />
            </div>
        </div>
        <!--        List-->
        <div v-if="flags?.data?.length" class="flex w-full flex-col">
            <div
                class="mb-2 flex w-full bg-zinc-800 py-2 text-center text-xs text-white"
            >
                <span class="w-3/12 text-center"> Name</span>
                <span class="w-4/12 text-center">Duration </span>
                <span class="w-5/12"> Type </span>
            </div>
            <div
                v-for="(item, index) in flags.data"
                :key="index"
                class="group relative flex w-full cursor-pointer items-center justify-evenly space-x-2 px-2 py-4 text-xs hover:scale-105 hover:rounded-lg hover:bg-zinc-700 hover:text-gray-200"
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
                    class="w-4/12 text-center text-[0.6rem] font-semibold italic"
                >
                    {{ moment(item.created_at).format("MMMM DD, YYYY") }} -
                    {{ moment(item.expires_at).fromNow() }}
                </span>
                <span class="flex w-5/12 justify-center">
                    <span
                        v-for="(type, index) in item.type"
                        :key="index"
                        class="mx-1 flex h-fit w-fit flex-wrap rounded-3xl bg-red-600 py-0.5 px-2 text-center text-[0.65rem] font-medium lowercase text-white hover:scale-110"
                    >
                        {{ type.substring(0, 3) }}
                    </span>
                </span>
                <div v-if="item.flagged_by.id === auth.id" class="">
                    <PencilIcon
                        class="my-1 ml-3 w-4 cursor-pointer text-zinc-700 hover:text-black group-hover:fill-white"
                        @click="handleUpdateFlag($event, item)"
                    />
                </div>
                <div
                    v-if="item.flagged_by.id === auth.id"
                    class="py-1 pl-3 hover:scale-110 hover:stroke-red-50 hover:px-1"
                >
                    <TrashIcon
                        class="w-4 cursor-pointer stroke-red-500 text-gray-600"
                        @click="deleteFlag($event, item)"
                    />
                </div>
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
            :selected-flag="selectedFlag"
            @done="showAddModal = false"
        />
    </Modal>

    <Modal v-model:view="showDetailModal">
        <SelectedFlagDetail
            :flaggable="view === 'student' ? props?.student : null"
            :batch-subject-options="batchSubjectOptions"
            :selected-flag-item="selectedFlag"
        />
    </Modal>

    <Modal v-model:view="showInfoModal">
        <FlagInfo />
    </Modal>
</template>

<script setup>
import {
    InformationCircleIcon,
    PencilIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";
import { computed, ref } from "vue";
import Modal from "@/Components/Modal.vue";
import { router, usePage } from "@inertiajs/vue3";
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
const showDelete = ref(false);

function handleUpdateFlag(e, item) {
    e.stopPropagation();
    selectedFlag.value = item;
    showAddModal.value = true;
}

function deleteFlag(e, item) {
    e.stopPropagation();
    selectedFlag.value = item;
    showDetailModal.value = false;
    showAddModal.value = false;
    showDelete.value = true;

    router.delete("/teacher/students/flag/" + selectedFlag.value.id, {
        preserveScroll: true,
        onSuccess: () => {
            showDelete.value = false;
            selectedFlag.value = null;
        },
    });
}

const flags = computed(() => usePage().props.flags);

const auth = usePage().props.auth.user;
</script>

<style scoped></style>
