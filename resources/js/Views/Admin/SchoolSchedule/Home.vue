<template>
    <div
        class="flex w-full flex-col items-center justify-between space-y-4 rounded-lg bg-white px-5 py-3 shadow-sm lg:flex-row lg:space-x-10"
    >
        <Loading v-if="showLoading" is-full-screen />
        <div
            v-if="schoolSchedule.length"
            class="flex h-full w-full flex-col items-center justify-evenly space-y-2 lg:w-8/12"
        >
            <div class="w-full px-3 text-2xl font-medium">
                {{ $t("common.schoolSchedules") }}
                <span class="text-sm font-normal"
                    >( {{ moment(startDate).format("ddd MMM DD YYYY") }} -
                    {{ moment(endDate).format("ddd MMM DD YYYY") }})</span
                >
            </div>

            <div class="flex w-full flex-wrap justify-between px-3">
                <div
                    v-for="(item, index) in schoolSchedule"
                    :key="index"
                    class="m-3 flex w-5/12 flex-col justify-center space-y-3 rounded-lg bg-brand-50/70 px-2 py-3 shadow-sm"
                >
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center text-[0.65rem] font-light"
                        >
                            <div
                                class="h-1.5 w-1.5 rounded-full bg-orange-300"
                            />
                            <div class="px-1">
                                {{
                                    moment(item.start_date).format(
                                        "ddd MMMM DD YYYY"
                                    )
                                }}
                            </div>
                        </div>

                        <!--                        Todo: Open the clicked schedule on schedules page-->
                        <EyeIcon
                            class="w-3 cursor-pointer text-zinc-700 hover:scale-105 hover:text-zinc-800"
                            @click="router.get('/admin/schedules')"
                        />
                    </div>
                    <div class="text-sm font-medium">
                        {{ item.title }}
                    </div>
                    <div class="text-xs font-light">
                        {{ item.body }}
                    </div>
                </div>
            </div>

            <LinkCell value="View All Schedules" href="/admin/schedules" />
        </div>

        <EmptyView
            v-else
            class="flex h-full w-full flex-col items-center lg:w-8/12"
            title="No School Schedules Found!"
        />

        <div class="w-full lg:w-3/12">
            <DatePicker
                v-model:start-date="startDate"
                v-model:end-date="endDate"
                visible
                range
                class="w-full"
                @change="filterSchedule"
            />
        </div>
    </div>
</template>
<script setup>
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";

import moment from "moment";
import { EyeIcon } from "@heroicons/vue/20/solid";
import DatePicker from "@/Components/DatePicker.vue";
import LinkCell from "@/Components/LinkCell.vue";
import { debounce } from "lodash";
import Loading from "@/Components/Loading.vue";
import EmptyView from "@/Views/EmptyView.vue";

const schoolSchedule = computed(() => usePage().props.school_schedule);
const startDate = ref(new Date());
const endDate = ref(new Date());
const showLoading = ref(false);

const filterSchedule = debounce(() => {
    showLoading.value = true;

    router.get(
        "admin",
        {
            start_date: startDate.value,
            end_date: endDate.value,
        },
        {
            preserveState: true,
            replace: true,
            only: ["school_schedule"],
            onFinish: () => {
                showLoading.value = false;
            },
        }
    );
}, 300);
watch(
    [startDate, endDate],
    ([newStartDate, newEndDate], [oldStartDate = null, oldEndDate = null]) => {
        if (
            oldStartDate !== null &&
            !moment(newStartDate).isSame(oldStartDate)
        ) {
            filterSchedule();
        }
        if (oldEndDate !== null && !moment(newEndDate).isSame(oldEndDate)) {
            filterSchedule();
        }
    },
    { immediate: true }
);
</script>
<style scoped></style>
