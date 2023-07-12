<template>
    <Modal v-model:view="isNewSubjectFormOpened">

        <FormElement
            v-model:show-modal="isNewSubjectFormOpened"
            modal
            :title="$t('registerSubjectForm.formElementTitle')"
            :subtitle="$t('registerSubjectForm.formElementSubtitle')"
            @submit="submit"
        >
            <TextInput
                v-model="newSubject.full_name"
                required
                :placeholder="$t('registerSubjectForm.fullNamePlaceholder')"
                :label="$t('registerSubjectForm.fullNameLabel')"
            />
            <TextInput
                v-model="newSubject.short_name"
                required
                :placeholder="$t('registerSubjectForm.shortNamePlaceholder')"
                :label="$t('registerSubjectForm.shortNameLabel')"
            />
            <TextInput
                v-model="tags"
                :placeholder="$t('registerSubjectForm.tagsPlaceholder')"
                :label="$t('registerSubjectForm.tagsLabel')"
            />

            <div class="relative">
                <TextInput
                    v-model="newSubject.category"
                    required
                    :placeholder="$t('registerSubjectForm.categoryPlaceholder')"
                    :label="$t('registerSubjectForm.categoryLabel')"
                    @click="isDropdownOpen = true"
                />
                <div
                    v-if="isDropdownOpen"
                    class="absolute z-10 mt-2 max-h-60 w-8/12 overflow-auto rounded-md border border-gray-200 bg-white py-1 text-base shadow-lg"

                >
                    <div class="relative flex items-center justify-between px-3 py-2">
                        <TextInput
                            v-if="isDropdownOpen"
                            v-model="newCategory"
                            :placeholder="$t('registerSubjectForm.newCategoryPlaceholder')"
                            class="w-full"
                            @keydown.enter.prevent="addCategory"
                        />
                        <button
                            class="absolute right-0 mr-3 grid h-10 w-10 place-items-center rounded bg-gray-100"
                            type="button"
                            @click="addCategory"
                        >
                            <PlusIcon class="h-4 w-4"/>
                        </button>
                    </div>
                    <template v-if="categoriesToShow.length">
                        <div
                            v-for="category in categoriesToShow"
                            :key="category"
                            class="cursor-pointer px-3 py-2 hover:bg-gray-100"
                            @click="selectCategory(category)"
                        >
                            {{ category }}
                        </div>
                    </template>
                    <template v-else>
                        <div class="px-3 py-2 text-gray-400">{{ $t('registerSubjectForm.noCategoriesFound')}}</div>
                    </template>
                </div>
            </div>
        </FormElement>
    </Modal>
</template>

<script setup>
import {computed, ref, watch} from "vue";
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import {useSubjectStore} from "@/Store/subjects";
import {router} from "@inertiajs/vue3";
import {PlusIcon} from "@heroicons/vue/24/solid";

const props = defineProps({
    open: {
        type: Boolean,
        required: true,
    },
});

const emits = defineEmits(["update:open"]);

const isNewSubjectFormOpened = computed({
    get() {
        return props.open;
    },
    set(value) {
        emits("update:open", value);
    },
});

const {subjects} = useSubjectStore();

const categories = computed(() => {
    return subjects.reduce((acc, subject) => {
        if (!acc.includes(subject["category"])) {
            acc.push(subject["category"]);
        }
        return acc;
    }, []);
});

const newSubject = ref({
    full_name: "",
    category: "",
    isNew: true,
});

const tags = ref("");
const computedShortName = computed(
    () =>
        newSubject.value.full_name.substring(
            0,
            Math.min(3, newSubject.value.full_name.length)
        ).toUpperCase()
);
const formShortName = ref(null);

watch(tags, (value) => {
    newSubject.value.tags = value.split(",");
});

watch([computedShortName, formShortName], () => {
    newSubject.value.short_name = formShortName.value ?? computedShortName.value;
});

const selectCategory = (category) => {
    newSubject.value.category = category;
    isDropdownOpen.value = false;
    newCategory.value = "";
};

const isDropdownOpen = ref(false);
const newCategory = ref("");

const addCategory = () => {
    const category = newCategory.value.trim();
    if (category && !categories.value.includes(category)) {
        categories.value.push(category);
    }
    newSubject.value.category = category;
    newCategory.value = "";
};

const categoriesToShow = computed(() => {
    const searchTerm = newCategory.value.trim().toLowerCase();
    if (!searchTerm) {
        return categories.value;
    }
    return categories.value.filter((category) =>
        category.toLowerCase().includes(searchTerm)
    );
});

const submit = () => {
    router.post(route("subjects.create"), newSubject.value, {
        onSuccess: () => {
            isNewSubjectFormOpened.value = false;
            newSubject.value = {
                full_name: "",
                category: "",
                isNew: true,
            };
            tags.value = "";
        },
    });
};
</script>
