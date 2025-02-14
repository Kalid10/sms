<template>
    <div class="flex w-full flex-col gap-3">
        <ul
            class="flex w-full items-center gap-4 overflow-auto py-2"
            :class="[backgroundColor, centerTabs ? 'justify-center' : '']"
        >
            <li
                v-for="(tab, t) in tabs"
                :key="t"
                :class="[isTabActive(tab) ? 'bg-brand-400' : '']"
                class="mx-2 rounded-full px-4 py-2 transition-colors duration-300"
            >
                <button
                    :class="[
                        isTabActive(tab)
                            ? 'text-brand-text-500'
                            : inActiveTabText,
                    ]"
                    class="w-full whitespace-nowrap text-sm font-semibold capitalize transition-colors duration-300"
                    @click="setActiveTab(tab)"
                >
                    {{ getTabLabel(tab) }}
                </button>
            </li>
        </ul>

        <div class="w-full rounded-lg p-0">
            <slot v-if="!activeOnly" :name="toUnderscore(active)">
                {{ active }}
            </slot>
            <slot v-else :active="{ tab: active, index: tabs.indexOf(active) }">
                {{ active }}
            </slot>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    tabs: {
        type: Array,
        required: true,
    },
    activeOnly: {
        type: Boolean,
        default: false,
    },
    active: {
        type: [String, Number],
        default: "Home",
    },
    inActiveTabText: {
        type: String,
        default: "text-brand-text-50",
    },
    backgroundColor: {
        type: String,
        default: "",
    },
    objectList: {
        type: Boolean,
        default: false,
    },
    centerTabs: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["click", "update:active"]);

function setActiveTab(tab) {
    if (props.objectList) {
        emit("update:active", tab.key);
        return;
    }
    emit("update:active", tab);
}

function toUnderscore(str) {
    return str.replace(" ", "_").toLowerCase();
}

function isTabActive(tab) {
    if (props.objectList) {
        return props.active === tab.key;
    }
    return props.active === tab;
}

function getTabLabel(tab) {
    if (props.objectList) {
        return tab.label;
    }
    return tab;
}
</script>

<style scoped>
::-webkit-scrollbar {
    display: none;
}
</style>
