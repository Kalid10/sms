<template>
    <div class="flex flex-col gap-4">
        <Heading size="md">Weekly Sessions</Heading>

        <template v-if="!!!weeklySessions">
            <form class="flex w-fit gap-2">
                <div class="flex flex-col">
                    <TextInput
                        v-model="form.frequency"
                        type="number"
                        class="w-64"
                        placeholder="Set the number of sessions per week"
                    />
                    <div class="flex items-center gap-2">
                        <Toggle v-model="form.allSections" />
                        <Heading size="sm" class="!font-light normal-case"
                            >Set for all other sections</Heading
                        >
                    </div>
                </div>
                <PrimaryButton type="button" class="h-fit" @click="setSession"
                    >Set Sessions</PrimaryButton
                >
            </form>
        </template>
    </div>
</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Toggle from "@/Components/Toggle.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const weeklySessions = computed(
    () => usePage().props.batchSubject["weekly_frequency"]
);

const form = useForm({
    batch_subject: usePage().props.batchSubject.id,
    frequency: null,
    all_sections: true,
});

function setSession() {
    form.post("/batches/subjects/set-sessions", {
        preserveState: true,
    });
}
</script>

<style scoped></style>
