<template>
    <TableElement
        :columns="users_config"
        :data="
            users.data.map((user) => {
                return { ...user, active: !user.is_blocked };
            })
        "
        actionable
        row-actionable
        selectable
        :title="$t('usersTable.tableElementTitle')"
        :subtitle="$t('usersTable.tableElementSubtitle')"
    >
        <template #filter>
            <TextInput
                v-model="query"
                :placeholder="$t('usersTable.queryPlaceholder')"
            />
        </template>

        <template #name-column="{ data }">
            <div class="flex items-start gap-2">
                <span class="font-light">{{ data }}</span>
            </div>
        </template>
        <template #email-column="{ data }">
            <div class="flex items-center gap-1">
                <span class="text-sm font-light">
                    {{ data }}
                </span>
            </div>
        </template>

        <template #action="{ selected }">
            <div class="flex flex-row space-x-4">
                <div v-if="selected.selected" class="flex items-center gap-2">
                    <TertiaryButton
                        :title="$t('usersTable.moveItems')"
                        @click="moveItems(selected.items)"
                    />
                    <PrimaryButton
                        :title="$t('usersTable.updateItems')"
                        @click="updateItems(selected.items)"
                    />
                </div>
            </div>
        </template>

        <template #row-actions="{ row }">
            <div v-if="row.id !== $page.props.auth.user.id">
                <button
                    v-if="row.is_blocked === 0"
                    @click="toggleDialogBox(row)"
                >
                    <LockOpenIcon class="h-4 w-4" />
                </button>
                <button v-else @click="toggleDialogBox(row)">
                    <LockClosedIcon class="h-4 w-4" />
                </button>
            </div>
            <div v-else>
                <span class="text-sm font-light"> </span>
            </div>
        </template>

        <template #footer>
            <Pagination class="py-1" :links="users.links" position="center" />
        </template>
    </TableElement>

    <DialogBox
        v-if="isDialogBoxOpen"
        open
        @confirm="blockUser"
        @abort="isDialogBoxOpen = false"
    >
        <template #icon>
            <NoSymbolIcon />
        </template>

        <template #title> Block User</template>
        <template #description>
            You are about to block this {{ selectedUserInformation.name }}. Are
            you sure you want to continue?
        </template>
        <template #action> Yes</template>
    </DialogBox>
</template>
<script setup>
import TextInput from "@/Components/TextInput.vue";
import Pagination from "@/Components/Pagination.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";
import TableElement from "@/Components/TableElement.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import {
    LockClosedIcon,
    LockOpenIcon,
    NoSymbolIcon,
} from "@heroicons/vue/24/outline";

import { useI18n } from "vue-i18n";
import DialogBox from "@/Components/DialogBox.vue";

const { t } = useI18n();
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

const selectedUser = ref(null);

const selectedUserInformation = ref(null);

const isDialogBoxOpen = ref(false);

const blockForm = useForm({
    user_id: null,
});

function toggleDialogBox(row) {
    isDialogBoxOpen.value = !isDialogBoxOpen.value;
    blockForm.user_id = row.id;
    selectedUser.value = row.id;
    selectedUserInformation.value = row;
}

const blockUser = () => {
    blockForm.post("/admin/user/block-unblock", {
        onFinish: () => {
            selectedUser.value = null;
            selectedUserInformation.value = null;
            isDialogBoxOpen.value = false;
        },
    });
};

const key = ref(0);
watch([query], () => {
    search();
});

const users_config = [
    {
        name: t("usersTable.fullName"),
        key: "name",
        // link: "/admin/{type}s/{id}",
        class: "w-[35%]",
        align: "left",
        type: "custom",
    },
    {
        name: t("usersTable.email"),
        key: "email",
        // link: "mailto:{email}",
        class: "w-[35%]",
        align: "left",
        type: "custom",
    },
    {
        name: t("usersTable.userType"),
        key: "type",
        type: "enum",
        options: [
            t("usersTable.options[0]"),
            t("usersTable.options[1]"),
            t("usersTable.options[2]"),
            t("usersTable.options[3]"),
        ],
    },
    {
        name: t("usersTable.gender"),
        key: "gender",
        type: "enum",
        options: ["male", "female"],
    },
    {
        name: t("usersTable.active"),
        key: "active",
        type: Boolean,
        class: "w-fit",
    },
];
</script>
<style scoped></style>
