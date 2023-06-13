<template>
    <div
        :class="
            classStyle
                ? classStyle
                : 'h-fit w-full space-y-2 rounded-lg bg-white py-5 px-24 shadow-sm'
        "
    >
        <div class="flex w-full flex-col space-y-4 rounded bg-gray-100 p-3">
            <div class="flex items-center gap-2">
                <SignalIcon class="h-8 w-8" />
                <div class="grow text-xl font-semibold lg:text-2xl">
                    {{ title }}
                </div>
                <PrimaryButton
                    v-if="isAdmin"
                    title="Add Announcement"
                    class="m-2"
                    @click="showAddAnnouncement = true"
                />
            </div>

            <TextInput
                v-model="searchKey"
                placeholder="Search Assessments"
                class="w-5/12"
                @keyup="search"
            />
        </div>

        <div>
            <EmptyView
                v-if="computedAnnouncements.length === 0"
                title="No Announcements Found!"
                link-title="Go To Announcements"
                link-url="/admin/announcements"
                class="flex w-full justify-center py-2"
            />
            <div v-else class="flex flex-col divide-y divide-gray-100">
                <Item
                    v-for="(item, index) in computedAnnouncements"
                    :key="index"
                    :announcement="item"
                    @click="handleClick(item)"
                />
                <Pagination
                    v-if="announcements.links"
                    :links="announcements.links"
                    position="center"
                />
                <LinkCell
                    v-else
                    class="flex w-full justify-center py-2"
                    href="/admin/announcements"
                    value="Show All Announcements"
                />
            </div>
        </div>

        <Modal v-model:view="showAnnouncement">
            <div class="flex w-full flex-col space-y-4 rounded-lg bg-white p-5">
                <div class="text-center text-2xl font-bold">
                    {{ selectedAnnouncement.title }}
                </div>

                <div class="text-sm font-medium">
                    Post Targets :
                    <span
                        v-for="(
                            target, index
                        ) in selectedAnnouncement.target_group"
                        :key="index"
                        class="mr-2 rounded-md bg-zinc-700 px-3 py-1 text-xs font-medium uppercase text-white"
                    >
                        {{ target }}
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

        <Modal v-model:view="showAddAnnouncement">
            <FormElement
                v-model:show-modal="showAddAnnouncement"
                title="Add Announcement"
                @submit="addAnnouncement"
            >
                <TextInput
                    v-model="form.title"
                    placeholder="Title"
                    class="w-full"
                />
                <TextInput
                    v-model="form.body"
                    placeholder="Body"
                    class="w-full"
                />

                <div class="">
                    <label
                        for="target-group"
                        class="block text-sm font-medium text-gray-700"
                        >Select target group :</label
                    >
                    <div
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                    >
                        <div
                            v-for="target in targetGroupOptions"
                            :key="target"
                            :class="{
                                'bg-blue-100 ':
                                    form.target_group.includes(target),
                            }"
                            class="cursor-pointer border-b-2 p-2"
                            @click="toggleSelection(target)"
                        >
                            {{ target }}
                        </div>
                    </div>
                    <p class="mt-4">You selected: {{ form.target_group }}</p>
                </div>

                <DatePicker
                    v-model="form.expires_on"
                    placeholder="Expires On"
                    class="w-full"
                />
            </FormElement>
        </Modal>
    </div>
</template>
<script setup>
import { computed, ref, toRefs, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import Item from "@/Views/Announcements/Item.vue";
import LinkCell from "@/Components/LinkCell.vue";
import Modal from "@/Components/Modal.vue";
import moment from "moment/moment";
import TextInput from "@/Components/TextInput.vue";
import EmptyView from "@/Views/EmptyView.vue";
import { debounce } from "lodash";
import Pagination from "@/Components/Pagination.vue";
import { SignalIcon } from "@heroicons/vue/20/solid";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FormElement from "@/Components/FormElement.vue";
import DatePicker from "@/Components/DatePicker.vue";

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
});

const { classStyle } = toRefs(props);

const Announcements = ["all", "students", "teachers", "guardians", "admins"];

const user = usePage().props.auth.user;
const isAdmin = computed(() => user.type === "admin");

const targetGroupOptions = [
    "all",
    "students",
    "teachers",
    "guardians",
    "admins",
];

function toggleSelection(target) {
    const index = this.form.target_group.indexOf(target);
    if (index < 0) {
        this.form.target_group.push(target);
    } else {
        this.form.target_group.splice(index, 1);
    }
}

const showAddAnnouncement = ref(false);

const form = ref({
    title: "",
    body: "",
    expires_on: new Date(),
    target_group: [],
});

const addAnnouncement = () => {
    form.value.expires_on = moment(form.value.expires_on).format("YYYY-MM-DD");
    router.post("/announcements/create", form.value, {
        onSuccess: () => {
            showAddAnnouncement.value = false;
            form.value = {
                title: "",
                body: "",
                expires_on: new Date(),
                target_group: [],
            };
        },
    });
};

const announcements = computed(() => usePage().props.announcements);
const computedAnnouncements = computed(() => {
    if (announcements.value.data) {
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
