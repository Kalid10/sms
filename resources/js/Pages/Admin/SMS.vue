<template>
    <div class="flex items-center gap-5">
        <h1 class="m-2 font-semibold text-brand-text-450">
            Hello there, With a single click, you can instantly send crucial
            message;
        </h1>
        <div class="my-5 flex flex-col gap-5">
            <p
                class="flex cursor-pointer items-center gap-2 text-blue-700"
                @click="parentsLink"
            >
                <ChatBubbleBottomCenterTextIcon class="h-5 w-5" />
                Parents phone information
            </p>
            <p
                class="flex cursor-pointer items-center gap-2 text-blue-700"
                @click="teachersLink"
            >
                <ChatBubbleBottomCenterTextIcon class="h-5 w-5" />
                Teachers phone information
            </p>
        </div>
    </div>
    <FormElement title="SMS" subtitle="" @submit="submit">
        <TextInput v-model="form.phone" placeholder="phone number" />
        <TextInput v-model="form.message" placeholder="message" />
    </FormElement>
</template>
<script setup>
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import { router, useForm } from "@inertiajs/vue3";
import { ChatBubbleBottomCenterTextIcon } from "@heroicons/vue/24/outline";

const form = useForm({
    phone: "",
    message: "",
});

const submit = () => {
    form.post(route("sms.send"), {
        onSuccess: () => {
            form.reset();
        },
    });
};

function teachersLink() {
    router.get("/admin/teachers");
}

function parentsLink() {
    router.get("/admin/students");
}
</script>
