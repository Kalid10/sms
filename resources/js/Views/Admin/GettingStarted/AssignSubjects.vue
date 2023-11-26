<template>
    <div
        class="container mx-auto flex h-full max-h-full flex-col gap-4 px-2 pt-6 md:px-6 md:pt-6"
        :class="widthClass"
    >
        <div
            class="grid h-full grid-cols-12 grid-rows-[auto_1fr] overflow-auto"
        >
            <div class="col-span-12 flex w-full items-center justify-between">
                <div class="mb-4 flex h-fit flex-col">
                    <Heading>
                        {{ $t("assignSubjects.assignSubjects") }}
                    </Heading>
                    <h3 class="text-brand-text-600 text-sm">
                        {{ $t("assignSubjects.assignClassHint") }}
                    </h3>
                </div>

                <PrimaryButton
                    title="Skip"
                    class="h-fit !w-fit !bg-brand-100 !text-black hover:!bg-brand-300 hover:!text-white"
                    @click="$emit('success')"
                />
            </div>
            <div
                class="relative col-span-12 flex h-full max-h-full flex-col gap-3 overflow-auto px-0.5 lg:col-span-3"
            >
                <Heading size="sm">
                    {{ $t("assignSubjects.subjects") }}
                </Heading>

                <TextInput
                    v-model="query"
                    :placeholder="$t('assignSubjects.queryPlaceholder')"
                    class="!w-full"
                />

                <div
                    class="relative grid h-full max-h-full grow gap-3 overflow-auto pb-3 pr-2 sm:grid-cols-2 md:grid-cols-3 lg:flex lg:w-full lg:flex-col"
                >
                    <label
                        v-for="(subject, s) in searchedSubjects"
                        :key="s"
                        :class="{
                            'sticky inset-y-0 z-10':
                                selectedSubject?.id === subject?.id,
                        }"
                        @click="selectSubject(subject)"
                    >
                        <Card
                            :class="{
                                '!bg-brand-400 !text-white':
                                    selectedSubject?.id === subject?.id,
                            }"
                            class="group !min-w-full cursor-pointer transition duration-150 hover:border-gray-500 hover:shadow-md"
                        >
                            <div class="flex flex-col gap-6">
                                <div class="flex flex-col">
                                    <div
                                        class="flex items-start justify-between"
                                    >
                                        <h3
                                            class="flex flex-wrap items-baseline"
                                        >
                                            <span
                                                class="mr-2 whitespace-nowrap font-semibold"
                                            >
                                                {{ subject?.full_name }}
                                            </span>
                                            <span
                                                :class="[
                                                    selectedSubject?.id ===
                                                    subject?.id
                                                        ? 'text-white'
                                                        : 'text-brand-text-600',
                                                ]"
                                                class="whitespace-nowrap text-sm uppercase"
                                            >
                                                {{ subject?.short_name }}
                                            </span>
                                        </h3>

                                        <CheckCircleIcon
                                            v-if="
                                                selectedSubject?.id ===
                                                subject?.id
                                            "
                                            class="mt-1 h-6 w-6 stroke-white stroke-2"
                                        />
                                    </div>

                                    <span
                                        class="flex w-fit origin-left scale-[.85] flex-wrap gap-1"
                                    >
                                        <span
                                            v-for="(label, l) in subject?.tags"
                                            :key="l"
                                            :class="[
                                                selectedSubject?.id ===
                                                subject?.id
                                                    ? 'text-white'
                                                    : 'text-brand-text-600',
                                            ]"
                                            class="cursor-pointer whitespace-nowrap rounded-xl py-0.5 pr-1.5 text-xs font-semibold leading-none"
                                        >
                                            {{ toHashTag(label) }}
                                        </span>
                                    </span>
                                </div>

                                <div class="flex items-center gap-1">
                                    <span class="text-sm font-semibold">{{
                                        selectedCount(subject).levels
                                    }}</span>
                                    <span
                                        :class="[
                                            selectedSubject?.id === subject?.id
                                                ? 'text-white'
                                                : 'text-brand-text-600',
                                        ]"
                                        class="text-brand-text-600 text-sm"
                                    >
                                        {{ $t("assignSubjects.levels") }}
                                    </span>

                                    <span class="text-sm font-semibold">{{
                                        selectedCount(subject).batches
                                    }}</span>
                                    <span
                                        :class="[
                                            selectedSubject?.id === subject?.id
                                                ? 'text-white'
                                                : 'text-brand-text-600',
                                        ]"
                                        class="text-brand-text-600 text-sm"
                                    >
                                        {{ $t("assignSubjects.classes") }}
                                    </span>
                                </div>
                            </div>
                        </Card>
                    </label>
                </div>
            </div>

            <div
                class="scrollbar-hide col-span-12 flex h-full max-h-full flex-col gap-3 overflow-auto px-5 pb-4 lg:col-span-9 lg:ml-12"
            >
                <div v-if="!!selectedSubject" class="flex flex-col">
                    <Heading size="sm"
                        >{{ selectedSubject.full_name }}
                        {{ $t("assignSubjects.classes") }}
                    </Heading>
                    <h3 class="text-brand-text-600 text-sm">
                        {{ $t("assignSubjects.selectClasses") }}
                        <span class="font-semibold"
                            >&nbsp;{{ selectedSubject.full_name }}</span
                        >
                    </h3>
                </div>

                <div class="relative flex flex-col gap-4">
                    <div
                        v-if="!!!selectedSubject"
                        class="absolute left-0 top-0 flex h-full w-full flex-col gap-8 bg-white/100"
                    >
                        <div
                            class="flex h-full w-full flex-col items-center gap-5 pt-60"
                        >
                            <Heading class="pb-3 !text-3xl">
                                {{ $t("assignSubjects.selectSubject") }}
                            </Heading>
                            <Heading
                                class="flex w-full flex-col text-center text-gray-500"
                            >
                                {{ $t("assignSubjects.chooseSubject") }}
                                <span class="text-black">
                                    {{ $t("assignSubjects.onTheLeft") }}
                                </span>
                                <span class="block">
                                    {{
                                        $t("assignSubjects.startAssigningClass")
                                    }}
                                </span>
                            </Heading>
                        </div>
                    </div>

                    <div
                        v-if="!!selectedSubject"
                        class="flex items-center gap-5"
                    >
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold">{{
                                selectedCount(selectedSubject).levels
                            }}</span>
                            <span class="text-brand-text-600 text-sm">
                                {{ $t("assignSubjects.selectedLevels") }}
                            </span>
                        </div>

                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold">{{
                                selectedCount(selectedSubject).batches
                            }}</span>
                            <span class="text-brand-text-600 text-sm">
                                / {{ batchToSelectedSubject.length }}
                                {{ $t("assignSubjects.classesSelected") }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-8">
                        <div class="flex flex-col gap-3">
                            <h3 class="text-sm font-semibold">
                                {{ $t("assignSubjects.selectClassesInLevels") }}
                            </h3>
                            <div
                                class="grid-cols-3 gap-3 transition duration-300 lg:grid"
                            >
                                <Card
                                    v-for="(
                                        levelCategory, LC
                                    ) in levelCategories"
                                    :key="LC"
                                    :class="
                                        isGroupSelected(levelCategory.id)
                                            ? 'border-brand-300 !bg-gradient-to-bl from-brand-350 to-brand-400 text-white border-2 '
                                            : ''
                                    "
                                    class="group !w-full cursor-pointer hover:border-gray-500 hover:bg-brand-400 hover:text-white hover:shadow-lg"
                                    :title="levelCategory.name"
                                    :subtitle="
                                        levelCategoryDescriptions[
                                            levelCategory.id
                                        ]
                                    "
                                    :sub-title-style="
                                        isGroupSelected(levelCategory.id)
                                            ? 'text-gray-300'
                                            : 'text-gray-500 group-hover:text-gray-300'
                                    "
                                    @click="
                                        isGroupSelected(levelCategory.id)
                                            ? deselectLevelGroups(
                                                  levelCategory.id
                                              )
                                            : selectLevelGroups(
                                                  levelCategory.id,
                                                  true,
                                                  false
                                              )
                                    "
                                />
                            </div>
                        </div>

                        <div class="flex flex-col gap-3">
                            <div
                                class="flex w-full items-center justify-between"
                            >
                                <h3 class="text-sm font-semibold">
                                    {{
                                        $t(
                                            "assignSubjects.selectIndividualClasses"
                                        )
                                    }}
                                </h3>
                                <button
                                    class="flex items-center gap-1"
                                    @click="
                                        isGroupSelected('All')
                                            ? deselectLevelGroups('All')
                                            : selectLevelGroups('All')
                                    "
                                >
                                    <CheckCircleIcon
                                        v-if="!isGroupSelected('All')"
                                        class="h-4 w-4 stroke-gray-500 stroke-2"
                                    />
                                    <MinusCircleIcon
                                        v-else
                                        class="h-4 w-4 stroke-negative-50 stroke-2"
                                    />
                                    <span
                                        :class="
                                            isGroupSelected('All')
                                                ? 'text-negative-50'
                                                : 'text-brand-text-600'
                                        "
                                        class="text-sm font-semibold"
                                    >
                                        {{
                                            isGroupSelected("All")
                                                ? $t(
                                                      "assignSubjects.deselectAll"
                                                  )
                                                : $t("assignSubjects.selectAll")
                                        }}
                                    </span>
                                </button>
                            </div>

                            <div
                                class="auto-rows-[102px] grid-cols-3 gap-3 transition duration-300 lg:grid"
                            >
                                <div
                                    v-for="(level, l) in levels.slice(
                                        0,
                                        levels.length
                                    )"
                                    :key="l"
                                    :class="
                                        showSectionsFor === level.id
                                            ? 'z-10'
                                            : ''
                                    "
                                >
                                    <Card
                                        v-if="level.batches.length > 0"
                                        class="group !w-full overflow-hidden transition-all duration-150 hover:border-black focus:border-black focus:shadow-lg"
                                        :class="[
                                            showSectionsFor === level.id
                                                ? 'max-h-[100rem] !drop-shadow-lg'
                                                : 'max-h-fit',
                                            selectedSectionsPercentage(
                                                selectedSubject,
                                                level
                                            ) > 0
                                                ? 'border-black'
                                                : showSectionsFor === level.id
                                                ? 'border-gray-300'
                                                : '',
                                        ]"
                                    >
                                        <div class="flex flex-col gap-6">
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <h3 class="font-semibold">
                                                    {{ parseLevel(level.name) }}
                                                </h3>

                                                <CircularProgress
                                                    v-if="
                                                        !!selectedSubject &&
                                                        selectedSectionsPercentage(
                                                            selectedSubject,
                                                            level
                                                        ) > 0
                                                    "
                                                    :diameter="25"
                                                    :dasharray="25 * 2.5"
                                                    :color="
                                                        selectedSectionsPercentage(
                                                            selectedSubject,
                                                            level
                                                        ) === 100
                                                            ? 'positive'
                                                            : 'default'
                                                    "
                                                    :percentage="
                                                        selectedSectionsPercentage(
                                                            selectedSubject,
                                                            level
                                                        )
                                                    "
                                                    background="stroke-gray-200"
                                                />
                                            </div>

                                            <div
                                                class="flex w-full flex-col items-start"
                                            >
                                                <div
                                                    :class="
                                                        showSectionsFor ===
                                                        level.id
                                                            ? 'hidden'
                                                            : 'group-hover:flex group-focus:flex'
                                                    "
                                                    class="-mb-4 -ml-4 hidden h-[36px] w-[calc(100%+32px)] rounded-b-md py-2 transition-all duration-150"
                                                >
                                                    <button
                                                        class="w-1/2 text-xs transition-transform duration-150 hover:scale-105 hover:font-semibold"
                                                        @click="
                                                            toggleAndSelectAll(
                                                                level.id,
                                                                selectedSubject?.id
                                                            )
                                                        "
                                                    >
                                                        {{
                                                            $t(
                                                                "assignSubjects.selectAllSections"
                                                            )
                                                        }}
                                                    </button>
                                                    <div
                                                        class="h-full w-1 border-r border-black"
                                                    ></div>
                                                    <button
                                                        class="w-1/2 rounded-sm text-xs transition-transform duration-150 hover:scale-105 hover:font-semibold"
                                                        @click="
                                                            toggleShowSection(
                                                                level.id
                                                            )
                                                        "
                                                    >
                                                        {{
                                                            $t(
                                                                "assignSubjects.listAllSections"
                                                            )
                                                        }}
                                                    </button>
                                                </div>
                                                <ul
                                                    :class="
                                                        showSectionsFor ===
                                                        level.id
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
                                                            {{
                                                                $t(
                                                                    "assignSubjects.allSections"
                                                                )
                                                            }}
                                                        </label>
                                                    </li>
                                                    <li
                                                        v-for="(
                                                            section, sec
                                                        ) in sectionsOfLevel(
                                                            level.id
                                                        )"
                                                        :key="sec"
                                                        class="sections-list flex w-full items-center gap-2 rounded-md"
                                                    >
                                                        <Checkbox
                                                            v-if="
                                                                !!selectedSubject
                                                            "
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
                                                                        level.id
                                                                    ).length -
                                                                        1,
                                                            }"
                                                            class="grow cursor-pointer select-none py-1"
                                                        >
                                                            {{
                                                                $t(
                                                                    "assignSubjects.section"
                                                                )
                                                            }}

                                                            {{
                                                                section.section
                                                            }}
                                                        </label>
                                                    </li>
                                                </ul>
                                                <div
                                                    class="-mb-4 -ml-4 mt-2 h-10 w-[calc(100%+32px)] items-center justify-end"
                                                    :class="
                                                        showSectionsFor ===
                                                        level.id
                                                            ? 'flex'
                                                            : 'hidden'
                                                    "
                                                >
                                                    <button
                                                        class="flex h-full w-full items-center justify-center gap-1 border-t bg-neutral-100 px-2 text-right text-sm ring-gray-500 hover:shadow-md hover:ring-1"
                                                        @click="
                                                            toggleShowSection(
                                                                level
                                                            )
                                                        "
                                                    >
                                                        <ChevronDoubleUpIcon
                                                            class="h-3 w-3 stroke-2"
                                                        />
                                                        <span
                                                            class="text-brand-text-600 text-xs font-semibold"
                                                        >
                                                            {{
                                                                $t(
                                                                    "assignSubjects.collapse"
                                                                )
                                                            }}
                                                        </span>
                                                    </button>
                                                </div>
                                                <div
                                                    :class="
                                                        showSectionsFor ===
                                                        level.id
                                                            ? 'max-h-0'
                                                            : 'max-h-[100rem]'
                                                    "
                                                    class="flex w-full items-center justify-between overflow-hidden rounded-md group-hover:hidden group-focus:hidden"
                                                >
                                                    <div
                                                        class="flex items-center gap-2"
                                                    >
                                                        <span
                                                            :class="
                                                                selectedSectionsPercentage(
                                                                    selectedSubject,
                                                                    level
                                                                ) === 100
                                                                    ? 'font-semibold'
                                                                    : ''
                                                            "
                                                            class="text-sm"
                                                        >
                                                            {{
                                                                selectedSectionsPercentage(
                                                                    selectedSubject,
                                                                    level
                                                                ) > 0
                                                                    ? selectedSectionsPercentage(
                                                                          selectedSubject,
                                                                          level
                                                                      ) === 100
                                                                        ? $t(
                                                                              "assignSubjects.allSectionSelected"
                                                                          )
                                                                        : $t(
                                                                              "assignSubjects.someSectionsSelected"
                                                                          )
                                                                    : $t(
                                                                          "assignSubjects.noneSelected"
                                                                      )
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

                            <div
                                v-if="!!selectedSubject"
                                class="flex items-center justify-end gap-3"
                            >
                                <PrimaryButton @click="saveBatches">
                                    {{ $t("assignSubjects.finish") }}
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
import { parseLevel, toHashTag } from "@/utils";
import { computed, inject, onMounted, ref, watch } from "vue";
import {
    CheckCircleIcon,
    ChevronDoubleUpIcon,
    ChevronDownIcon,
    MinusCircleIcon,
} from "@heroicons/vue/24/outline";
import CircularProgress from "@/Components/CircularProgress.vue";
import Card from "@/Components/Card.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Heading from "@/Components/Heading.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    widthClass: {
        type: String,
        default: "w-9/12",
    },
    url: {
        type: String,
        default: "/getting-started/school-period",
    },
});

