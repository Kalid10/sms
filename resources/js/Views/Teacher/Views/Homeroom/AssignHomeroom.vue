<template>
    <div class="flex flex-col space-y-3 rounded-lg bg-white p-4 text-center">
        <div>
            <Title :title="$t('assignHomeroom.assignHomeroom')"/>
        </div>

        <div class="mx-auto mt-10 flex w-full flex-col space-y-4">
            <div v-if="isAssigned" class="flex w-full flex-col gap-5">
                <div class="flex w-full">
                    <span class="flex items-center gap-5 text-lg font-medium"
                        >Teacher: {{ teacher.user.name }} is currently assigned
                        to Grade:
                        <span
                            v-for="homeroom in teacher.homeroom"
                            :key="homeroom"
                            class="mr-2 cursor-pointer rounded-md bg-gray-200 text-gray-500"
                        >
                            <p class="px-3 py-2 text-sm font-bold">
                                {{ homeroom.batch?.level?.name }} -
                                {{ homeroom.batch?.section }}
                            </p>
                        </span>
                    </span>
                </div>
            </div>

            <div class="flex w-full flex-col justify-center space-y-4">
                <span class="text-md font-medium text-gray-500"
                    >
                    {{ $t('assignHomeroom.searchAndSelect')}}
                </span>
                <div>
                    <input
                        v-model="searchKey"
                        class="mb-2 rounded border border-gray-300 px-3 py-2"
                        type="text"
                        placeholder="Search for grade level"
                    />
                    <div
                        v-for="(batch, index) in batchOptions"
                        v-show="searchKey.length > 0"
                        :key="batch"
                        class="cursor-pointer py-2"
                        :class="
                            index % 2 === 0
                                ? 'bg-gray-100 px-10 hover:bg-gray-300'
                                : 'bg-white px-10 hover:bg-gray-300'
                        "
                        @click="selectBatch(batch)"
                    >
                        <p class="px-3 py-2">{{ batch.label }}</p>
                    </div>
                    <div
                        v-if="selectedBatch && selectedBatch.value"
                        class="flex w-full flex-col items-center justify-center gap-5 py-3"
                    >
                        <div
                            class="flex w-full flex-col items-center justify-center py-3"
                        >
                            <div class="flex w-full flex-col gap-5 py-3">
                                <h2 class="flex justify-center">
                                    Your are about to assign
                                    <span class="px-2 font-bold">
                                        {{ teacher.user.name }}
                                    </span>
                                    as a homeroom teacher to Grade:
                                </h2>
                                <p class="flex justify-center underline">
                                    {{ selectedBatch.label }}
                                </p>

                                <div class="flex items-center justify-center">
                                    <PrimaryButton
                                        :title="$t('assignHomeroom.assignHomeroom')"
                                        @click="assignHomeroom"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import Title from "@/Views/Teacher/Views/Title.vue";
import { debounce } from "lodash";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const batches = computed(() => usePage().props.batches);
const teacher = computed(() => usePage().props.teacher);

const isAssigned = computed(() => {
    return teacher?.value.homeroom[0];
});

const props = defineProps({
    url: {
        type: String,
        default: "/admin/teachers/homeroom?teacher_id=",
    },
});
const batchOptions = computed(() => {
    return batches.value.map((batch) => {
        return {
            label:
                batch.level.name +
                " " +
                batch.section +
                " - " +
                (batch?.homeroom_teacher?.teacher
                    ? batch?.homeroom_teacher?.teacher?.user.name
                    : "-"),
            value: batch.id,
        };
    });
});

const selectedBatch = ref(null);

const selectBatch = (item) => {
    selectedBatch.value = item;
};

const searchKey = ref("");

watch(searchKey, () => {
    search();
    selectedBatch.value = null;
});

const search = debounce(() => {
    router.get(
        props.url + teacher.value.id,
        {
            search: searchKey.value,
        },
        {
            only: ["batches"],
            preserveState: true,
            replace: true,
        }
    );
}, 300);

const assignHomeroom = () => {
    router.post("/teachers/assign/homeroom", {
        teacher_id: teacher.value.id,
        batch_id: selectedBatch.value.value,
        replace: false,
    });
};
</script>
