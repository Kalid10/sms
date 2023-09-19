<template>
    <div
        class="container mx-auto flex h-full max-h-full max-w-7xl flex-col gap-4 space-y-4 px-2 pt-6 md:px-6 md:pt-6"
    >
        <div class="flex flex-col">
            <Heading>{{ $t("registerBatches.registerGrades") }}</Heading>
            <h3 class="text-brand-text-600 text-sm">
                {{ $t("registerBatches.hintOne") }}

                <span class="inline-block">
                    {{ $t("registerBatches.hintTwo") }}
                </span>
            </h3>
        </div>

        <div class="relative flex gap-5">
            <span
                class="col-span1 text-sm sm:col-span-2 md:col-span-3 lg:col-span-3 xl:col-span-4"
            >
                <span class="font-bold">{{ selectedGradeCount }}</span>
                {{ $t("registerBatches.gradesSelected") }}
            </span>

            <span
                class="col-span1 text-brand-text-600 text-sm sm:col-span-2 md:col-span-3 lg:col-span-3 xl:col-span-4"
            >
                <span class="font-bold">{{ batchesCount }}</span>
                {{ $t("registerBatches.sectionsSelected") }}
            </span>

            <!--            <PrimaryButton-->
            <!--                class="absolute right-0"-->
            <!--                :title="$t('registerBatches.buttonTitle')"-->
            <!--                @click="isNewLevelFormOpened = true"-->
            <!--            />-->
        </div>

        <div
            v-for="(levelCategory, lc) in levelCategories"
            :key="lc"
            class="flex flex-col gap-4"
        >
            <div class="flex items-center gap-2">
                <div
                    class="z-10 h-3.5 w-3.5 rounded-full"
                    :class="colors[lc]"
                />
                <Heading size="sm" class="text-brand-text-600 font-normal">
                    {{
                        updatedLevels.filter(
                            (level) => level.level_category_id === levelCategory
                        )[0].level_category.name
                    }}
                </Heading>
            </div>
            <div
                class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4"
            >
                <label
                    v-for="(level, l) in updatedLevels.filter(
                        (level) => level.level_category_id === levelCategory
                    )"
                    :key="l"
                >
                    <Card class="group !min-w-full">
                        <div
                            :class="
                                level.selected || level.isNew
                                    ? 'opacity-100'
                                    : 'opacity-25'
                            "
                            class="flex flex-col gap-6"
                        >
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold">
                                    <span v-if="level.name.length < 3">{{
                                        $t("registerBatches.grade")
                                    }}</span>
                                    {{ level.name }}
                                </h3>

                                <Checkbox
                                    v-if="!!!level.isNew"
                                    v-model="level.selected"
                                    class="h-5 w-5 !rounded-full border-none bg-transparent checked:ring-0 focus:ring-0"
                                    type="checkbox"
                                />
                                <TrashIcon
                                    v-if="level.isNew"
                                    class="h-5 w-5 cursor-pointer stroke-black stroke-2"
                                    @click="removeLevel(level)"
                                ></TrashIcon>
                            </div>

                            <div class="flex items-baseline gap-1">
                                <span>{{
                                    $t("registerBatches.sections")
                                }}</span>
                                <span class="font-semibold">{{
                                    level.no_of_sections
                                }}</span>
                                <button
                                    class="ml-2 grid h-6 w-6 cursor-pointer place-items-center rounded-full bg-neutral-200 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                                    @click="
                                        editSection(
                                            getGlobalIndexForLevel(level)
                                        )
                                    "
                                >
                                    <PencilIcon
                                        class="h-3 w-3 stroke-black stroke-2"
                                    />
                                </button>
                            </div>
                        </div>
                    </Card>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <PrimaryButton @click="submitBatches"
                >{{ $t("registerBatches.finish") }}
            </PrimaryButton>
        </div>
    </div>

    <Modal v-model:view="updateLevelSection">
        <FormElement
            v-model:show-modal="updateLevelSection"
            modal
            :title="$t('registerBatches.updateLevelSectionTitle')"
            :subtitle="
                $t('registerBatches.updateLevelSectionSubtitle', {
                    grade: updatedLevels[levelToUpdateSection].name,
                })
            "
            @submit="updateSection"
        >
            <TextInput
                v-model="updatedLevels[levelToUpdateSection].no_of_sections"
                :placeholder="
                    $t('registerBatches.updateLevelSectionInputPlaceholder')
                "
            />
        </FormElement>
    </Modal>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import Heading from "@/Components/Heading.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Card from "@/Components/Card.vue";
