<template>
    <div
        class="flex w-full flex-col items-center space-y-5 rounded-lg bg-white p-3 shadow-sm"
    >
        <!--        Header-->
        <div class="flex h-fit w-full items-center justify-between px-2">
            <span
                class="grow px-3 py-0.5 text-xl font-medium"
                :class="flags?.data?.length ? '' : ' '"
            >
                <span v-if="view === 'student'">
                    {{ student.user.name }}'s Flag List
                </span>
                <span v-else> {{ title }} </span>
            </span>
            <SecondaryButton
                v-if="view === 'student' && !flags.data?.length"
                class="h-fit !rounded-2xl bg-zinc-700 !px-4 !py-1 !text-xs text-white"
                title="Add"
                @click="showAddModal = true"
            />
            <InformationCircleIcon
                class="w-4 cursor-pointer text-gray-600 hover:scale-125 hover:text-black"
                @click="showInfoModal = true"
            />
        </div>
        <!--        List-->
        <div v-if="flags?.data?.length" class="flex w-full flex-col">
            <div
                class="mb-2 flex w-full bg-zinc-800 py-2 text-center text-xs text-white"
            >
                <span :class="viewDate ? 'w-3/12' : 'w-4/12'"> Name</span>
                <span :class="viewDate ? 'w-3/12' : 'w-4/12'"> By</span>
                <span :class="viewDate ? 'w-3/12' : 'w-4/12'"> Type </span>
                <span v-if="viewDate" class="w-3/12 text-center"
                    >Duration
                </span>
            </div>
            <div
                v-for="(item, index) in flags.data"
                :key="index"
                class="flex w-full cursor-pointer justify-evenly py-3 text-xs hover:rounded-lg hover:bg-zinc-800 hover:text-white"
                :class="index % 2 === 1 ? 'bg-gray-50' : ''"
                @click="
                    showDetailModal = true;
                    selectedFlag = item;
                "
            >
                <span
                    class="w-3/12 cursor-pointer text-center hover:scale-110 hover:underline hover:underline-offset-2"
                    @click.stop="routeToStudent(item.flaggable_id)"
                >
                    {{ item.flaggable.user.name }}
                </span>
                <span
                    class="w-3/12 cursor-pointer text-center hover:underline hover:underline-offset-2"
                >
                    <span v-if="item.flagged_by">
                        By
                        <span
                            v-if="
                                item.flagged_by.name ===
                                usePage().props.auth.user.name
                            "
                        >
                            You
                        </span>
                        <span v-else>
                            {{ item.flagged_by.name }}
                        </span>
                        <span v-if="item.is_homeroom" class="font-medium"
                            >(Homeroom)</span
                        >
                        <span v-else-if="item.flagged_by.type === 'teacher'"
                            >(
                            {{ item.batch_subject?.subject.full_name }} )</span
                        >
                        <span v-else>{{ item.flaggable.user.admin }}</span>
                    </span>
                    <span v-else class="text-xs font-medium"
                        >System Generated</span
                    >
                </span>

                <span class="flex w-3/12 justify-center space-x-1 text-center">
                    <span
                        v-for="(type, index) in item.type"
                        :key="index"
                        class="mx-1 flex h-fit w-fit flex-wrap rounded-3xl bg-red-600 py-0.5 px-2 text-center text-[0.65rem] font-medium lowercase text-white hover:scale-110"
                    >
                        {{ type.substring(0, 3) }}
                    </span>
                </span>
                <div v-if="item.flagged_by.id === auth.id">
                    <PencilIcon
                        class="absolute bottom-0 right-0 my-1 ml-3 w-4 cursor-pointer fill-black stroke-white text-gray-600 hover:mx-1 hover:scale-110 hover:fill-white hover:text-black"
                        @click="handleUpdateFlag($event, item)"
                    />
                </div>
                <div
                    class="absolute top-0 right-0 py-1 pl-3 hover:scale-110 hover:stroke-red-50 hover:px-1"
                >
                    <TrashIcon
                        class="w-4 cursor-pointer stroke-red-500 text-gray-600"
                        @click="deleteFlag($event, item)"
                    />
                </div>

                <span
                    v-if="viewDate"
                    class="w-3/12 text-center text-xs font-light"
                >
                    {{
                        moment(item.created_at).format("ddd MMMM DD YYYY") +
                        " - " +
                        moment(item.expires_at).format("ddd MMMM DD YYYY")
                    }}
                </span>
            </div>
            <Pagination :links="flags.links" class="pt-3" position="center" />
        </div>
        <div v-else class="w-full">
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
    <Loading v-if="isLoading" />
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
import { isAdmin } from "@/utils";
import Loading from "@/Components/Loading.vue";

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
    viewDate: {
        type: Boolean,
        default: true,
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
const isLoading = ref(false);

const flags = computed(() => usePage().props.flags);

const routeToStudent = (studentId) => {
    isLoading.value = true;
    router.get(
        isAdmin()
            ? "/admin/students/" + studentId
            : "/teacher/students/" + studentId,
        {},
        {
            onFinish: () => {
                isLoading.value = false;
            },
        }
    );
};

const auth = usePage().props.auth.user;
</script>

<style scoped></style>
