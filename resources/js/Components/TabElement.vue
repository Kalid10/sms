<template>

    <ul class="flex w-full items-center gap-4 overflow-auto">

        <li
            v-for="(tab, t) in tabs" :key="t"
            :class="[ activeTab === tab ? 'bg-brand-50' : '' ]"
            class="rounded-full px-4 py-2 transition-colors duration-300"
        >
            <button :class="[ activeTab === tab ? 'text-brand-100' : 'text-gray-500' ]" class="w-full whitespace-nowrap text-sm font-semibold transition-colors duration-300" @click="setActiveTab(tab)">
                {{ tab }}
            </button>
        </li>

    </ul>

    <div class="h-52 w-full rounded-lg p-2">
        <slot :name="toUnderscores(activeTab)">
            {{ activeTab }}
        </slot>
    </div>

</template>

<script setup>

import {ref} from "vue";

const props = defineProps({
    tabs: {
        type: Array,
        required: true
    },
})

const activeTab = ref(props.tabs[0])

function setActiveTab(tab) {
    activeTab.value = tab
}

function toUnderscores(string) {
    return string.toLowerCase().replace(/ /g, '_')
}

</script>

<style scoped>
::-webkit-scrollbar { display: none; }
</style>