import { PencilIcon, TrashIcon } from "@heroicons/vue/24/outline";
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useGettingStartedStore } from "@/Store/getting-started";

const emits = defineEmits(["success"]);

const gettingStartedStore = useGettingStartedStore();

const level_categories = computed(() => usePage().props.level_categories);

const levelCategories = computed(() =>
    updatedLevels.value.reduce((acc, level) => {
        acc.add(level.level_category_id);

        return acc;
    }, new Set())
);

const levels = computed(() => gettingStartedStore.levels);

const updatedLevels = ref(
    gettingStartedStore.levels.map((level) => {
        return { ...level, selected: true, no_of_sections: 3 };
    })
);

const selectedLevels = computed(() =>
    updatedLevels.value.filter((level) => level.selected)
);

const formData = computed(() => {
    return selectedLevels.value.map((level) => {
        return {
            level_id: level.isNew ? { name: level.name } : level.id,
            no_of_sections: level.no_of_sections,
        };
    });
});

const batchesCount = computed(() => {
    return updatedLevels.value.reduce((acc, level) => {
        if (level.selected || level.isNew) acc += Number(level.no_of_sections);
        return acc;
    }, 0);
});

const selectedGradeCount = computed(() => {
    return updatedLevels.value.reduce((acc, level) => {
        if (level.selected || level.isNew) acc += 1;
        return acc;
    }, 0);
});
const newLevel = useForm({
    level_id: null,
    name: "",
    number_of_sections: 3,
    level_category_id: null,
});

const customSections = ref(false);
watch(
    newLevel,
    () => {
        if (newLevel.number_of_sections === 0) {
            customSections.value = true;
        }
    },
    {
        deep: true,
    }
);

const isNewLevelFormOpened = ref(false);

function removeLevel(level) {
    updatedLevels.value = updatedLevels.value.filter(
        (l) => l.name !== level.name
    );
}

const updateLevelSection = ref(false);
const levelToUpdateSection = ref(null);

function editSection(index) {
    levelToUpdateSection.value = index;
    updateLevelSection.value = true;
}

function getGlobalIndexForLevel(level) {
    return updatedLevels.value.findIndex((l) => l.id === level.id);
}

const registerBatchesForm = useForm({
    batches: formData.value,
});

function updateSection() {
    if (
        updatedLevels.value &&
        registerBatchesForm.data.batches &&
        levelToUpdateSection.value < updatedLevels.value.length &&
        levelToUpdateSection.value < registerBatchesForm.data.batches.length
    ) {
        updatedLevels.value[levelToUpdateSection.value].no_of_sections =
            registerBatchesForm.data.batches[
                levelToUpdateSection.value
            ].no_of_sections;
    }
    updateLevelSection.value = false;
}

function submitBatches() {
    router.post(
        "/batches/create-bulk",
        {
            batches: formData.value,
        },
        {
            onSuccess: () => {
                emits("success");
            },
        }
    );
}

const sectionsOptions = [
    { value: 1, label: "1 Section" },
    { value: 2, label: "2 Sections" },
    { value: 3, label: "3 Sections" },
    { value: 4, label: "4 Sections" },
    { value: 5, label: "5 Sections" },
    { value: 0, label: "Custom number" },
];

const colors = [
    "bg-blue-500",
    "bg-yellow-500",
    "bg-green-500",
    "bg-red-500",
    "bg-purple-500",
    "bg-pink-500",
    "bg-indigo-500",
    "bg-brand-300",
];
</script>

<style scoped></style>