const emits = defineEmits(["success"]);

const subjects = computed(() => {
    if (usePage().props.subjects?.data) {
        return usePage().props.subjects?.data;
    }

    return usePage().props.subjects;
});
const batches = computed(() => usePage().props.batches);
const levelCategories = computed(() => usePage().props.level_categories);

const levels = computed(() => usePage().props.levels);

const selectedSubject = ref(null);

function selectSubject(subject) {
    selectedSubject.value = subject;
}

const sectionsOfLevel = computed(() => {
    return (level_id) => {
        if (!batches.value) {
            return [];
        }

        return batches.value
            .filter((batch) => batch.level_id === level_id)
            .map((batch) => {
                return {
                    id: batch.id,
                    section: batch.section,
                };
            });
    };
});

const batchToSelectedSubject = computed(() =>
    batchToSubjects.value.filter((batchToSubject) => {
        return batchToSubject?.subject_id === selectedSubject.value?.id;
    })
);

function selectLevelGroups(group, toggle = false, reset = true) {
    showSectionsFor.value = null;
    if (reset) {
        batchToSelectedSubject.value.forEach(
            (batchToSubject) => (batchToSubject.selected = false)
        );
    }

    if (group === "All") {
        batchToSelectedSubject.value.forEach(
            (batchToSubject) => (batchToSubject.selected = true)
        );
    } else {
        batchToSelectedSubject.value
            .filter((batchToSubject) => {
                return batchToSubject?.level_category_id === group;
            })
            .forEach((batchToSubject) => (batchToSubject.selected = true));
    }
}

