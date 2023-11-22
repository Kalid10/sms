<template>
    <div
        class="container mx-auto flex max-w-7xl flex-col gap-4 px-2 pt-6 md:p-6"
    >
        <div class="flex flex-col">
            <Heading>
                {{ $t("registerSubjects.registerSubjects") }}
            </Heading>
            <h3 class="text-brand-text-600 text-sm">
                {{ $t("registerSubjects.descriptionOne") }}
                <span class="inline-block">
                    {{ $t("registerSubjects.descriptionTwo") }}
                </span>
            </h3>
        </div>

        <div class="flex flex-wrap gap-2 md:gap-5">
            <span
                class="text-brand-text-600 col-span-1 text-sm sm:col-span-2 md:col-span-3 lg:col-span-3 xl:col-span-4"
            >
                <span class="whitespace-nowrap font-semibold text-black">{{
                    updatedSubjects.length
                }}</span>
                {{ $t("registerSubjects.totalSubjects") }}
            </span>
            <span
                class="text-brand-text-600 col-span-1 text-sm sm:col-span-2 md:col-span-3 lg:col-span-3 xl:col-span-4"
            >
                <span class="whitespace-nowrap font-semibold text-black">{{
                    selectedSubjects.length
                }}</span>
                {{ $t("registerSubjects.subjectsSelected") }}
            </span>
            <span
                v-if="newSubjects.length > 0"
                class="text-brand-text-600 col-span-1 text-sm sm:col-span-2 md:col-span-3 lg:col-span-3 xl:col-span-4"
            >
                <span class="whitespace-nowrap font-semibold text-black">{{
                    newSubjects.length
                }}</span>
                {{ $t("registerSubjects.newSubject") }}
                {{ newSubjects.length > 1 ? "s" : "" }}
            </span>
        </div>

        <div class="flex flex-col gap-4">
            <div
                v-for="(category, c) in categories"
                :key="c"
                class="flex flex-col gap-2"
            >
                <div class="flex items-center gap-2">
                    <div
                        class="z-10 h-3.5 w-3.5 rounded-full"
                        :class="colors[c]"
                    />
                    <Heading size="sm" class="text-brand-text-600 font-normal"
                        >{{ category }}
                    </Heading>
                </div>
                <div
                    class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4"
                >
                    <label
                        v-for="(subject, s) in updatedSubjects.filter(
                            (sub) => sub.category === category
                        )"
                        :key="s"
                    >
                        <Card
                            :class="{
                                'border-black':
                                    !!filteredLabel &&
                                    subjectIncludesLabel(subject),
                                'opacity-20': !subjectIncludesLabel(subject),
                                'border-black shadow-md': subject.isNew,
                            }"
                            class="group !min-w-full transition duration-150"
                        >
                            <div
                                :class="
                                    subject.selected || subject.isNew
                                        ? 'opacity-100'
                                        : 'opacity-25'
                                "
                                class="flex flex-col justify-between gap-6"
                            >
                                <div class="flex items-center justify-between">
                                    <h3 class="flex items-baseline gap-2">
                                        <span class="font-semibold">
                                            {{ subject.full_name }}
                                        </span>
                                        <span
                                            class="text-brand-text-600 text-sm uppercase"
                                        >
                                            {{ subject.short_name }}
                                        </span>
                                    </h3>

                                    <Checkbox
                                        v-if="!!!subject.isNew"
                                        v-model="subject.selected"
                                        class="h-5 w-5 !rounded-full border-none bg-transparent checked:ring-0 focus:ring-0"
                                    />
                                    <TrashIcon
                                        v-if="subject.isNew"
                                        class="h-5 w-5 cursor-pointer stroke-black stroke-2"
                                        @click="removeSubject(subject)"
                                    ></TrashIcon>
                                </div>

                                <span
                                    class="flex origin-bottom-left scale-[.85] flex-wrap gap-1"
                                >
                                    <span
                                        v-for="(label, l) in subject.tags"
                                        :key="l"
                                        :class="{
                                            'bg-brand-200':
                                                filteredLabel === label,
                                        }"
                                        class="cursor-pointer whitespace-nowrap rounded-xl px-1.5 py-0.5 text-xs font-semibold leading-none transition duration-300 hover:scale-110 hover:bg-brand-200"
                                        @click="
                                            ($e) => {
                                                $e.preventDefault();
                                            }
                                        "
                                        @mouseout="resetFilteredLabel"
                                        @mouseover="filterTags(label)"
                                    >
                                        {{ toHashTag(label) }}
                                    </span>
                                </span>
                            </div>
                        </Card>
                    </label>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <div class="flex items-center gap-2">
                    <div class="z-10 h-3.5 w-3.5 rounded-full bg-brand-200" />
                    <Heading size="sm" class="text-brand-text-600 font-normal">
                        {{ $t("registerSubjects.other") }}
                    </Heading>
                </div>

                <div
                    class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4"
                >
                    <button
                        class="flex min-h-[82px] flex-col items-center justify-center gap-2 rounded-md border-2 border-dashed bg-white/75"
                        @click="openNewSubjectForm"
                    >
                        <PlusCircleIcon
                            class="h-5 w-5 stroke-gray-500 stroke-2"
                        />
                        <span
                            class="text-brand-text-600 text-sm font-semibold"
                            >{{ $t("registerSubjects.createNewSubject") }}</span
                        >
                    </button>
                    <button
                        class="flex min-h-[82px] flex-col items-center justify-center gap-2 rounded-md border-2 border-dashed bg-white/75"
                        @click="openNewSubjectForm"
                    >
                        <span class="flex">
                            <span
                                class="z-0 h-3.5 w-3.5 translate-x-1/3 rounded-full bg-blue-400"
                            />
                            <span
                                class="z-10 h-3.5 w-3.5 rounded-full bg-yellow-400"
                            />
                            <span
                                class="relative z-20 grid h-3.5 w-3.5 -translate-x-1/3 place-items-center rounded-full border-2 border-gray-500 bg-white leading-none"
                            >
                                <PlusIcon
                                    class="h-2 w-2 stroke-gray-500 stroke-[5]"
                                />
                            </span>
                        </span>
                        <span
                            class="text-brand-text-600 text-sm font-semibold"
                            >{{
                                $t("registerSubjects.createNewCategory")
                            }}</span
                        >
                    </button>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <PrimaryButton @click="submitSubjects"
                >{{ $t("registerSubjects.finish") }}
            </PrimaryButton>
            <TertiaryButton
                v-if="selectedSubjects.length < updatedSubjects.length"
                @click="resetSubjects"
                >{{ $t("registerSubjects.reset") }}
            </TertiaryButton>
        </div>

        <Modal v-model:view="isNewSubjectFormOpened">
            <FormElement
                v-model:show-modal="isNewSubjectFormOpened"
                modal
                :title="$t('registerSubjects.newSubjectTitle')"
                :subtitle="$t('registerSubjects.newSubjectSubtitle')"
                @submit="addToSubjectsList"
            >
                <TextInput
                    v-model="newSubject.full_name"
                    required
                    :placeholder="
                        $t('registerSubjects.subjectFullNamePlaceholder')
                    "
                    :label="$t('registerSubjects.newSubjectFullNameLabel')"
                />
                <TextInput
                    v-model="newSubject.short_name"
                    required
                    :placeholder="
                        $t('registerSubjects.subjectShortNamePlaceholder')
                    "
                    :label="$t('registerSubjects.subjectShortNameLabel')"
                />
                <TextInput
                    v-model="tags"
                    :placeholder="$t('registerSubjects.subjectTagPlaceholder')"
                    :label="$t('registerSubjects.subjectTagLabel')"
                />
                <SelectInput
                    v-if="!customCategory"
                    v-model="newSubject.category"
                    :options="categoryOptions"
                    required
                    :placeholder="
                        $t(
                            'registerSubjects.subjectCategoryOptionsPlacehold' +
                                'er'
                        )
                    "
                    :label="$t('registerSubjects.subjectCategoryOptionsLabel')"
                />
                <TextInput
                    v-else
                    id="categoryInput"
                    v-model="newSubject.category"
                    required
                    :placeholder="
                        $t('registerSubjects.subjectCategoryPlaceholder')
                    "
                    :label="$t('registerSubjects.subjectCategoryLabel')"
                />
            </FormElement>
        </Modal>
    </div>
