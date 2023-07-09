<template>
    <div
        :class="
            classStyle
                ? classStyle
                : 'h-fit w-full space-y-2 py-4 rounded-lg bg-white px-2 shadow-sm'
        "
    >
        <div v-if="showHeader" class="w-full p-3">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div
                    class="text-xl font-medium lg:text-2xl"
                    :class="view === 'admin' ? 'w-7/12' : 'w-5/12'"
                >
                    {{ title }}
                </div>
                <SquaresPlusIcon
                    v-if="isAdmin() && view === 'admin'"
                    class="w-5 cursor-pointer text-zinc-700 hover:scale-105"
                />
            </div>
        </div>

        <div>
            <EmptyView
                v-if="!computedAnnouncements"
                title="No Announcements Found!"
                link-title="Go To Announcements"
                :link-url="url"
                class="flex w-full justify-center py-2"
            />
            <div v-else class="flex flex-col space-y-2">
                <div class="flex flex-col divide-y divide-gray-50">
                    <Item
                        v-for="(item, index) in computedAnnouncements"
                        :key="index"
                        :announcement="item"
                        :class="index % 2 === 0 ? 'bg-gray-50/50' : ''"
                        @click="handleClick(item)"
                    />
                </div>
                <Pagination
                    v-if="announcements.links"
                    :links="announcements.links"
                    position="center"
                    class="pt-3"
                />
                <LinkCell
                    v-else-if="!(view === 'teacher' && isAdmin())"
                    class="flex w-full justify-center py-2"
                    :href="url"
                    value="View All Announcements"
                />
            </div>
        </div>

        <Modal v-model:view="showAnnouncement">
            <ShowAnnouncementView
                :selected-announcement="selectedAnnouncement"
            />
        </Modal>
    </div>
</template>
<script setup>
import { computed, ref, toRefs, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import Item from "@/Views/Announcements/Item.vue";
import LinkCell from "@/Components/LinkCell.vue";
import Modal from "@/Components/Modal.vue";
import EmptyView from "@/Views/EmptyView.vue";
import { debounce } from "lodash";
import Pagination from "@/Components/Pagination.vue";
import { isAdmin } from "@/utils";
import { SquaresPlusIcon } from "@heroicons/vue/20/solid";
import ShowAnnouncementView from "@/Views/Announcements/ShowAnnouncement.vue";

const props = defineProps({
    url: {
        type: String,
        default: "/admin/announcements/",
    },
    title: {
        type: String,
        default: "Announcements",
    },
    classStyle: {
        type: String,
        default: null,
    },
    view: {
        type: String,
        default: "admin",
    },
    showHeader: {
        type: Boolean,
        default: true,
    },
});

const error = computed(() => usePage().props.errors);

const { classStyle } = toRefs(props);

const Announcements = ["all", "teachers", "guardians", "admins"];

const announcements = computed(() => usePage().props.announcements);
const computedAnnouncements = computed(() => {
    if (announcements.value?.data) {
        return announcements.value.data;
    }
    return announcements.value;
});

const showAnnouncement = ref(false);
const selectedAnnouncement = ref();

function handleClick(item) {
    selectedAnnouncement.value = item;
    showAnnouncement.value = true;
}

const searchKey = ref("");

const search = debounce(() => {
    router.get(
        props.url,
        {
            search: searchKey.value,
        },
        {
            only: ["announcements"],
            preserveState: true,
            replace: true,
        }
    );
}, 300);

watch(searchKey, () => {
    search();
});
</script>
<style scoped></style>
