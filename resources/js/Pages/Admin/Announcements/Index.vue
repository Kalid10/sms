<template>
    <div class="h-fit w-full space-y-2 rounded-lg bg-white p-4 shadow-sm">
        <div class="flex w-full justify-between">
            <div class="text-xl font-semibold lg:text-2xl">
                Recent Announcements
            </div>
            <!--            TODO: Implement Search-->
            <TextInput placeholder="Search Assessments" class="w-5/12" />
        </div>
        <div class="flex flex-col divide-y divide-gray-100">
            <Item
                v-for="(item, index) in announcements"
                :key="index"
                :announcement="item"
                @click="handleClick(item)"
            />
        </div>
        <LinkCell
            class="flex w-full justify-center py-2"
            href="/admin/announcements"
            value="Show All Announcements"
        />

        <Modal v-model:view="showAnnouncement">
            <div class="flex w-full flex-col space-y-4 rounded-lg bg-white p-5">
                <div class="text-center text-2xl font-bold">
                    {{ selectedAnnouncement.title }}
                </div>

                <div class="text-sm font-medium">
                    Post Targets :
                    <span class="capitalize">
                        {{ selectedAnnouncement.target_group }}
                    </span>
                </div>
                <div class="flex items-center text-sm font-light">
                    {{ selectedAnnouncement.body }}
                </div>
                <div class="flex items-end justify-between space-y-2 text-xs">
                    <div>
                        Expires
                        {{ moment(selectedAnnouncement.expires_on).fromNow() }}
                    </div>
                    <div>
                        <div>
                            Posted
                            {{
                                moment(
                                    selectedAnnouncement.created_at
                                ).fromNow()
                            }}, By
                            <span
                                class="cursor-pointer underline-offset-2 hover:scale-105 hover:font-semibold hover:underline"
                            >
                                {{ selectedAnnouncement.author.user.name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </Modal>
    </div>
</template>
<script setup>
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import Item from "@/Views/Shared/Announcements/Item.vue";
import LinkCell from "@/Components/LinkCell.vue";
import Modal from "@/Components/Modal.vue";
import moment from "moment/moment";
import TextInput from "@/Components/TextInput.vue";

const announcements = computed(() => usePage().props.announcements);
const showAnnouncement = ref(false);
const selectedAnnouncement = ref();

function handleClick(item) {
    selectedAnnouncement.value = item;
    showAnnouncement.value = true;
}
</script>
<style scoped></style>
