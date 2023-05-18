<template>

    <div
        class="container mx-auto flex h-full max-h-full max-w-7xl flex-col gap-4 px-2 pt-6 md:px-6 md:pt-6"
    >
        <div class="grid h-full grid-cols-12 grid-rows-[auto_1fr] overflow-auto">

            <div class="col-span-12 mb-4 flex h-fit flex-col">
                <Heading>Assign Subjects</Heading>
                <h3 class="text-sm text-gray-500">
                    Now that you have registered your classes and subjects, it's time to
                    assign each class to the subject they will be taking.
                </h3>
            </div>

            <div
                class="relative col-span-12 flex h-full max-h-full flex-col gap-3 overflow-auto px-0.5 lg:col-span-3"
            >
                <Heading size="sm">Subjects</Heading>

                <TextInput
                    v-model="query"
                    placeholder="Search for a subject, tag or category"
                    class="!w-full"
                />

                <div
                    class="relative grid h-full max-h-full grow gap-3 overflow-auto pr-2 pb-3 sm:grid-cols-2 md:grid-cols-3 lg:flex lg:w-full lg:flex-col"
                >
                    <label
                        v-for="(subject, s) in searchedSubjects"
                        :key="s"
                        :class="{ 'sticky inset-y-0 z-10' : selectedSubject?.id === subject.id }"
                        @click="selectSubject(subject)"
                    >
                        <Card
                            :class="{
                                '!bg-black !text-white':
                                    selectedSubject?.id === subject.id,
                            }"
                            class="group !min-w-full cursor-pointer transition duration-150 hover:border-gray-500 hover:shadow-md"
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
                                            class="cursor-pointer whitespace-nowrap rounded-xl py-0.5 pr-1.5 text-xs font-semibold leading-none"
                                        >
                                            {{ toHashTag(label) }}
                                        </span>
                                    </span>
                                </div>

                                <div class="flex items-center gap-1">
                                    <span class="text-sm font-semibold">{{ selectedCount(subject).levels }}</span>
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

                                    <span class="text-sm font-semibold">{{ selectedCount(subject).batches }}</span>
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

            </div>

            <div
                class="col-span-12 flex h-full max-h-full flex-col gap-3 overflow-auto lg:col-span-9 lg:ml-12"
            >
                <div v-if="!!selectedSubject" class="flex flex-col">
                    <Heading size="sm">{{ selectedSubject.full_name }} Classes</Heading>
                    <h3 class="text-sm text-gray-500">
                        Select the classes that will be taking<span
                        class="font-semibold">&nbsp;{{ selectedSubject.full_name }}</span>
                    </h3>
                </div>

                <div class="relative flex flex-col gap-4">
                    <div
v-if="!!!selectedSubject"
                         class="absolute top-0 left-0 flex h-full w-full flex-col items-center justify-center gap-8 bg-white/100">

                        <div class="flex flex-col items-center justify-center">
                            <h3 class="font-semibold">First, Select a Subject</h3>
                            <h3 class="text-center text-sm text-gray-500">Choose a subject from the list <span>on the left</span>
                                <span class="block">to start assigning classes</span></h3>
                        </div>

                        <div class="flex flex-col items-center gap-2">
                            <h3 class="text-sm text-gray-500">...or pick one of these subjects to start</h3>
                            <div class="flex gap-2">
                                <button
                                    class="rounded-md border border-gray-500 p-2 text-sm font-semibold text-gray-500"
                                    @click="selectSubjectByName('English')">English
                                </button>
                                <button
                                    class="rounded-md border border-gray-500 p-2 text-sm font-semibold text-gray-500"
                                    @click="selectSubjectByName('Mathematics')">Mathematics
                                </button>
                                <button
                                    class="rounded-md border border-gray-500 p-2 text-sm font-semibold text-gray-500"
                                    @click="selectSubjectByName('አማርኛ')">አማርኛ
                                </button>
                            </div>
                        </div>

                    </div>

                    <div v-if="!!selectedSubject" class="flex items-center gap-5">
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold">{{ selectedCount(selectedSubject).levels }}</span>
                            <span
                                class="text-sm text-gray-500"
                            >
                                        / 15 Levels Selected
                                    </span>
                        </div>

                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold">{{ selectedCount(selectedSubject).batches }}</span>
                            <span
                                class="text-sm text-gray-500"
                            >
                                        / {{ batchToSelectedSubject.length }} Classes Selected
                                    </span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-8">

                        <div class="flex flex-col gap-3">
                            <h3 class="text-sm font-semibold">Select Classes in Levels</h3>
                            <div
                                class="grid-cols-3 gap-3 transition duration-300 lg:grid"
                            >
                                <Card
                                    v-for="(levelCategory, LC) in levelCategories"
                                    :key="LC"
                                    :class="isGroupSelected(levelCategory.id) ? 'border-black' : ''"
                                    class="!w-full cursor-pointer hover:border-gray-500 hover:shadow-lg"
                                    :title="levelCategory.name"
                                    :subtitle="levelCategoryDescriptions[levelCategory.id]"
                                    @click="isGroupSelected(levelCategory.id) ?
                                        deselectLevelGroups(levelCategory.id) :
                                        selectLevelGroups(levelCategory.id, true, false)
                                    "
                                />
                            </div>
                        </div>

                        <div class="flex flex-col gap-3">
                            <div class="flex w-full items-center justify-between">
                                <h3 class="text-sm font-semibold">Select Individual Classes</h3>
                                <button