function deselectLevelGroups(group) {
    showSectionsFor.value = null;

    if (group === "All") {
        batchToSelectedSubject.value.forEach(
            (batchToSubject) => (batchToSubject.selected = false)
        );
    } else {
        batchToSelectedSubject.value
            .filter((batchToSubject) => {
                return batchToSubject?.level_category_id === group;
            })
            .forEach((batchToSubject) => (batchToSubject.selected = false));
    }
}

function isGroupSelected(group) {
    if (group === "All") {
        return batchToSelectedSubject.value.every(
            (batchToSubject) => batchToSubject?.selected
        );
    } else {
        return batchToSelectedSubject.value
            .filter((batchToSubject) => {
                return batchToSubject?.level_category_id === group;
            })
            .every((batchToSubject) => batchToSubject?.selected);
    }
}

function selectedCount(subject) {
    let batches = batchToSubjects.value.filter((batchToSubject) => {
        return (
            batchToSubject["subject_id"] === subject.id &&
            batchToSubject["selected"]
        );
    });

    let levels = batches.reduce((acc, batchToSubject) => {
        if (!acc.includes(batchToSubject["level_id"])) {
            acc.push(batchToSubject["level_id"]);
        }
        return acc;
    }, []);

    batches = batches.length;
    levels = levels.length;

    return {
        batches,
        levels,
    };
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
    if (subject === null) return null;

    const allBatches = batchToSubjects.value.filter((batchToSubject) => {
        return (
            batchToSubject?.level_id === level.id &&
            batchToSubject?.subject_id === subject.id
        );
    });

    const selectedBatches = allBatches.filter(
        (batchToSubject) => batchToSubject?.selected
    );

    return (selectedBatches.length / allBatches.length) * 100;
}

function toggleAndSelectAll(level_id, subject_id) {
    toggleShowSection(level_id);

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
                }, 100 * (batch + 1));
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

function toggleSelectAllSectionsOfLevelToSubject(
    level_id,
    subject_id,
    toggle = true
) {
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

const showNotification = inject("showNotification");

function saveBatches() {
    router.post(
        "/batches/subjects/assign",
        {
            batches_subjects: formData.value,
        },
        {
            onSuccess() {
                showSectionsFor.value = null;
                emits("success");
            },
            onError(error) {
                showSectionsFor.value = null;
                if (error.batches_subjects)
                    showNotification({
                        type: "error",
                        message: error.batches_subjects,
                        position: "top-center",
                    });
            },
        }
    );
}

function submitForm() {
    router.get(props.url);
}

const { t } = useI18n();
const levelCategoryDescriptions = {
    1: t("assignSubjects.kindergarten"),
    2: t("assignSubjects.elementary"),
    3: t("assignSubjects.highSchool"),
};

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
                        selected: batch.subjects
                            .map((sub) => sub.subject_id)
                            .includes(subject.id),
                    });
                });
            });
        },
    });
});
</script>

<style scoped></style>
