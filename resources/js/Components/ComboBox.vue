<template>
    <div class=" mt-2 h-fit border border-blue-400 md:w-2/5 ">
        <div class=" ">
            <PrimaryButton class="inline-flex items-center justify-between px-4 py-3 text-center text-sm font-medium text-white"  type="button" @click="showDropdown=!showDropdown">Assign new Role
                <chevron-down class="h-10 w-10" />
            </PrimaryButton>


            <!-- Dropdown menu -->
            <div v-if="showDropdown" class="z-10 w-full bg-white">
                <div class="p-3">
                    <SearchTextInput type="text" class="block w-full text-sm text-gray-900 " placeholder="Search roles"></SearchTextInput>
                </div>
                <ul class="h-48 overflow-y-auto px-3 pb-3 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownSearchButton">
                    <li>
                        <div class="flex items-center rounded p-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <input id="checkbox-item-11" type="checkbox" value="" class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-500 dark:bg-gray-600 dark:ring-offset-gray-700 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-700">
                            <label for="checkbox-item-11" class="ml-2 w-full rounded text-sm font-medium text-gray-900 dark:text-gray-300">Admin</label>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center rounded p-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <input id="checkbox-item-12" checked type="checkbox" value="" class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-500 dark:bg-gray-600 dark:ring-offset-gray-700 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-700">
                            <label for="checkbox-item-12" class="ml-2 w-full rounded text-sm font-medium text-gray-900 dark:text-gray-300">Manage Teachers</label>
                        </div>
                    </li>
                </ul>
                <div class="flex justify-end">
                    <PrimaryButton title="Add" class="m-4"></PrimaryButton>
                </div>
            </div>
        </div>
    </div>


</template>

<script setup>
import PrimaryButton from "./PrimaryButton";
import SearchTextInput from "./SearchRoleTextInput";
import {ref} from "vue";
import { ChevronDown } from '@heroicons/vue/outline'

defineProps({
    title: {
        type: String,
    }
})

const showDropdown = ref(false);
</script>
