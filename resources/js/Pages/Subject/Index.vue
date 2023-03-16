<template>
    <subject-table-element :data="users" actionable title="Subject">
        <template #all-actions>
            <div class="flex flex-row space-x-4">
                <subject-text-input class="w-1/2" placeholder="search"/>
                <subject-primary-button :click="() => {}" class="w-1/2" title="Add"/>
            </div>
        </template>
    </subject-table-element>

</template>
<script setup>
import SubjectTableElement from "@/Components/TableElement.vue";
import {users} from "@/fake";
import SubjectPrimaryButton from "@/Components/PrimaryButton.vue";
import SubjectTextInput from "@/Components/TextInput.vue";</script>
