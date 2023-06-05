<template>
    <div
        class="flex w-full flex-col items-center space-y-5 rounded-lg bg-white py-3 shadow-sm"
    >
        <div class="flex w-full justify-between px-5">
            <div
                class="flex grow justify-center space-x-2 text-center text-xl font-semibold underline-offset-4"
            >
                <BookOpenIcon class="w-6" />
                <span>Notes</span>
            </div>
            <div class="flex w-1/12 justify-center">
                <DocumentPlusIcon
                    class="w-5 cursor-pointer text-gray-600 hover:scale-125 hover:text-black"
                    @click="showModal = true"
                />
            </div>
        </div>
        <div
            class="flex w-11/12 flex-col items-center justify-center space-y-6 px-1"
        >
            <div
                v-if="notes.length === 0"
                class="py-5 px-3 text-center text-sm font-light"
            >
                No Notes Associated with {{ student.user.name }}
            </div>
            <div
                v-for="(item, index) in notes"
                :key="index"
                class="flex w-full cursor-pointer justify-center space-x-3"
                @click="selectedNote = item"
            >
                <div
                    class="min-h-full w-[0.01rem] rounded-t-lg rounded-b-md bg-zinc-600 py-2"
                ></div>
                <div class="flex w-full flex-col space-y-1">
                    <div class="text-xs font-medium hover:font-semibold">
                        {{ item.title }}
                    </div>
                    <div
                        class="flex w-full justify-between text-[0.6rem] 2xl:text-xs"
                    >
                        <div class="font-light">
                            {{ moment(item.created_at).fromNow() }}
                        </div>
                        <div>{{ item.author.name }}</div>
                    </div>
                </div>
            </div>
        </div>

        <Modal v-model:view="showModal">
            <FormElement
                :title="'Add Note For ' + student.user.name"
                @submit="submit"
            >
                <TextInput v-model="form.title" placeholder="Title" />
                <TextInput
                    v-model="form.description"
                    placeholder="Description"
                />
            </FormElement>
        </Modal>

        <Modal v-model:view="selectedNote">
            <div class="flex flex-col space-y-2 rounded-lg bg-white p-5">
                <div class="text-center text-xl font-semibold">
                    {{ student.user.name }}'s Note
                </div>
                <div class="text-xl font-medium">
                    {{ selectedNote.title }}
                </div>
                <div>
                    {{ selectedNote.description }}
                </div>

                <div class="text-xs font-light"></div>
                <div class="text-xs font-light">
                    {{ moment(selectedNote.created_at).fromNow() }}
                    By {{ selectedNote.author.name }}
                </div>
            </div>
        </Modal>
        <LinkCell v-if="notes.length" href="/cum" value="Show All Notes" />
    </div>
</template>
<script setup>
import { BookOpenIcon, DocumentPlusIcon } from "@heroicons/vue/24/outline";
import LinkCell from "@/Components/LinkCell.vue";
import Modal from "@/Components/Modal.vue";
import { computed, ref } from "vue";
import FormElement from "@/Components/FormElement.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import moment from "moment";

const showModal = ref(false);
const student = usePage().props.student;
const notes = computed(() => usePage().props.student_notes);
const selectedNote = ref(null);

const form = useForm({
    title: "",
    description: "",
    student_id: student.id,
});

function submit() {
    form.post("/teacher/students/" + student.id + "/notes/create", {
        onSuccess: () => {
            form.reset();
        },
        onFinish: () => {
            showModal.value = false;
        },
    });
}
</script>

<style scoped></style>
