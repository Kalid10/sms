<template>
    <div class="flex w-11/12 flex-col items-center justify-center space-y-2">
        <Title title="Add Book" class="w-full" />
        <div
            class="flex w-full flex-col items-center justify-center space-y-5 rounded-lg bg-white p-4"
        >
            <div class="w-full text-center text-xl font-semibold">
                Basic Information
            </div>
            <TextInput
                v-model="form.title"
                :error="form.errors.title"
                placeholder="Title"
                label="Title"
                class="w-full"
            />
            <SelectInput
                v-model="form.level_id"
                :options="levelOptions"
                placeholder="Grade"
                label="Select Grade"
                :error="form.errors.level_id"
                class="w-full"
            />
            <SelectInput
                v-model="form.subject_id"
                :options="subjectOptions"
                :error="form.errors.subject_id"
                placeholder="Subject"
                label="Select Subject"
                class="w-full"
            />
            <div class="flex w-full justify-end space-x-2">
                <PrimaryButton
                    title="Clear"
                    class="bg-red-600 text-white"
                    @click="form.reset()"
                />
                <PrimaryButton
                    title="Next"
                    class="bg-brand-400 text-white"
                    @click="submitForm"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Title from "@/Views/Teacher/Views/Title.vue";

const books = computed(() => usePage().props.books);
const levels = computed(() => usePage().props.levels);
const subjects = computed(() => usePage().props.subjects);
const showUploadBookCover = ref(false);
const levelOptions = computed(() => {
    return levels.value.map((level) => {
        return {
            value: level.id,
            label: "Grade " + level.name,
        };
    });
});

const subjectOptions = computed(() => {
    return subjects.value.map((subject) => {
        return {
            value: subject.id,
            label: subject.full_name,
        };
    });
});

const form = useForm({
    title: "",
    level_id: "",
    subject_id: "",
});

const submitForm = () => {
    form.post("/admin/books/create", {
        onSuccess: () => {
            showUploadBookCover.value = true;
        },
    });
};
</script>

<style scoped></style>
