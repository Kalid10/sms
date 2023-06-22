<template>
    <div class="flex flex-col space-y-5 rounded-lg bg-white p-3 shadow-sm">
        <div class="px-4 text-2xl font-medium">Recent Flags</div>
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
            <span class="w-3/12 text-center">
                {{ item.flaggable.user.name }}
            </span>
            <span
                class="w-2/12 cursor-pointer hover:underline hover:underline-offset-2"
            >
                By {{ item.flagged_by.name }}
                <span v-if="item.flagged_by.type === 'teacher'"
                    >({{ item.batch_subject?.subject.full_name }} )</span
                >
                <span v-else>{{ item.flaggable.user.admin }}</span>
            </span>
            <span class="w-4/12 text-center text-xs font-light">
                {{
                    moment(item.created_at).format("ddd MMMM DD YYYY") +
                    " - " +
                    moment(item.expires_at).format("ddd MMMM DD YYYY")
                }}
            </span>

            <span class="w-3/12 text-center">
                <span
                    class="rounded-lg bg-red-600 px-3 py-1 font-medium lowercase text-white"
                >
                    {{ item.type }}
                </span>
            </span>
        </div>
        <Modal v-model:view="showDetailModal">
            <SelectedFlagDetail :selected-flag-item="selectedFlag" />
        </Modal>
        <Pagination :links="flags.links" position="center" />
    </div>
</template>
<script setup>
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import moment from "moment";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import SelectedFlagDetail from "@/Views/SelectedFlagDetail.vue";

const flags = computed(() => usePage().props.flags);
const selectedFlag = ref(null);
const showDetailModal = ref(false);
</script>
<style scoped></style>
