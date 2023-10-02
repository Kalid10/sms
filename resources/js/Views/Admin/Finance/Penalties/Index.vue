<template>
    <TableElement
        :data="mappedPenalties"
        :columns="columns"
        :selectable="false"
        :filterable="false"
        header-style="!bg-brand-400 text-white"
        class="h-full !w-8/12 !rounded-lg p-4 shadow-sm"
    >
        <template #table-header>
            <div class="flex w-full justify-between py-5">
                <div class="text-xl font-semibold capitalize">
                    {{ $t("fees.penalties") }}
                </div>
                <PrimaryButton
                    class="flex h-fit items-center justify-center space-x-1 bg-brand-450"
                    :title="$t('fees.addPenalty')"
                    @click="showAddPenaltyForm = true"
                >
                    <SquaresPlusIcon class="w-4 stroke-white stroke-2" />
                    <span>{{ $t("fees.addPenalty") }}</span>
                </PrimaryButton>
            </div>
        </template>
    </TableElement>

    <Modal v-model:view="showAddPenaltyForm">
        <AddPenalty @close="showAddPenaltyForm = false" />
    </Modal>
</template>
<script setup>
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import moment from "moment";
import Modal from "@/Components/Modal.vue";
import AddPenalty from "@/Views/Admin/Finance/Penalties/AddPenalty.vue";
import { upperCase } from "lodash";
import { useI18n } from "vue-i18n";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { SquaresPlusIcon } from "@heroicons/vue/24/outline";

const { t } = useI18n();
const penalties = computed(() => usePage().props.penalties);

const showAddPenaltyForm = ref(false);
const mappedPenalties = computed(() => {
    return penalties.value.map((penalty) => {
        return {
            type: upperCase(penalty.type),
            amount: penalty.amount,
            date: moment(penalty.created_at).format("MMMM DD, YYYY"),
            last_updated: moment(penalty.updated_at).format("MMMM DD, YYYY"),
        };
    });
});

const columns = [
    {
        key: "type",
        name: t("common.type"),
    },
    {
        key: "amount",
        name: t("fees.amount"),
    },
    {
        key: "date",
        name: t("addPenalty.date"),
    },
    {
        key: "last_updated",
        name: t("fees.lastUpdated"),
    },
];
</script>
<style scoped></style>
