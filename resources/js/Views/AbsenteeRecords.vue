<template>
    <div class="h-full">
        <div
            v-if="absenteeRecords?.data.length"
            class="flex w-full flex-col space-y-2"
        >
            <div class="py-2 text-center text-xl font-medium">
                {{ $t("absenteeRecords.absenteeRecords") }}
            </div>
            <div
                v-for="(item, index) in absenteeRecords.data"
                :key="index"
                class="flex w-full justify-evenly space-x-2 rounded-lg px-3 py-4 text-xs"
                :class="index % 2 === 0 ? 'bg-brand-50' : ''"
            >
                <div class="w-5/12">
                    {{
                        moment(item.batch_session.date).format(
                            "dddd, DD MMMM, YYYY"
                        )
                    }}
                </div>
                <div class="w-2/12">
                    {{ $t("absenteeRecords.period") }}
                    {{ item.batch_session.school_period.name }}
                </div>
                <div class="w-2/12">
                    {{
                        item.batch_session.batch_schedule.batch_subject.subject
                            .short_name
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
        <div v-else class="flex h-full items-center justify-center">
            <EmptyView :title="$t('absenteeRecords.noAbsentee')" />
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
