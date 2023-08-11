<template>
    <div>
        <UserTableElement
            :filterable="false"
            :data="users"
            :selectable="false"
            :columns="config"
            actionable
            :row-actionable="true"
            class="broder w-full"
            subtitle="list of all Users"
            title="Users"
        >
            <template #action>
                <UserSearchTextInput
                    v-model="searchKey"
                    title="search"
                    actionable
                    :selectable="false"
                    placeholder="Name or Email"
                    class="w-full max-w-sm"
                    @keyup="search"
                ></UserSearchTextInput>
            </template>
            <template #all-actions>
                <div class="flex flex-row justify-end pr-6">
                    <UserSearchTextInput
                        class="w-1/3"
                        placeholder="search"
                        model-value=""
                    />
                </div>
            </template>
            <template #row-actions="{ row }">
                <Link
                    class="hover:underline"
                    title="view"
                    :href="'/roles/user/details?' + 'user_id=' + row.id"
                >
                    view
                </Link>
            </template>
        </UserTableElement>
    </div>
</template>

<script setup>
import UserSearchTextInput from "@/Components/TextInput.vue";
import UserTableElement from "@/Components/TableElement.vue";
import { debounce } from "lodash";
import { Link, router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const searchKey = ref(usePage().props.searchKey);
const search = debounce(() => {
    router.get(
        "/roles/users",
        { search: searchKey.value },
        { preserveState: true, replace: true }
    );
}, 300);
const users = computed(() => {
    return usePage().props.users.data;
});

const userDetail = (row) => {
    router.get("/roles/user/details", {
        user_id: row.id,
    });
};
const config = [
    {
        name: t("common.name"),
        key: "name",
        sortable: true,
        searchable: true,
    },
    {
        name: t("common.email"),
        key: "email",
        sortable: true,
        searchable: true,
    },
    {
        name: t("common.type"),
        key: "type",
        sortable: true,
        searchable: true,
    },
];
</script>
