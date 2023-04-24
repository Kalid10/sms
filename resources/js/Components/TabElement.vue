<template>

    <div class="flex w-full flex-col gap-3">

        <ul class="flex w-full items-center gap-4 overflow-auto">

            <li
                v-for="(tab, t) in tabs" :key="t"
                :class="[ activeTab === tab ? 'bg-brand-50' : '' ]"
                class="rounded-full px-4 py-2 transition-colors duration-300"
            >
                <button
                    :class="[ activeTab === tab ? 'text-brand-100' : 'text-gray-500' ]"
                    class="w-full whitespace-nowrap text-sm font-semibold transition-colors duration-300"
                    @click="setActiveTab(tab)"
                >
                    {{ tab }}
                </button>
            </li>

        </ul>

        <div class="w-full rounded-lg p-2">
            <slot v-if="! activeOnly" :name="toUnderscore(activeTab)">
                {{ activeTab }}
            </slot>
            <slot v-else :active="{ tab: activeTab, index: tabs.indexOf(activeTab) }">
                {{ activeTab }}
            </slot>
        </div>

    </div>

</template>

<script setup>

import {ref} from "vue";

const props = defineProps({
    tabs: {
        type: Array,
        required: true
    },
    activeOnly: {
        type: Boolean,
        default: false
    }
})

const activeTab = ref(props.tabs[0])

function setActiveTab(tab) {
    activeTab.value = tab
}

function toUnderscore(str) {
    return str.replace(' ', '_').toLowerCase()
}

</script>

<style scoped>
::-webkit-scrollbar {
    display: none;
}
</style>
