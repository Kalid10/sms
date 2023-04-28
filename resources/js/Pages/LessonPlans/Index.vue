<template>
    <div class="min-h-screen bg-gray-100 p-8">
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-3xl font-bold">Lesson Plans</h1>
            <select
                v-model="selectedView"
                class="rounded-md border-gray-300 shadow-sm"
            >
                <option value="fiveDays">Five Days View</option>
                <option value="singleDay">Single Day View</option>
            </select>
        </div>

        <div v-if="selectedView === 'fiveDays'" class="grid grid-cols-4 gap-5">
            <FormCard v-for="i in 5" :key="i" @submit="handleSubmit">
                <template #form>
                    <TextInput label="Topic"/>
                    <TextArea title="Description"/>

                    <div class="mt-4">
                        <label for="select" class="block text-sm font-medium">Select</label>
                        <select
                            id="select"
                            v-model="selected"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        >
                            <option v-for="option in options" :key="option" :value="option">{{ option }}</option>
                        </select>
                    </div>
                </template>
            </FormCard>
        </div>

        <div v-else-if="selectedView === 'singleDay'" class="w-full max-w-md">
            <FormCard @submit="submit">
                <template #form>
                    <TextInput label="Topic"/>
                    <TextArea title="Description"/>
                    <div class="mt-4">
                        <label for="select" class="block text-sm font-medium">Select</label>
                        <select
                            id="select"
                            v-model="selected"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        >
                            <option v-for="option in options" :key="option" :value="option">{{ option }}</option>
                        </select>
                    </div>
                </template>
                <template #card>
                    <div>
                        <p class="text-black">Card Content</p>
                    </div>
                </template>
            </FormCard>
        </div>
    </div>
</template>

<script setup>
import FormCard from "@/Components/FormCard.vue";
import {computed, ref} from 'vue'
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import {useForm, usePage} from "@inertiajs/vue3";

const topic = ref('')
const description = ref('')
const selected = ref('')

const availableBatchSessions = computed(() => usePage().props.availableBatchSessions);

const options = computed(() => {
    return availableBatchSessions.value.map(session => {
        return {
            id: session.id,
            name: session.name
        }
    })
})

const selectedView = ref('fiveDays')

const form = useForm({
    topic: '',
    description: '',
    batch_session_ids: [],
});

const weekOneForm = useForm({
    topic: '',
    description: '',
    batch_session_ids: [],
});

const weekTwoForm = useForm({
    topic: '',
    description: '',
    batch_session_ids: [],
});

const weekThreeForm = useForm({
    topic: '',
    description: '',
    batch_session_ids: [],
});

const weekFourForm = useForm({
    topic: '',
    description: '',
    batch_session_ids: [],
});

const weekFiveForm = useForm({
    topic: '',
    description: '',
    batch_session_ids: [],
});

const weekOneSubmit = () => {
    weekOneForm.post("teacher/lesson-plan/", {
        onSuccess: () => {
            weekOneForm.reset();
        },
    })
}
const weekTwoSubmit = () => {
    weekTwoForm.post("teacher/lesson-plan/", {
        onSuccess: () => {
            weekTwoForm.reset();
        },
    })
}
const weekThreeSubmit = () => {
    weekThreeForm.post("teacher/lesson-plan/", {
        onSuccess: () => {
            weekThreeForm.reset();
        },
    })
}
const weekFourSubmit = () => {
    weekFourForm.post("teacher/lesson-plan/", {
        onSuccess: () => {
            weekFourForm.reset();
        },
    })
}
const weekFiveSubmit = () => {
    weekFiveForm.post("teacher/lesson-plan/", {
        onSuccess: () => {
            weekFiveForm.reset();
        },
    })
}
const submit = () => {
    form.post("teacher/lesson-plan/", {
        onSuccess: () => {
            form.reset();
            form.reset();
        },
    })
}

function handleSubmit() {
    console.log('Submitted:', {
        topic: topic.value,
        description: description.value,
        selected: selected.value
    })
}
</script>