class="flex items-center gap-1"
                                        @click="isGroupSelected('All') ? deselectLevelGroups('All') : selectLevelGroups('All')">
                                    <CheckCircleIcon
v-if="!isGroupSelected('All')"
                                                     class="h-4 w-4 stroke-gray-500 stroke-2"/>
                                    <MinusCircleIcon v-else class="h-4 w-4 stroke-negative-50 stroke-2"/>
                                    <span
:class="isGroupSelected('All') ? 'text-negative-50' : 'text-gray-500'"
                                          class="text-sm font-semibold">
                                        {{ isGroupSelected('All') ? 'Deselect All' : 'Select All' }}
                                    </span>
                                </button>
                            </div>

                            <div
                                class="auto-rows-[102px] grid-cols-3 gap-3 transition duration-300 lg:grid"
                            >
                                <div
                                    v-for="(level, l) in levels.slice(0, levels.length)"
                                    :key="l"
                                    :class="showSectionsFor === level.id ? 'z-10' : ''"
                                >
                                    <Card
                                        class="group !w-full overflow-hidden transition-all duration-150 hover:border-black focus:border-black focus:shadow-lg"
                                        :class="[
                                    showSectionsFor === level.id
                                        ? 'max-h-[100rem] !drop-shadow-lg'
                                        : 'max-h-fit',
                                    selectedSectionsPercentage(selectedSubject, level) > 0 ? 'border-black' : showSectionsFor === level.id ? 'border-gray-300' : '']
                                    "
                                    >
                                        <div class="flex flex-col gap-6">
                                            <div class="flex items-center justify-between">
                                                <h3 class="font-semibold">
                                                    {{ parseLevel(level.name) }}
                                                </h3>

                                                <CircularProgress
                                                    v-if="!!selectedSubject"
                                                    :diameter="25"
                                                    :dasharray="25 * 2.5"
                                                    :color="selectedSectionsPercentage(selectedSubject, level) === 100 ? 'positive' : 'default'"
                                                    :percentage="selectedSectionsPercentage(selectedSubject, level)"
                                                    background="stroke-gray-200"
                                                />

                                            </div>

                                            <div
                                                class="flex w-full flex-col items-start"
                                            >
                                                <div
                                                    :class="showSectionsFor === level.id ? 'hidden' : 'group-hover:flex group-focus:flex'"
                                                    class="-ml-4 -mb-4 hidden h-[36px] w-[calc(100%+32px)] rounded-b-md py-2 transition-all duration-150">
                                                    <button
                                                        class="w-1/2 text-xs transition-transform duration-150 hover:scale-105 hover:font-semibold"
                                                        @click="toggleAndSelectAll(level.id, selectedSubject?.id)">
                                                        Select All Sections
                                                    </button>
                                                    <div class="h-full w-1 border-r border-black"></div>
                                                    <button
                                                        class="w-1/2 rounded-sm text-xs transition-transform duration-150 hover:scale-105 hover:font-semibold"
                                                        @click="toggleShowSection(level.id)">List All Sections
                                                    </button>
                                                </div>
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
                                                    class="-ml-4 -mb-4 mt-2 h-10 w-[calc(100%+32px)] items-center justify-end"
                                                    :class="showSectionsFor === level.id ? 'flex' : 'hidden'"
                                                >
                                                    <button
                                                        class="flex h-full w-full items-center justify-center gap-1 border-t bg-neutral-100 px-2 text-right text-sm ring-gray-500 hover:shadow-md hover:ring-1"
                                                        @click="toggleShowSection(level)"
                                                    >
                                                        <ChevronDoubleUpIcon class="h-3 w-3 stroke-2"/>
                                                        <span
                                                            class="text-xs font-semibold text-gray-500">Collapse</span>
                                                    </button>
                                                </div>
                                                <div
                                                    :class="
                                            showSectionsFor === level.id
                                                ? 'max-h-0'
                                                : 'max-h-[100rem]'
                                            "
                                                    class="flex w-full items-center justify-between overflow-hidden rounded-md group-hover:hidden group-focus:hidden"
                                                >
                                                    <div class="flex items-center gap-2">
                                                        <span
                                                            :class="selectedSectionsPercentage(selectedSubject, level) === 100 ? 'font-semibold' : ''"
                                                            class="text-sm">
                                                        {{
                                                                selectedSectionsPercentage(selectedSubject, level) > 0 ?
                                                                    selectedSectionsPercentage(selectedSubject, level) === 100 ? 'All Sections Selected' : 'Some Sections Selected' : 'None Selected'
                                                            }}
                                                    </span>
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

                            <div v-if="!!selectedSubject" class="flex items-center justify-between gap-3">
                                <TertiaryButton @click="saveBatches">Save</TertiaryButton>
                                <PrimaryButton @click="submitForm">Finish</PrimaryButton>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>


