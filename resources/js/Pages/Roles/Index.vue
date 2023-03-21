<template>
    <div>
        <UserTableElement
            :data="users" :selectable=false :columns="config" :row-actionable="true"  class="broder w-full "
            subtitle="list of all Users" title="Users">
            <template #all-actions>
                <div class="flex flex-row justify-end pr-6">
                    <role-text-input class="w-1/3" placeholder="search"/>
                </div>
            </template>
            <template #row-actions="{row}">
                <div class="flex flex-row justify-end pr-6">
                    <ViewTertiaryButton class="" title="view" @click="UserDetail(row)"/>
                </div>
            </template>
        </UserTableElement>

    </div>


<!--    &lt;!&ndash; test components &ndash;&gt;-->

<!--    <Toggle v-model="isTrue"></Toggle>-->
<!--    <DialogBox type="delete" delete-title="Delete Zeget from you list" delete-text="Bro what are you waiting for ...">-->
<!--        <p class="  -sm text-gray-500">-->
<!--            Are you sure you want to delete this user from your list? All of your data will be <span class="font-bold text-red-500">permanently removed.  </span> This action-->
<!--            cannot be undone.-->
<!--        </p>-->
<!--    </DialogBox>-->
<!--    <ComboBox></ComboBox>-->
<!--    &lt;!&ndash; <GroupCheckBox></GroupCheckBox> &ndash;&gt;-->
<!--    &lt;!&ndash; <Notification title="group checkbox title"></Notification> &ndash;&gt;-->
<!--    &lt;!&ndash; <UserRoles></UserRoles> &ndash;&gt;-->


</template>

<script setup>
import RoleTextInput from "@/Components/TextInput.vue";
import UserTableElement from  '@/Components/TableElement.vue';
import ViewTertiaryButton from "@/Components/TertiaryButton.vue";
import {router, usePage} from "@inertiajs/vue3";
import { computed , onMounted, ref} from "vue";

// // test imports
// import Toggle from "@/Components/Toggle.vue";
// import DialogBox from "@/Components/DialogBox.vue";
// // import GroupCheckBox from "@/Components/GroupCheckBox.vue";
// import ComboBox from "@/Components/ComboBox.vue"
// // import Notification from "@/Components/Notification.vue";
// import UserRoles from "@/Pages/Roles/UserRoles.vue";
// // import GroupCheckBox from "@/Components/GroupCheckBox.vue";


const users = computed(() => {
    return usePage().props.users.data;
});

const isTrue = ref(false)

const UserDetail = (row) => {
    router.get('roles/user/' + row.id);
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
