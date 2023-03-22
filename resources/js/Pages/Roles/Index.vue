<template>
    <div>
        <UserTableElement
            :data="users" :selectable=false :columns="config" :row-actionable="true"  class="broder w-full "
            subtitle="list of all Users" title="Users">
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
import RolesCheckbox from "@/Components/Checkbox.vue";
import {router, usePage} from "@inertiajs/vue3";
import { computed , onMounted, ref} from "vue";

// test imports
import Card from "@/Components/Card.vue";
const options = [
    { value: 'option1', label: 'Option 1' },
    { value: 'option2', label: 'Option 2' },
    { value: 'option3', label: 'Option 3' },
]
const selectedOptions = ref(['option1'])



const users = computed(() => {
    return usePage().props.users.data;
});

const isTrue = ref(false)

const userDetail = (row) => {
    router.get('/roles/user', {
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