import {router} from "@inertiajs/vue3";

import {parseLevel, toHashTag} from "@/utils";
import {computed, onMounted, ref, watch} from "vue";
import {allLevels, sectionsOfLevel} from "@/fake";
import {CheckCircleIcon, ChevronDoubleUpIcon, ChevronDownIcon, MinusCircleIcon,} from "@heroicons/vue/24/outline";
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
const levelCategories = ref(null);
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

function selectSubjectByName(name) {
    selectedSubject.value = subjects.value.find(subject => subject.full_name === name)
}

const batchToSelectedSubject = computed(() => batchToSubjects.value.filter(batchToSubject => {
    return batchToSubject?.subject_id === selectedSubject.value?.id
}))

function selectLevelGroups(group, toggle = false, reset = true) {

    showSectionsFor.value = null
    if (reset) {
        batchToSelectedSubject.value.forEach(batchToSubject => batchToSubject.selected = false)
    }

    if (group === 'All') {

        batchToSelectedSubject.value.forEach(batchToSubject => batchToSubject.selected = true)

    } else {

        batchToSelectedSubject.value.filter(batchToSubject => {
            return batchToSubject?.level_category_id === group
        }).forEach(batchToSubject => batchToSubject.selected = true)

    }

}

function deselectLevelGroups(group) {

    showSectionsFor.value = null

    if (group === 'All') {

        batchToSelectedSubject.value.forEach(batchToSubject => batchToSubject.selected = false)

    } else {

        batchToSelectedSubject.value.filter(batchToSubject => {
            return batchToSubject?.level_category_id === group
        }).forEach(batchToSubject => batchToSubject.selected = false)

    }

}

function isGroupSelected(group) {

    if (group === 'All') {

        return batchToSelectedSubject.value.every(batchToSubject => batchToSubject?.selected)

    } else {

        return batchToSelectedSubject.value.filter(batchToSubject => {
            return batchToSubject?.level_category_id === group
        }).every(batchToSubject => batchToSubject?.selected)

    }

}

function selectedCount(subject) {
    let batches = batchToSubjects.value.filter((batchToSubject) => {
        return (
            batchToSubject['subject_id'] === subject.id &&
            batchToSubject['selected']
        );
    });

    let levels = batches.reduce((acc, batchToSubject) => {
        if (!acc.includes(batchToSubject['level_id'])) {
            acc.push(batchToSubject['level_id']);
        }
        return acc;
    }, [])

    batches = batches.length
    levels = levels.length

    return {
        batches,
        levels
    }
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

function selectedSectionsPercentage(subject, level) {

    if (subject === null) return null

    const allBatches = batchToSubjects.value.filter(batchToSubject => {
        return batchToSubject?.level_id === level.id && batchToSubject?.subject_id === subject.id
    })

    const selectedBatches = allBatches.filter((batchToSubject) => batchToSubject?.selected)

    return selectedBatches.length / allBatches.length * 100
}

function toggleAndSelectAll(level_id, subject_id) {
    toggleShowSection(level_id)

    if (!!selectedSubject.value) {

        batchToSubjects.value
            .filter(
                (batchToSubject) =>
                    batchToSubject?.subject_id === subject_id &&
                    batchToSubject?.level_id === level_id
            )
            .forEach((batchToSubject, batch) => {
                setTimeout(() => {
                    batchToSubject.selected = true;
                }, 100 * (batch + 1))
            });

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

function toggleSelectAllSectionsOfLevelToSubject(level_id, subject_id, toggle = true) {
    if (!!!selectedSubject.value) {
        return;
    }

    if (isAllLevelSectionsSelectedForSubject(level_id, subject_id) && toggle) {
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

const levelCategoryDescriptions = {
    1: "Select only Kindergarten level grades (Pre-KG to KG-2)",
    2: "Select only Elementary level grades (Grades 1 - 8)",
    3: "Select only High School level grades (Grades 9 - 12)"
}

const batchToSubjects = ref([]);
onMounted(() => {
    router.reload({
        only: ["batches", "subjects", "level_categories"],
        preserveState: true,
        onSuccess(data) {
            batches.value = data.props.batches;
            levelCategories.value = data.props.level_categories;
            subjects.value = data.props.subjects;

            batches.value.forEach((batch) => {
                subjects.value.forEach((subject) => {
                    batchToSubjects.value.push({
                        batch_id: batch.id,
                        level_id: batch.level_id,
                        level_category_id: batch.level.level_category_id,
                        subject_id: subject.id,
                        selected: false,
                    });
                });
            });

            console.log(batchToSubjects.value[0])
        },
    });
});
</script>

<style scoped></style>
