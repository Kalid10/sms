<template>
    <div v-if="absentees.length > 0" class="flex w-full">
        <TableElement
            :selectable="false"
            :filterable="false"
            :data="absenteesData"
            title="Student Notes"
        />
    </div>
    <div v-else class="flex w-full items-center justify-center">
        <EmptyView title="No Absentee Students Found" />
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import EmptyView from "@/Views/EmptyView.vue";

const absentees = computed(() => usePage().props.absentees);

const absenteesData = computed(() => {
    return absentees.value.map((absentee) => {
        return {
            reason: absentee.reason,
            student: absentee.user.name,
        };
    });
});
</script>
<style scoped></style>
