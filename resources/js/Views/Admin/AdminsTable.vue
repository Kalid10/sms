<template>
    <TableElement
        :filterable="false"
        :footer="false"
        :selectable="false"
        :header="false"
        :title="$t('adminsTable.tableElementTitle')"
        :subtitle="$t('adminsTable.tableElementSubtitle')"
        :data="admins"
        :columns="configAdmin"
    >

        <template #name-column="{ data }">
            <div class="flex items-start gap-2">
                <span class="text-sm font-light">{{ data }}</span>
            </div>
        </template>

        <template #email-column="{ data }">
            <div class="flex items-center gap-1">
                <span class="text-xs">
                {{ data }}
            </span>
            </div>
        </template>

        <template #role-column="{ data }">
            <div class="flex items-center gap-1">
                    <span class="text-xs font-semibold">
                {{ data }}
            </span>
            </div>
        </template>

        <template #type-column="{ data }">
            <div class="flex items-center gap-1">
                <span class="text-xs">
                {{ data }}
            </span>
            </div>
        </template>

        <template #updated_at-column="{ data }">
            <div class="flex items-center gap-1">
                <span class="text-xs text-gray-500">
            {{ $t('adminsTable.updated')}}  {{ data }}
            </span>
            </div>
        </template>

        <template #empty-data>
            <div class="flex flex-col items-center justify-center">
                <ExclamationTriangleIcon class="mb-2 h-6 w-6 text-negative-50"/>
                <p class="text-sm font-semibold">
                    {{ $t('adminsTable.noDataFound')}}
                </p>
            </div>
        </template>


    </TableElement>

</template>

<script setup>
import TableElement from "@/Components/TableElement.vue";
import {ExclamationTriangleIcon} from "@heroicons/vue/24/outline";
import SelectInput from "@/Components/SelectInput.vue";

const props = defineProps({
    admins: {
        type: String,
        required: true,
    },
    configAdmin: {
        type: String,
        required: true,
    },
});
</script>

<style scoped>

</style>
