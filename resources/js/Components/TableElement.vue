<template>

    <div class="flex w-full flex-col gap-3 rounded-t-md border bg-white shadow-sm">

        <div class="px-4 pt-4">
            <h3 :class="{ 'font-semibold': ! subtitle }" class="capitalize">{{ title }}</h3>
            <h5 v-if="subtitle" class="text-sm text-gray-500">
                {{ subtitle }}
            </h5>
        </div>

        <slot v-if="actionable" name="all-actions">
            <PrimaryButton v-if="! anySelected" :click="() => {}" class="mx-4" title="Download CSV"/>
            <div v-else class="mx-4 flex items-center gap-2">
                <slot name="selected-actions">
                    <TertiaryButton :click="() => {}" class="w-full" title="Update Users"/>
                    <PrimaryButton :click="() => {}" class="w-full" title="Delete Users"/>
                </slot>
            </div>
        </slot>

        <div class="w-full overflow-x-auto">
            <table class="w-full">
                <tr>
                    <th
                        v-if="selectable"
                        class="h-10 border-y bg-neutral-50 px-3 text-sm font-semibold">
                        <Checkbox v-model="selectAll"/>
                    </th>
                    <th
                        v-for="(key, index) in (columnsConfig.length > 0 ? columnsConfig.map(item => item.name) : Object.keys(data[0]))"
                        :key="index"
                        class="h-10 whitespace-nowrap border-y bg-neutral-50 px-3 text-sm font-semibold text-neutral-700">
                        {{ key }}
                    </th>
                </tr>
                <tr v-for="(item, index) in data" :key="index" class="border-b">
                    <th
                        v-if="selectable"
                        class="h-10 border-y px-3 text-sm font-semibold">
                        <Checkbox v-model="items[index].selected"/>
                    </th>
                    <td
                        v-for="(key, i) in (columnsConfig.length > 0 ? columnsConfig.map(i => i.key) : Object.keys(item))"
                        :key="i"
                        :class="[columnsConfig.length > 0 && !! columnsConfig[i]?.link ? 'cursor-pointer hover:underline hover:underline-offset-2' : '']"
                        class="h-10 whitespace-nowrap px-3 text-center text-sm"
                    >
                        <Link v-if="!! columnsConfig[i]?.link" :href="getLink(columnsConfig[i]?.link, index)">
                            {{ item[key] }}
                        </Link>
                        <template v-else>
                            {{ item[key] }}
                        </template>
                    </td>
                </tr>
            </table>
        </div>

        <div class="-mt-3 flex gap-3 bg-neutral-50 p-4">
            <TertiaryButton :click="() => {}" class="w-full" title="Previous"/>
            <TertiaryButton :click="() => {}" class="w-full" title="Next"/>
        </div>

    </div>

</template>

<script setup>
import {ref, watch, computed} from "vue"
import {Link} from '@inertiajs/vue3'
import Checkbox from "@/Components/Checkbox.vue"
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";

const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
    title: {
        type: String,
        required: true
    },
    subtitle: {
        type: String,
        default: null
    },
    selectable: {
        type: Boolean,
        default: true,
    },
    actionable: {
        type: Boolean,
        default: false
    },
    columnsConfig: {
        type: Array,
        default: () => []
    }
})


const selectAll = ref(false)
const anySelected = computed(() => items.value.some((item) => item.selected))
const items = ref(props.data.map((item, index) => {
    return {
        id: index,
        selected: false
    }
}))

function getLink(link, index) {
    const matches = link.match(/{(.*?)}/g) || []
    let newLink = link
    matches.forEach((match) => {
        const key = match.replace('{', '').replace('}', '')
        newLink = newLink.replace(match, props.data[index][key])
    })
    return newLink
}

watch(selectAll, (value) => {
    if (selectAll.value) {
        items.value = items.value.map((item) => {
            return {
                ...item,
                selected: true
            }
        })
    } else {
        items.value = items.value.map((item) => {
            return {
                ...item,
                selected: false
            }
        })
    }
})
</script>

<style scoped>

</style>
