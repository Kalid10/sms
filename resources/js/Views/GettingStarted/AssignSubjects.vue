<template>
    <div class="flex flex-col">
        <DelegateLink></DelegateLink>
        <Heading>Assign Subjects</Heading>
        <h3 class="text-sm text-gray-500">
            Now that you have registered your classes and subjects, you can now
            assign each class to the subject they will be taking.
        </h3>
    </div>

    <div class="grid w-full grid-cols-12">
        <div
            class="relative col-span-12 flex h-full flex-col gap-3 lg:col-span-3"
        >
            <Heading size="sm">Subjects</Heading>

            <TextInput
                v-model="query"
                placeholder="Search for a subject, tag or category"
                class="!w-full"
            />

            <div
                class="grid max-h-[60rem] gap-3 overflow-hidden sm:grid-cols-2 md:grid-cols-3 lg:flex lg:w-full lg:flex-col"
            >
                <label
                    v-for="(subject, s) in searchedSubjects"
                    :key="s"
                    @click="selectSubject(subject)"
                >
                    <Card
                        :class="{
                            '!bg-black !text-white':
                                selectedSubject?.id === subject.id,
                        }"
                        class="group !min-w-full transition duration-150"
                    >
                        <div class="flex flex-col gap-6">
                            <div class="flex flex-col">
                                <div class="flex items-start justify-between">
                                    <h3 class="flex flex-wrap items-baseline">
                                        <span
                                            class="mr-2 whitespace-nowrap font-semibold"
                                        >
                                            {{ subject.full_name }}
                                        </span>
                                        <span
                                            :class="[
                                                selectedSubject?.id ===
                                                subject.id
                                                    ? 'text-white'
                                                    : 'text-gray-500',
                                            ]"
                                            class="whitespace-nowrap text-sm uppercase"
                                        >
                                            {{ subject.short_name }}
                                        </span>
                                    </h3>

                                    <CheckCircleIcon
                                        v-if="
                                            selectedSubject?.id === subject.id
                                        "
                                        class="mt-1 h-6 w-6 stroke-white stroke-2"
                                    />
                                </div>

                                <span
                                    class="flex w-fit origin-left scale-[.85] flex-wrap gap-1"
                                >
                                    <span
                                        v-for="(label, l) in subject.tags"
                                        :key="l"
                                        :class="[
                                            selectedSubject?.id === subject.id
                                                ? 'text-white'
                                                : 'text-gray-500',
                                        ]"
                                        class="cursor-pointer whitespace-nowrap rounded-xl py-0.5 pr-1.5 text-xs font-semibold leading-none transition duration-300 hover:scale-110 hover:bg-gray-300"
                                    >
                                        {{ toHashTag(label) }}
                                    </span>
                                </span>
                            </div>

                            <div class="flex items-center gap-1">
                                <span class="text-sm font-semibold">3</span>
                                <span
                                    :class="[
                                        selectedSubject?.id === subject.id
                                            ? 'text-white'
                                            : 'text-gray-500',
                                    ]"
                                    class="text-sm text-gray-500"
                                >
                                    Levels
                                </span>

                                <span class="text-sm font-semibold">9</span>
                                <span
                                    :class="[
                                        selectedSubject?.id === subject.id
                                            ? 'text-white'
                                            : 'text-gray-500',
                                    ]"
                                    class="text-sm text-gray-500"
                                >
                                    Classes
                                </span>
                            </div>
                        </div>
                    </Card>
                </label>
            </div>

            <button
                class="mx-auto flex w-fit items-center justify-center gap-1 rounded-2xl bg-gray-200 px-3 py-2 text-xs"
            >
                <ChevronDoubleDownIcon class="h-4 w-4"/>
                <span>Show All</span>
            </button>
        </div>

        <div
            class="col-span-12 flex h-full flex-col gap-3 lg:col-span-9 lg:ml-12"
        >
            <div class="flex flex-col">
                <Heading size="sm">Classes</Heading>
                <h3 class="text-sm text-gray-500">
                    Select the classes that will be taking the subjects you have
                    selected.
                </h3>
            </div>

            <span
                v-if="!!selectedSubject"
                class="inline-flex select-none flex-wrap items-center gap-2 text-sm"
            >
                <span
                    class="flex items-center gap-1 whitespace-nowrap rounded-xl border border-black px-2 font-semibold"
                >
                    <XCircleIcon
                        class="h-4 w-4 cursor-pointer fill-black"
                        @click="unselectSubject"
                    />
                    <span>{{ selectedSubject.full_name }}</span>
                </span>
            </span>

            <div
                :class="{ 'opacity-25': !!!selectedSubject }"
                class="grid-cols-3 gap-3 transition duration-300 lg:grid"
            >
                <div
                    v-for="(level, l) in levels.slice(0, levels.length)"
                    :key="l"
                >
                    <Card
                        class="group !w-full transition-[max-height] duration-300"
                        :class="
                            showSectionsFor === level.id
                                ? 'border-black max-h-[100rem]'
                                : 'max-h-fit'
                        "
                        @click="toggleShowSection(level.id)"
                    >
                        <div class="flex flex-col gap-6">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold">
                                    <span v-if="level.name.length < 3"
                                    >Grade</span
                                    >
                                    {{ level.name }}
                                </h3>
                            </div>

                            <div
                                class="flex w-full flex-col items-start"
                                @click="
                                    () => {
                                        showSectionsFor === level.id &&
                                            toggleShowSection(level.id);
                                    }
                                "
                            >
                                <ul
                                    :class="
                                        showSectionsFor === level.id
                                            ? 'max-h-[100rem]'
                                            : 'max-h-0'
                                    "
                                    class="flex w-full flex-col overflow-hidden text-sm"
                                >
                                    <li
                                        class="flex w-full items-center gap-2 rounded-md"
                                        @click="
                                            toggleSelectAllSectionsOfLevelToSubject(
                                                level.id,
                                                selectedSubject?.id
                                            )
                                        "
                                    >
                                        <CheckCircleIcon
                                            v-if="
                                                isAllLevelSectionsSelectedForSubject(
                                                    level.id,
                                                    selectedSubject?.id
                                                )
                                            "
                                            class="h-[14px] w-[14px] scale-[1.15] stroke-2"
                                        />
                                        <MinusCircleIcon
                                            v-else-if="
                                                isSomeLevelSectionsSelectedForSubject(
                                                    level.id,
                                                    selectedSubject?.id
                                                )
                                            "
                                            class="h-[14px] w-[14px] scale-[1.15] stroke-gray-500 stroke-2 opacity-70"
                                        />
                                        <div
                                            v-else
                                            class="h-[14px] w-[14px] rounded-full border border-[#D4D4D4] bg-[#F3F4F6]"
                                        />
                                        <label
                                            :for="`section${l}-all`"
                                            class="grow cursor-pointer select-none border-b py-1 font-semibold"
                                        >
                                            All Sections
                                        </label>
                                    </li>
                                    <li
                                        v-for="(
                                            section, sec
                                        ) in sectionsOfLevel(batches, level.id)"
                                        :key="sec"
                                        class="sections-list flex w-full items-center gap-2 rounded-md"
                                    >
                                        <Checkbox
                                            v-if="!!selectedSubject"
                                            :id="`section${l}-${sec}`"
                                            v-model="
                                                getBatchToSubject(
                                                    section.id,
                                                    selectedSubject?.id
                                                ).selected
                                            "
                                            class="!rounded-full ring-0 focus:ring-0"
                                        />
                                        <label
                                            :for="`section${l}-${sec}`"
                                            :class="{
                                                'border-b':
                                                    sec !==
                                                    sectionsOfLevel(
                                                        batches,
                                                        level.id
                                                    ).length -
                                                        1,
                                            }"
                                            class="grow cursor-pointer select-none py-1"
                                        >
                                            Section {{ section.section }}
                                        </label>
                                    </li>
                                </ul>
                                <div
                                    :class="
                                        showSectionsFor === level.id
                                            ? 'max-h-0'
                                            : 'max-h-[100rem]'
                                    "
                                    class="flex w-full items-center justify-between overflow-hidden rounded-md"
                                >
                                    <div class="flex items-center gap-2">
                                        <CircularProgress
                                            :diameter="17"
                                            :dasharray="17 * 2.5"
                                            :percentage="100"
                                        />
                                        <span class="text-sm"
                                        >All Sections</span
                                        >
                                    </div>

                                    <ChevronDownIcon
                                        class="h-4 w-4 -translate-y-1.5 stroke-black stroke-2 opacity-0 transition duration-300 group-hover:translate-y-0 group-hover:opacity-100"
                                    />
                                </div>
                            </div>
                        </div>
                    </Card>
                </div>
            </div>

            <div class="flex items-center justify-between gap-3">
                <TertiaryButton @click="saveBatches">Save</TertiaryButton>
                <PrimaryButton @click="submitForm">Finish</PrimaryButton>
            </div>
        </div>
    </div>
