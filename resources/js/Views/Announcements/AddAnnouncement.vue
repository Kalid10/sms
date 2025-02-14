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
            :placeholder="$t('addAnnouncement.body')"
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
                class="mt-1 flex w-full justify-between rounded-md border border-gray-300 bg-white px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
            >
                <div
                    v-for="target in targetGroupOptions"
                    :key="target"
                    :class="{
                        'bg-brand-450 text-white':
                            form.target_group.includes(target),
                    }"
                    class="flex cursor-pointer flex-row justify-between space-x-2 rounded-2xl bg-brand-100 p-2 px-8 text-black"
                    @click="toggleSelection(target)"
                >
                    <CheckCircleIcon
                        v-if="form.target_group.includes(target)"
                        class="w-4"
                    />
                    <span>
                        {{ target }}
                    </span>
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
import { CheckCircleIcon } from "@heroicons/vue/20/solid";

const emit = defineEmits(["success"]);
const targetGroupOptions = ["all", "teachers", "guardians", "admins"];

const showAddAnnouncement = ref(false);

function toggleSelection(target) {
    if (target === "all") {
        if (form.target_group.includes("all")) {
            // If "all" is already selected, deselect everything
            form.target_group = [];
        } else {
            // If "all" is not selected, select everything (excluding "all" itself)
            form.target_group = targetGroupOptions.filter(
                (option) => option !== "all"
            );
            form.target_group.push("all"); // Optionally, you can include "all" in the selected group
        }
    } else {
        const index = form.target_group.indexOf(target);
        if (index < 0) {
            form.target_group.push(target);
        } else {
            form.target_group.splice(index, 1);
        }

        // Check if "all" is in the target group and remove it if individual selection is made
        if (form.target_group.includes("all")) {
            const allIndex = form.target_group.indexOf("all");
            form.target_group.splice(allIndex, 1);
        }

        // If all individual groups are selected, include "all" in the selection (Optional)
        if (form.target_group.length === targetGroupOptions.length - 1) {
            form.target_group.push("all");
        }
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
