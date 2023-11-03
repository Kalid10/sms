<template>
    <div
        class="relative flex flex-col space-y-5 rounded-lg bg-white p-4 text-center"
    >
        <div><Title :title="$t('assignHomeroom.assignHomeroom')" /></div>

        <div
            v-if="selectedBatch && selectedBatch.value"
            class="flex items-center justify-center rounded-lg p-3"
        >
            <div
                v-if="selectedBatch.batch"
                class="flex w-full flex-col items-center space-y-4 rounded-lg border-2 border-red-500 p-3 text-sm"
            >
                <span
                    class="flex items-center justify-center space-x-1 text-xl font-bold"
                >
                    <ExclamationTriangleIcon class="w-6" />
                    <span>Caution </span>
                </span>
                <p class="font-medium">
                    Do you want to replace
                    <span
                        class="-skew-x-3 bg-brand-100 px-2 font-bold italic group-hover:bg-brand-300"
                    >
                        {{ selectedBatch.homeroom }}
                    </span>
                    with
                    <span
                        class="-skew-x-3 bg-brand-100 px-2 font-bold italic group-hover:bg-brand-300"
                    >
                        {{ teacherName }}
                    </span>
                    for
                    <span class="italic">
                        {{ selectedBatch.batch }}
                    </span>
                    ?
                </p>
                <PrimaryButton
                    title="Change Homeroom"
                    class="bg-red-600"
                    @click="assignHomeroom"
                />
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

        <div class="mx-auto mt-10 flex w-full flex-col">
            <div
                class="scrollbar-hide flex max-h-[500px] w-full flex-col space-y-4 overflow-y-scroll"
            >
                <TextInput
                    v-model="searchKey"
                    type="text"
                    placeholder="Search for a grade"
                />
                <div
                    v-for="(batch, index) in batchOptions"
                    v-show="searchKey.length"
                    :key="batch"
                    class="group cursor-pointer hover:bg-brand-300 hover:text-white"
                    :class="
                        batch.value === selectedBatch?.value
                            ? 'bg-brand-300 text-white hover:bg-brand-300'
                            : index % 2 === 0
                            ? 'bg-brand-50/50 px-10'
                            : 'bg-white px-10'
                    "
                    @click="selectBatch(batch)"
                >
                    <p class="px-3 py-2 text-sm">
                        Current Homeroom for {{ batch.batch }} is
                        <span
                            class="-skew-x-3 bg-brand-100 px-2 font-bold italic group-hover:bg-brand-300"
                            :class="
                                selectedBatch?.value === batch.value
                                    ? 'bg-brand-300 '
                                    : ''
                            "
                        >
                            {{ batch.homeroom }}
                        </span>
                    </p>
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
import { ExclamationTriangleIcon } from "@heroicons/vue/20/solid";

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
            batch: batch.level.name + batch.section,
            value: batch.id,
            homeroom: batch?.homeroom_teacher?.teacher
                ? batch?.homeroom_teacher?.teacher?.user.name
                : "-",
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
