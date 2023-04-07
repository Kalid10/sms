<template>
    <Modal v-model:view="isNewSubjectFormOpened">

        <FormElement
            v-model:show-modal="isNewSubjectFormOpened"
            modal
            title="New Subject"
            subtitle="Create a new subject and assign it to a category"
            @submit="submit"
        >
            <TextInput
                v-model="newSubject.full_name"
                required
                placeholder="Name of the new Subject"
                label="Subject Name"
            />
            <TextInput
                v-model="newSubject.short_name"
                required
                placeholder="Short name for Subject"
                label="Subject Short Name"
            />
            <TextInput
                v-model="tags"
                placeholder="Assign tags (separate multiple tags with comma)"
                label="Subject Tags"
            />

            <div class="relative">
                <TextInput
                    v-model="newSubject.category"
                    required
                    placeholder="Enter a category"
                    label="Subject Category"
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
                            placeholder="New category"
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
                        <div class="px-3 py-2 text-gray-400">No categories found</div>
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
    toggle: {
        type: Boolean,
        required: true,
    },
});

const isNewSubjectFormOpened = ref(props.toggle);

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
        },
    });
};
</script>
