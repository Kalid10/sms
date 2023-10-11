<template>
    <div class="w-full rounded-lg bg-white">
        <FormElement
            title="Add Chapter"
            @submit="submit"
            @cancel="form.reset()"
        >
            <TextInput
                v-model="form.title"
                placeholder="Title"
                label="Title"
                class="w-full"
                :error="form.errors.title"
            />
            <TextInput
                v-model="form.start_page"
                placeholder="Start Page"
                label="Start Page"
                class="w-full"
                :error="form.errors.start_page"
                type="number"
            />
            <TextInput
                v-model="form.end_page"
                placeholder="End Page"
                label="End Page"
                class="w-full"
                :error="form.errors.end_page"
                type="number"
            />

            <TextArea
                v-model="form.summary"
                placeholder="Summary"
                label="Summary"
            />
        </FormElement>
    </div>
</template>
<script setup>
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import TextArea from "@/Components/TextArea.vue";

const emit = defineEmits(["success"]);

const book = computed(() => usePage().props.book);

const form = useForm({
    title: "",
    start_page: "",
    end_page: "",
    summary: "",
});

const submit = () => {
    form.post("/admin/books/" + book.value.id + "/chapter/create", {
        onSuccess: () => {
            emit("success");
        },
    });
};
</script>
<style scoped></style>