</template>

<script setup>
import DelegateLink from "@/Views/DelegateLink.vue";
import {computed, onMounted, ref, watch} from "vue";
import {router} from "@inertiajs/vue3";
import {toHashTag} from "@/utils";
import {allLevels, sectionsOfLevel} from "@/fake";
import {XCircleIcon} from "@heroicons/vue/24/solid";
import {CheckCircleIcon, ChevronDoubleDownIcon, ChevronDownIcon, MinusCircleIcon,} from "@heroicons/vue/24/outline";
import CircularProgress from "@/Components/CircularProgress.vue";
import Card from "@/Components/Card.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Heading from "@/Components/Heading.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";

const emits = defineEmits(["success"]);

const subjects = ref(null);
const batches = ref(null);
const levels = computed(() =>
    allLevels(batches.value).map((level) => ({
        ...level.level,
        batch_id: level.id,
        selected: true,
    }))
);
const selectedSubject = ref(null);

function selectSubject(subject) {
    selectedSubject.value = subject;
}

const query = ref("");
const searchedSubjects = computed(() => {
    if (query.value === "") {
        return subjects.value;
    }
    return subjects.value.filter((subject) =>
        subject.full_name.toLowerCase().includes(query.value.toLowerCase())
    );
});

const showSectionsFor = ref(null);

