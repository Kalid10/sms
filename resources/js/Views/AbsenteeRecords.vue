<template>
    <div>
        <div
            v-if="absenteeRecords?.data.length"
            class="flex w-full flex-col space-y-2"
        >
            <div class="text-xl font-medium">Absentee Records</div>
            <div
                v-for="(item, index) in absenteeRecords.data"
                :key="index"
                class="flex w-full justify-evenly space-x-2 rounded-lg p-3 text-sm"
                :class="index % 2 === 1 ? 'bg-gray-50' : ''"
            >
                <div class="w-5/12">
                    {{
                        moment(item.batch_session.date).format(
                            "dddd, DD MMMM, YYYY"
                        )
                    }}
                </div>
                <div class="w-2/12">
                    Period
                    {{ item.batch_session.school_period.name }}
                </div>
                <div class="w-2/12">
                    {{
                        item.batch_session.batch_schedule.batch_subject.subject
                            .full_name
                    }}
                </div>
                <div class="">
                    {{ item.batch_session.teacher.user.name }}
                </div>
            </div>
            <Pagination
                :links="absenteeRecords.links"
                :preserve-state="true"
                position="center"
            />
        </div>
        <div v-else class="py-5">
            <EmptyView title="No absentee records found!" />
        </div>
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import moment from "moment";
import Pagination from "@/Components/Pagination.vue";
import EmptyView from "@/Views/EmptyView.vue";

const props = defineProps({
    absenteeRecords: {
        type: Object,
        default: null,
    },
});

const absenteeRecords =
    props.absenteeRecords ?? computed(() => usePage().props.absentee_records);
</script>

<style scoped></style>
