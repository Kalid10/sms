<template>
    <div>
        <UserTableElement
            :data="users" :selectable=false :columns="config" actionable :row-actionable="true"  class="broder w-full "
            subtitle="list of all Users" title="Users">
            <template #action>
<!--                <UserSearchTextInput-->
<!--                    v-model="searchKey"-->
<!--                    title="search"-->
<!--                    actionable-->
<!--                    :selectable="false"-->
<!--                    placeholder="Name or Email"-->
<!--                    class="w-10"-->
<!--                    @keyup="search"-->
<!--                ></UserSearchTextInput>-->

            </template>
            <template #all-actions>
                <div class="flex flex-row justify-end pr-6">
                    <UserSearchTextInput class="w-1/3" placeholder="search" model-value=""/>
                </div>
            </template>
            <template #row-actions="{row}">
                <div class="flex flex-row justify-end pr-6">
                    <ViewTertiaryButton class="" title="view" @click="userDetail(row)"/>
                </div>
            </template>
        </UserTableElement>

    </div>

</template>

<script setup>
import UserSearchTextInput from "@/Components/TextInput.vue";
import UserTableElement from  "@/Components/TableElement.vue";
import ViewTertiaryButton from "@/Components/TertiaryButton.vue";
import {debounce} from "lodash";
import {router, usePage} from "@inertiajs/vue3";
import { computed , ref} from "vue";


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

const isTrue = ref(false)

const userDetail = (row) => {
    router.get('/roles/user/details', {
        user_id: row.id
    },{
        onSuccess: () =>{
            console.log("Success")
        },
        onError: (error) =>{
            console.log("Error")
            console.log(error)
        }
    });
}

const config =[
    {
        name: 'Name',
        key: 'name',
        sortable: true,
        searchable: true,
    },
    {
        name: 'Email',
        key: 'email',
        sortable: true,
        searchable: true,
    },
    {
        name: 'Role',
        key: 'type',
        sortable: true,
        searchable: true,
    },
];


</script>
