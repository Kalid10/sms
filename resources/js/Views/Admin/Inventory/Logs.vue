<template>
    <div class="flex w-full flex-col items-center space-y-4 py-5 pt-10">
        <TableElement
            header-style="!bg-brand-400 text-white"
            class="h-fit !w-full !rounded-lg !bg-transparent p-4 !shadow-none"
            :data="mappedTransactions"
            :columns="columns"
            :selectable="false"
        >
            <template #filter>
                <div class="text-center text-3xl font-semibold">
                    {{ $t("inventoryLogs.latestTransaction") }}
                </div>
            </template>
            <template #status-column="{ data }">
                <span
                    class="rounded-lg px-3 py-1 font-semibold"
                    :class="
                        data === 'pending'
                            ? 'bg-indigo-400 text-white'
                            : data === 'approved'
                            ? 'bg-emerald-400 text-black'
                            : data === 'filled'
                            ? 'bg-green-500 text-white'
                            : 'bg-red-600 text-white'
                    "
                >
                    {{ data }}
                </span>
            </template>
            <Pagination :links="transactions.links" />
        </TableElement>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import TableElement from "@/Components/TableElement.vue";
import moment from "moment";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const transactions = computed(() => usePage().props.transactions);

const mappedTransactions = computed(() =>
    usePage().props.transactions.data.map((item) => {
        return {
            recipient_user: item.recipient.name,
            provider_user: item.provider.name,
            quantity: item.quantity,
            date: moment(item.date).format("dddd MMM DD, YYYY"),
            name: item.item.name,
            status: item.status,
        };
    })
);

const columns = [
    {
        name: t("common.name"),
        key: "name",
        class: "font-semibold",
    },
    {
        name: t("common.quantity"),
        key: "quantity",
    },
    {
        name: t("inventoryLogs.status"),
        key: "status",
        class: "font-medium uppercase",
        type: "custom",
    },
    {
        name: t("inventoryLogs.to"),
        key: "recipient_user",
    },
    {
        name: t("inventoryLogs.from"),
        key: "provider_user",
    },
    {
        name: t("inventoryLogs.date"),
        key: "date",
    },
];
</script>
<style scoped></style>
