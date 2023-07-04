<template>
    <TableElement
        :columns="users_config"
        :data="
            users.data.map((user) => {
                return { ...user, active: true };
            })
        "
        actionable
        row-actionable
        selectable
        subtitle="List of users registered in the system"
        title="Users List"
    >
        <template #filter>
            <TextInput
                v-model="query"
                placeholder="Search for Users by Name or Email"
            />
        </template>

        <template #action="{ selected }">
            <div class="flex flex-row space-x-4">
                <div v-if="selected.selected" class="flex items-center gap-2">
                    <TertiaryButton
                        title="Move Items"
                        @click="moveItems(selected.items)"
                    />
                    <PrimaryButton
                        title="Update Items"
                        @click="updateItems(selected.items)"
                    />
                </div>
            </div>
        </template>

        <template #row-actions="{ row }">
            <Link
                :href="'/admin/users/' + row.id"
                class="flex flex-col items-center gap-1"
            >
                <EyeIcon
                    class="h-3 w-3 stroke-2 transition-transform duration-150 hover:scale-125"
                />
            </Link>
            <Link
                :href="'/admin/users/' + row.id + '/edit'"
                class="flex flex-col items-center gap-1"
            >
                <ArrowPathIcon
                    class="h-3 w-3 stroke-2 transition-all duration-150 hover:scale-125 hover:stroke-blue-700"
                />
            </Link>
            <Link
                :href="'/admin/users/' + row.id + '/delete'"
                class="flex flex-col items-center gap-1"
            >
                <ArchiveBoxXMarkIcon
                    class="h-3 w-3 stroke-2 transition-all duration-150 hover:scale-125 hover:stroke-red-700"
                />
            </Link>
        </template>

        <template #footer>
            <Pagination class="py-1" :links="users.links" position="center" />
        </template>
    </TableElement>
</template>
<script setup>
import TextInput from "@/Components/TextInput.vue";
import {
    ArchiveBoxXMarkIcon,
    ArrowPathIcon,
    EyeIcon,
} from "@heroicons/vue/24/outline";
import Pagination from "@/Components/Pagination.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";
import TableElement from "@/Components/TableElement.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";

const users = computed(() => {
    return usePage().props.users;
});

const showModal = ref(false);
const query = ref("");

function search() {
    router.get(
        "/admin/users",
        {
            search: query.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
}

const key = ref(0);
watch([query], () => {
    search();
});

const users_config = [
    {
        name: "Full Name",
        key: "name",
        link: "/admin/{type}s/{id}",
        class: "w-[35%]",
        align: "left",
    },
    {
        name: "Email",
        key: "email",
        link: "mailto:{email}",
        class: "w-[35%]",
        align: "left",
    },
    {
        name: "User Type",
        key: "type",
        type: "enum",
        options: ["admin", "teacher", "student", "guardian"],
    },
    {
        name: "Gender",
        key: "gender",
        type: "enum",
        options: ["male", "female"],
    },
    {
        name: "Active",
        key: "active",
        type: Boolean,
        class: "w-fit",
    },
];
</script>
<style scoped></style>
