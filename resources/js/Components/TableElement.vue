<template>

    <div class="flex w-full flex-col rounded-t-md border bg-white shadow-sm">

        <div v-if="titleHeader" class="flex flex-col sm:flex-row sm:items-center">
            <div class="flex flex-col justify-center p-4 sm:grow">
                <h3 :class="{ 'font-semibold': ! subtitle }" class="capitalize">{{ title }}</h3>
                <h5 v-if="subtitle" class="text-sm text-gray-500">
                    {{ subtitle }}
                </h5>
            </div>

            <div class="mb-4 min-w-fit px-4 sm:mb-0">
                <slot v-if="actionable" :selected="{ selected: anySelected, items: selectedItems }" name="action">
                    <PrimaryButton v-if="! anySelected" :click="() => {}" class="w-full" title="Download CSV"/>
                    <div v-else class="flex items-center gap-2">
                        <TertiaryButton class="w-full whitespace-nowrap" title="Update Users" @click="() => {}"/>
                        <PrimaryButton class="w-full whitespace-nowrap" title="Delete Users" @click="() => {}"/>
                    </div>
                </slot>
            </div>
        </div>

        <div class="mb-4 flex flex-col gap-4 px-4">
            <TextInput v-model="query" placeholder="Search for [... attributes]"/>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full">
                <tr>
                    <th
                        v-if="selectable"
                        class="h-10 w-[1%] border-y bg-neutral-50 px-3">
                        <Checkbox v-model="selectAll"/>
                    </th>
                    <th
                        v-for="(key, index) in (columns.length > 0 ? columns.map(item => item.name) : Object.keys(data[0]))"
                        :key="index"
                        class="h-10 whitespace-nowrap border-y bg-neutral-50 px-3 text-xs font-semibold uppercase text-neutral-700">
                        {{ key }}
                    </th>
                    <th
                        v-if="rowActionable"
                        class="h-10 w-fit whitespace-nowrap border-y bg-neutral-50 px-3 text-xs font-semibold uppercase text-neutral-700">
                        Actions
                    </th>
                </tr>
                <tr v-for="(item, index) in data" :key="index" class="border-b">
                    <th
                        v-if="selectable"
                        class="h-10 w-[1%] border-y border-l bg-white px-3">
                        <Checkbox v-model="items[index].selected"/>
                    </th>
                    <template
                        v-for="(key, i) in (columns.length > 0 ? columns.map(i => i.key) : Object.keys(item))"
                        :key="i"
                    >
                        <td
                            :class="[columns.length > 0 && !! columns[i]?.link ? 'cursor-pointer hover:underline hover:underline-offset-2' : '']"
                            class="h-10 whitespace-nowrap px-3 text-center text-sm"
                        >
                            <component
                                :is="cell(i, index).component" :value="item[key]"
                                v-bind="{ ...cell(i, index).props }"
                            />
                        </td>
                    </template>
                    <td
                        v-if="rowActionable"
                        class="flex h-10 min-w-fit items-center justify-center gap-2 text-center text-sm [&>*]:mr-3 [&>*]:text-xs [&>:nth-child(1)]:ml-3">
                        <slot :row="item" name="row-actions">
                            <Link :href="'/' + item.id" class="">View</Link>
                            <Link :href="'update/' + item.id" class="">Update</Link>
                            <Link :href="'delete/' + item.id" class="">Delete</Link>
                        </slot>
                    </td>
                </tr>
            </table>
        </div>

        <div class="flex gap-3 bg-neutral-50 p-4">
            <TertiaryButton :click="() => {}" class="w-full" title="Previous"/>
            <TertiaryButton :click="() => {}" class="w-full" title="Next"/>
        </div>

    </div>

</template>

<script setup>
import {ref, watch, computed, defineAsyncComponent} from "vue"
import Checkbox from "@/Components/Checkbox.vue"
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import {Link} from '@inertiajs/vue3'

const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
    title: {
        type: String,
        default: null
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
    rowActionable: {
        type: Boolean,
        default: false
    },
    columns: {
        type: Array, // Array of objects of type { name: String, key: String, link: String }. opt: link
        default: () => []
    }
})

const titleHeader = computed(() => props.title || props.subtitle)
const selectAll = ref(false)
const anySelected = computed(() => items.value.some((item) => item.selected))
const selectedItems = computed(() => items.value.filter((item) => item.selected).map((item) => props.data[item.id]))
const items = ref(props.data.map((item, index) => {
    return {
        id: index,
        selected: false
    }
}))

function getLink(link, index) {
    const matches = link?.match(/{(.*?)}/g) || []
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

function cell(columnIndex, rowIndex) {

    if (!!props.columns[columnIndex]?.link) {
        return {
            component: defineAsyncComponent(() => import('@/Components/LinkCell.vue')),
            props: {
                href: getLink(props.columns[columnIndex]?.link, rowIndex)
            }
        }
    }

    switch (props.columns[columnIndex]?.type) {

        case Boolean:
            return {
                component: defineAsyncComponent(() => import('@/Components/BooleanCell.vue')),
            };

        case 'enum':
            return {
                component: defineAsyncComponent(() => import('@/Components/EnumCell.vue')),
                props: {
                    options: props.columns[columnIndex]?.options
                }
            }

        default:
            return {
                component: defineAsyncComponent(() => import('@/Components/TextCell.vue')),
            }

    }
}

const query = ref('')
const perPage = ref(5)
</script>

<style scoped>

</style>
