<template>
    <FormElement
        :title="$t('addAnnouncement.addAnnouncement')"
        @submit="addAnnouncement"
        @cancel="form.reset()"
    >
        <TextInput
            v-model="form.title"
            :label="$t('addAnnouncement.title')"
            :placeholder="$t('addAnnouncement.title')"
            class="w-full"
            :error="form.errors.title"
            required
        />

        <TextArea
            v-model="form.body"
            :label="$t('addAnnouncement.body')"
            :placeholder="$t('addAnnouncement.description')"
            class="w-full"
            :error="form.errors.body"
        />

        <div class="flex flex-col">
            <label
                for="target-group"
                class="block text-sm font-medium text-black"
                >{{ $t("addAnnouncement.selectTargetGroup") }}</label
            >
            <div
                class="mt-1 flex w-full justify-between rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
            >
                <div
                    v-for="target in targetGroupOptions"
                    :key="target"
                    :class="{
                        'bg-brand-450 text-white':
                            form.target_group.includes(target),
                    }"
                    class="flex cursor-pointer flex-row justify-between rounded bg-brand-100 p-2 px-8 text-black"
                    @click="toggleSelection(target)"
                >
                    {{ target }}
                </div>
            </div>
            <div
                v-if="form.errors.target_group"
                class="text-xs text-negative-50"
            >
                * {{ form.errors.target_group }}
            </div>
        </div>

        <DatePicker
            v-model="form.expires_on"
            :label="$t('addAnnouncement.expireDate')"
            :placeholder="$t('addAnnouncement.expiresOn')"
            class="w-full"
        />
    </FormElement>
</template>
<script setup>
import moment from "moment";
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import DatePicker from "@/Components/DatePicker.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const emit = defineEmits(["success"]);
const targetGroupOptions = ["all", "teachers", "guardians", "admins"];

const showAddAnnouncement = ref(false);

function toggleSelection(target) {
    const index = this.form.target_group.indexOf(target);
    if (index < 0) {
        this.form.target_group.push(target);
    } else {
        this.form.target_group.splice(index, 1);
    }
}

const addAnnouncement = () => {
    form.post("/announcements/create", {
        onSuccess: () => {
            showAddAnnouncement.value = false;
            // Reset the form but a set expires_on back to a Date object
            form.reset();
            form.expires_on = new Date();
            emit("success");
        },
    });
};
const form = useForm({
    title: "",
    body: "",
    expires_on: new Date(),
    target_group: [],
}).transform((data) => ({
    ...data,
    expires_on: moment(data.expires_on).format("YYYY-MM-DD HH:mm:ss"),
}));
</script>
<style scoped></style>