</template>

<script setup>
import { computed, nextTick, ref, watch } from "vue";
import { subjects as initialSubjects } from "@/fake.js";
import { toHashTag } from "@/utils.js";
import { PlusCircleIcon, PlusIcon, TrashIcon } from "@heroicons/vue/24/outline";
import Heading from "@/Components/Heading.vue";
import Card from "@/Components/Card.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";
import { router } from "@inertiajs/vue3";

const emits = defineEmits(["success"]);

const subjects = computed(() => initialSubjects);
const categories = computed(() => {
    return updatedSubjects.value.reduce((acc, subject) => {
        if (!acc.includes(subject["category"])) {
            acc.push(subject["category"]);
        }
        return acc;
    }, []);
});

const updatedSubjects = ref(
    subjects.value.map((subject) => {
        return {
            ...subject,
            selected: true,
        };
    })
);
const newSubjects = computed(() =>
    updatedSubjects.value.filter((subject) => subject.isNew)
);
const selectedSubjects = computed(() =>
    updatedSubjects.value.filter((subject) => subject.selected || subject.isNew)
);

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

function submitSubjects() {
    router.post(
        "/subjects/create-bulk",
        {
            subjects: selectedSubjects.value.map((subject) => {
                return {
                    full_name: subject["full_name"],
                    short_name: subject["short_name"],
                    category: subject["category"],
                    tags: subject["tags"],
                };
            }),
        },
        {
            onSuccess: () => {
                emits("success");
            },
        }
    );
}

