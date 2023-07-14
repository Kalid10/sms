<template>
    <div
        class="flex w-full flex-col items-center space-y-2 rounded-lg bg-white py-3 shadow-sm"
    >
        <div
            v-if="notes?.data?.length"
            class="flex w-full justify-between px-5"
        >
            <div
                class="flex grow justify-center space-x-2 text-center text-xl font-semibold underline-offset-4"
            >
                <DocumentChartBarIcon class="w-4" />
                <span>{{ $t('studentNotes.notes')}}</span>
            </div>
            <div class="flex w-1/12 justify-center">
                <PlusIcon
                    class="w-4 cursor-pointer text-gray-600 hover:scale-125 hover:text-black"
                    @click="handleAddNote"
                />
            </div>
        </div>
        <div
            class="flex w-full flex-col items-center justify-center space-y-2 px-2"
        >
            <div
                v-for="(item, index) in notes.data"
                :key="index"
                class="flex w-full cursor-pointer justify-center space-x-3 rounded-lg p-2.5 hover:bg-zinc-700 hover:text-gray-50"
                :class="index % 2 === 1 ? 'bg-gray-50' : ''"
                @click="handleClicked(item)"
            >
                <div
                    class="min-h-full w-[0.01rem] rounded-t-lg rounded-b-md bg-zinc-600 py-2"
                ></div>

                <div class="relative flex w-full flex-col space-y-2 pt-1">
                    <div class="text-xs font-medium hover:font-semibold">
                        {{ item.title }}
                    </div>
                    <div
                        class="flex w-full items-center justify-between text-[0.55rem] font-light 2xl:text-xs"
                    >
                        <div class="w-5/12">
                            {{ moment(item.created_at).fromNow() }}
                        </div>
                        <div class="w-6/12 text-center">
                            {{ item.author.name }}
                        </div>

                        <TrashIcon
                            class="w-3 text-red-600 hover:scale-110"
                            @click.stop="deleteNote(item.id)"
                        />
                    </div>
                </div>
            </div>
            <div
                v-if="!notes?.data.length"
                class="flex w-full flex-col items-center py-2"
                @click="showModal = true"
            >
                <EmptyView
                    :title="'No notes found for ' + student?.user.name"
                />
                <SecondaryButton
                    :title="$t('studentNotes.addNote')"
                    class="mt-4 w-9/12 !rounded-2xl border-none bg-zinc-700 text-white"
                />
            </div>
        </div>

        <Modal v-model:view="showModal">
            <FormElement
                :title="'Add Note For ' + student.user.name"
                @submit="submit"
            >
                <TextInput v-model="form.title" :placeholder="$t('studentNotes.title')"/>
                <TextArea
                    v-model="form.description"
                    :placeholder="$t('studentNotes.description')"
                    :label="$t('studentNotes.description')"
                />
            </FormElement>
        </Modal>

        <Modal v-model:view="selectedNote">
            <FormElement
                v-if="selectedNote.author.id === user.id"
                :title="`Update ` + student.user.name + `'s note`"
                @submit="update"
            >
                <TextInput
                    v-model="form.title"
                    :label="$t('studentNotes.title')"
                    :placeholder="$t('studentNotes.title')"
                >
                </TextInput>
                <TextArea
                    v-model="form.description"
                    :placeholder="$t('studentNotes.description')"
                    :label="$t('studentNotes.description')"
                />
            </FormElement>

            <div
                v-if="selectedNote.author.id !== user.id"
                class="flex flex-col space-y-2 rounded-lg bg-white p-5"
            >
                <div class="text-center text-xl font-semibold">
                    {{ student.user.name }}'s Note
                </div>
                <div class="flex flex-col gap-5 space-y-4 rounded-t-lg p-2">
                    <div class="flex items-center font-medium">
                        {{ selectedNote.title }}
                    </div>

                    <div class="flex items-center text-sm font-light">
                        {{ selectedNote.description }}
                    </div>

                    <div
                        class="flex flex-col items-end space-y-1 text-sm font-light"
                    >
                        <div class="text-xs">
                            {{ moment(selectedNote.created_at).fromNow() }}
                        </div>
                        <div class="font-medium">
                            By {{ selectedNote.author.name }}
                        </div>
                    </div>
                </div>
            </div>
        </Modal>

        <Pagination
            :preserve-state="true"
            :links="notes.links"
            position="center"
        />
    </div>
</template>
<script setup>
import {
    DocumentChartBarIcon,
    PlusIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";
import Modal from "@/Components/Modal.vue";
import { computed, ref } from "vue";
import FormElement from "@/Components/FormElement.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import moment from "moment";
import Pagination from "@/Components/Pagination.vue";
import TextArea from "@/Components/TextArea.vue";
import EmptyView from "@/Views/EmptyView.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const showModal = ref(false);
const student = usePage().props.student;
const notes = computed(() => usePage().props.student_notes);
const selectedNote = ref(null);

const user = usePage().props.auth.user;

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

function handleAddNote() {
    showModal.value = true;
    form.reset();
}

function handleClicked(note) {
    selectedNote.value = note;
    if (note.author.id === user.id) {
        form.title = note.title;
        form.description = note.description;
    }
}

function update() {
    form.post(
        "/teacher/students/" +
            student.id +
            "/notes/update/" +
            selectedNote.value.id,
        {
            onSuccess: () => {
                form.reset();
                selectedNote.value = false;
            },
            onFinish: () => {
                showModal.value = false;
            },
        }
    );
}

function deleteNote(id) {
    form.delete("/teacher/students/" + student.id + "/notes/delete/" + id, {
        onSuccess: () => {
            form.reset();
            showModal.value = false;
        },
    });
}
</script>

<style scoped></style>
