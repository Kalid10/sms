<template>
    <div
        class="relative flex flex-col space-y-3 rounded-lg bg-white p-4 text-center"
    >
        <div>
            <Title :title="$t('assignHomeroom.assignHomeroom')" />
        </div>

        <div class="mx-auto mt-10 flex w-full flex-col">
            <div
                class="scrollbar-hide flex max-h-[500px] w-full flex-col space-y-4 overflow-y-scroll"
            >
                <TextInput
                    v-model="searchKey"
                    type="text"
                    placeholder="Search for a grade"
                    class-style="focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-zinc-100 focus:border-2 rounded-lg border-gray-400 "
                />
                <div
                    v-for="(batch, index) in batchOptions"
                    v-show="searchKey.length"
                    :key="batch"
                    class="cursor-pointer"
                    :class="
                        index % 2 === 0
                            ? 'bg-brand-50/50 px-10 hover:bg-brand-200'
                            : 'bg-white px-10 hover:bg-brand-100'
                    "
                    @click="selectBatch(batch)"
                >
                    <p class="px-3 py-2">{{ batch.label }}</p>
                </div>

                <div
                    v-if="selectedBatch && selectedBatch.value"
                    class="absolute right-0 top-0 mx-3 flex w-4/12 items-center justify-center rounded"
                >
                    <div
                        class="flex w-full gap-5 rounded border-b border-black py-3"
                    >
                        <div class="flex items-center justify-center">
                            <div v-if="selectedBatch.label">
                                <p class="text-sm font-semibold">
                                    {{
                                        selectedBatch.label
                                            ? selectedBatch.label
                                            : selectedBatch.value
                                    }}
                                </p>

                                <div>
                                    <PrimaryButton
                                        :title="`Change to ${props.teacherName}`"
                                        @click="assignHomeroom"
                                    />
                                </div>
                            </div>

                            <div v-else>
                                <div>
                                    <PrimaryButton
                                        :title="`Assign ${props.teacherName} to ${selectedBatch.label}`"
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
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const emit = defineEmits(["close"]);

const props = defineProps({
    url: {
        type: String,
        default: "/admin/teachers/?",
    },
    teacherId: {
        type: Object,
        default: () => {},
    },
    teacherName: {
        type: Object,
        default: () => {},
    },
});

const batches = computed(() => usePage().props.batches);

const batchOptions = computed(() => {
    return batches?.value.map((batch) => {
        return {
            label:
                (batch?.homeroom_teacher?.teacher
                    ? batch?.homeroom_teacher?.teacher?.user.name
                    : "-") +
                " is assigned to " +
                batch.level.name +
                " " +
                batch.section,
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
        props.url ? props.url + props.teacherId : "/admin/teachers/?",
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
    router.post(
        "/teachers/assign/homeroom",
        {
            teacher_id: props.teacherId,
            batch_id: selectedBatch.value.value,
            replace: false,
        },

        {
            onSuccess: () => {
                selectedBatch.value = null;
                searchKey.value = "";
                emit("close");
                router.get(
                    props.url + props.teacherId,
                    {},
                    {
                        preserveState: true,
                    }
                );
            },
        }
    );

    emit("close", false);
};
</script>