const newSubject = ref({
    full_name: "",
    category: null,
    isNew: true,
});

const tags = ref("");
const computedShortName = computed(() =>
    newSubject.value.full_name
        .substring(0, Math.min(3, newSubject.value.full_name.length))
        .toUpperCase()
);
const formShortName = ref(null);

watch(tags, (value) => {
    newSubject.value.tags = value.split(",");
});

watch([computedShortName, formShortName], () => {
    newSubject.value.short_name =
        formShortName.value ?? computedShortName.value;
});

const categoryOptions = computed(() => {
    const options = categories.value.map((category) => {
        return {
            label: category,
            value: category,
        };
    });
    options.push({
        label: "+ Add new Category",
        value: "",
    });

    return options;
});

function addToSubjectsList() {
    updatedSubjects.value.push(newSubject.value);
    isNewSubjectFormOpened.value = false;
    newSubject.value = {
        full_name: "",
        category: null,
        isNew: true,
    };
}

const isNewSubjectFormOpened = ref(false);

function openNewSubjectForm() {
    isNewSubjectFormOpened.value = true;
}

function removeSubject(subject) {
    updatedSubjects.value = updatedSubjects.value.filter(
        (sub) => sub !== subject
    );
}

function resetSubjects() {
    updatedSubjects.value = subjects.value.map((subject) => {
        return {
            ...subject,
            selected: true,
        };
    });
}

function subjectIncludesLabel(subject) {
    if (filteredLabel.value === null) return true;
    return subject.tags.includes(filteredLabel.value);
}

const filteredLabel = ref(null);

function filterTags(label) {
    filteredLabel.value = label;
}

function resetFilteredLabel() {
    filteredLabel.value = null;
}

const customCategory = ref(false);
watch(
    newSubject,
    () => {
        if (newSubject.value.category === "") {
            customCategory.value = true;
            nextTick(() => {
                const categoryInput = document.getElementById("categoryInput");
                categoryInput.focus();
            });
        }
    },
    {
        deep: true,
    }
);
</script>