function toggleShowSection(level) {
    if (!!!selectedSubject.value) {
        return;
    }

    if (showSectionsFor.value === level) {
        showSectionsFor.value = null;
    } else {
        showSectionsFor.value = level;
    }
}

function unselectSubject() {
    selectedSubject.value = null;
}

watch(selectedSubject, () => {
    if (!!!selectedSubject.value) {
        showSectionsFor.value = null;
    }
    showSectionsFor.value = null;
});

function getBatchToSubject(batch_id, subject_id) {
    return batchToSubjects.value.find(
        (batchToSubject) =>
            batchToSubject?.batch_id === batch_id &&
            batchToSubject?.subject_id === subject_id
    );
}

function isSomeLevelSectionsSelectedForSubject(level_id, subject_id) {
    if (!!!selectedSubject.value) {
        return false;
    }
    return batchToSubjects.value
        .filter(
            (batchToSubject) =>
                batchToSubject?.subject_id === subject_id &&
                batchToSubject?.level_id === level_id
        )
        .some((batchToSubject) => batchToSubject?.selected);
}

function isAllLevelSectionsSelectedForSubject(level_id, subject_id) {
    if (!!!selectedSubject.value) {
        return false;
    }
    return batchToSubjects.value
        .filter(
            (batchToSubject) =>
                batchToSubject?.subject_id === subject_id &&
                batchToSubject?.level_id === level_id
        )
        .every((batchToSubject) => batchToSubject?.selected);
}

function toggleSelectAllSectionsOfLevelToSubject(level_id, subject_id) {
    if (!!!selectedSubject.value) {
        return;
    }

    if (isAllLevelSectionsSelectedForSubject(level_id, subject_id)) {
        batchToSubjects.value
            .filter(
                (batchToSubject) =>
                    batchToSubject?.subject_id === subject_id &&
                    batchToSubject?.level_id === level_id
            )
            .forEach((batchToSubject) => {
                batchToSubject.selected = false;
            });
        return;
    }

    batchToSubjects.value
        .filter(
            (batchToSubject) =>
                batchToSubject?.subject_id === subject_id &&
                batchToSubject?.level_id === level_id
        )
        .forEach((batchToSubject) => {
            batchToSubject.selected = true;
        });
}

// remap batchToSubjects list of objects to a list of objects with structure { batch_id: int, subject_ids: array }
const formData = computed(() => {
    const grouped = batchToSubjects.value.reduce((acc, item) => {
        if (item?.selected) {
            if (!acc[item.batch_id]) {
                acc[item.batch_id] = {
                    batch_id: item.batch_id,
                    subject_ids: [],
                };
            }
            acc[item.batch_id].subject_ids.push(item.subject_id);
        }
        return acc;
    }, {});

    return Object.values(grouped);
});

function saveBatches() {
    router.post(
        "/batches/subjects/assign",
        {
            batches_subjects: formData.value,
        },
        {
            onSuccess() {
                console.log("Success");
                showSectionsFor.value = null;
            },
            onError() {
                console.log("Error");
                showSectionsFor.value = null;
            },
        }
    );
}

function submitForm() {
    router.get("/getting-started/school-period");
}

const batchToSubjects = ref([]);
onMounted(() => {
    router.reload({
        only: ["batches", "subjects"],
        preserveState: true,
        onSuccess(data) {
            batches.value = data.props.batches;
            subjects.value = data.props.subjects;

            batches.value.forEach((batch) => {
                subjects.value.forEach((subject) => {
                    batchToSubjects.value.push({
                        batch_id: batch.id,
                        level_id: batch.level_id,
                        subject_id: subject.id,
                        selected: false,
                    });
                });
            });
        },
    });
});
</script>

<style scoped></style>
