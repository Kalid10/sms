<template>
    <div
        class="flex min-h-screen w-full flex-col items-center space-y-5 bg-gray-50 py-5"
    >
        <Title class="w-11/12" title="School Schedules" />
        <div class="flex w-11/12 justify-between">
            <div class="flex w-6/12 justify-between space-x-10 bg-gray-50">
                <div
                    class="h-fit w-full rounded-lg bg-white px-5 py-3 shadow-sm"
                >
                    <div class="flex w-full items-center justify-between">
                        <div
                            class="flex w-full flex-col justify-center space-y-2 py-2"
                        >
                            <div class="pl-1 text-sm font-light">
                                From (
                                {{ moment(startDate).format(" MMM DD YYYY") }}
                                -
                                {{ moment(endDate).format(" MMM DD YYYY") }} )
                            </div>
                        </div>
                        <TextInput
                            v-model="query"
                            class="w-4/5"
                            placeholder="Search for schedule"
                        />
                    </div>

                    <div
                        class="mt-3 flex flex-col justify-center divide-y divide-gray-100"
                    >
                        <div
                            v-for="(item, index) in schoolSchedule.data"
                            :key="index"
                        >
                            <SchoolScheduleItem :school-schedule="item" />
                        </div>
                    </div>

                    <Pagination
                        class="py-3"
                        position="center"
                        :links="schoolSchedule.links"
                    />
                </div>
            </div>
            <div class="mt-5 flex h-fit w-5/12 flex-col items-center space-y-6">
                <date-picker
                    v-model:start-date="startDate"
                    v-model:end-date="endDate"
                    visible
                    range
                    class="w-7/12"
                    @change="filterSchedule"
                />
                <SecondaryButton
                    v-if="isAdmin()"
                    title="Add Event"
                    class="w-7/12 rounded-lg bg-zinc-800 !py-2 text-white"
                    @click="showModalToggle"
                />
            </div>
        </div>
    </div>
    <SchoolScheduleAdd v-if="showAddModal" />

    <Loading v-if="showLoading" is-full-screen />
</template>
<script setup>
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import DatePicker from "@/Components/DatePicker.vue";
import Pagination from "@/Components/Pagination.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SchoolScheduleItem from "@/Views/Admin/Schedule/SchoolScheduleItem.vue";
import Title from "@/Views/Teacher/Views/Title.vue";
import { debounce } from "lodash";
import Loading from "@/Components/Loading.vue";
import moment from "moment";
import TextInput from "@/Components/TextInput.vue";
import SchoolScheduleAdd from "@/Views/Admin/GettingStarted/Schedule/SchoolSchedule.vue";
import { isAdmin, isTeacher } from "@/utils";

const schoolSchedule = computed(() => usePage().props.school_schedule);
const filters = computed(() => usePage().props.filters);
const startDate = ref(new Date(filters.value?.start_date ?? moment()));
const endDate = ref(
    new Date(filters.value?.end_date ?? moment(startDate.value).add(1, "week"))
);

const showAddModal = ref(false);

function showModalToggle() {
    showAddModal.value = !showAddModal.value;
}

const showLoading = ref(false);
const query = ref("");
const url = ref(isTeacher() ? "/teacher/school-schedule" : "/admin/schedules");

function search() {
    router.get(
        url.value,
        {
            search: query.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
}

const key = ref(0);
watch([query], () => {
    search();
});

const filterSchedule = debounce(() => {
    showLoading.value = true;

    router.get(
        url.value,
        {
            start_date: startDate.value,
            end_date: endDate.value,
        },
        {
            preserveState: true,
            replace: true,
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
