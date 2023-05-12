<template>
    <div class="flex flex-col">
        <DelegateLink></DelegateLink>
        <Heading>Register Grades</Heading>
        <h3 class="text-sm text-gray-500">
            We have selected a set of default grades for you. You can remove
            grades you don't need, add new grades or edit the number of sections
            in each grade.
            <span class="inline-block"
                >Click on the "Finish" to proceed, and "Next" to save and
                proceed.</span
            >
        </h3>
    </div>

    <div class="flex gap-5">
        <span
            class="col-span1 text-sm text-gray-500 sm:col-span-2 md:col-span-3 lg:col-span-3 xl:col-span-4"
        >
            <span class="font-semibold text-black">{{
                selectedGradeCount
            }}</span>
            Grades Selected
        </span>

        <span
            class="col-span1 text-sm text-gray-500 sm:col-span-2 md:col-span-3 lg:col-span-3 xl:col-span-4"
        >
            <span class="font-semibold text-black">{{ batchesCount }}</span>
            Sections Selected
        </span>
    </div>

    <div
        v-for="(levelCategory, lc) in levelCategories"
        :key="lc"
        class="flex flex-col gap-4"
    >
        <div class="flex items-center gap-2">
            <div class="z-10 h-3.5 w-3.5 rounded-full" :class="colors[lc]" />
            <Heading size="sm" class="font-normal text-gray-500">
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
                                <span v-if="level.name.length < 3">Grade</span>
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
                            <span>Sections</span>
                            <span class="font-semibold">{{
                                level.no_of_sections
                            }}</span>
                            <button
                                class="ml-2 grid h-6 w-6 cursor-pointer place-items-center rounded-full bg-neutral-200 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                                @click="editSection(l)"
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

        <Modal v-model:view="updateLevelSection">
            <FormElement
                v-model:show-modal="updateLevelSection"
                modal
                title="Number of Sections"
                subtitle="Update the number of Sections for Grade 7"
            >
                <TextInput
                    v-model="updatedLevels[levelToUpdateSection].no_of_sections"
                    placeholder="Specify the number of sections for Grade 7"
                />
            </FormElement>
        </Modal>
    </div>

    <div class="flex items-center justify-end gap-3">
        <PrimaryButton @click="submitBatches">Finish</PrimaryButton>
    </div>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import Heading from "@/Components/Heading.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Card from "@/Components/Card.vue";
import { PencilIcon, TrashIcon } from "@heroicons/vue/24/outline";
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DelegateLink from "@/Views/DelegateLink.vue";
import { useGettingStartedStore } from "@/Store/getting-started";

const emits = defineEmits(["success"]);

const gettingStartedStore = useGettingStartedStore();

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
const formData = computed(() => {
    return updatedLevels.value.map((level) => {
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
    name: "",
    number_of_sections: 3,
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

function addNewLevel() {
    updatedLevels.value.push({
        name: newLevel.name,
        isNew: true,
        no_of_sections: newLevel.number_of_sections,
    });
    newLevel.reset();
    isNewLevelFormOpened.value = false;
}

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

const registerBatchesForm = useForm({
    batches: formData.value,
});

function submitBatches() {
    router.post(
        "/batches/create-bulk",
        {
            batches: formData.value,
        },
        {
            onSuccess: () => {
                console.log("Success");
                emits("success");
            },
            onError: (error) => {
                console.log("Error");
                console.log(error);
            },
        }
    );
}

const colors = [
    "bg-blue-500",
    "bg-yellow-500",
    "bg-green-500",
    "bg-red-500",
    "bg-purple-500",
    "bg-pink-500",
    "bg-indigo-500",
    "bg-gray-500",
];
</script>

<style scoped></style>
