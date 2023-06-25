<template>
    <div class="flex w-full flex-col space-y-5 rounded-lg bg-white p-5">
        <div class="w-full text-center text-2xl font-medium">
            {{ flaggable.user.name }} Flag Form
        </div>
        <SelectInput
            v-model="form.flag_type"
            label="Flag Type"
            placeholder="Select Type"
            :options="flagTypeOptions"
            :error="form.errors.flag_type"
        />
        <SelectInput
            v-if="
                batchSubjectOptions?.length &&
                isTeacher() &&
                view !== 'homeroom'
            "
            v-model="form.batch_subject_id"
            label="Subject"
            placeholder="Select Subject"
            :options="batchSubjectOptions"
            :error="form.errors.batch_subject_id"
        />
        <TextArea
            v-model="form.description"
            placeholder="Enter Flag Description"
            rows="10"
            label="Description"
            :error="form.errors.description"
        />

        <DatePicker v-model="form.expires_at" label="Expiry Date" />

        <div class="flex w-full justify-end">
            <PrimaryButton
                class="w-3/12 !rounded-2xl bg-zinc-800 text-white shadow-sm"
                title="Submit"
                @click="handleAddFlag"
            />
        </div>
    </div>
</template>
<script setup>
import { isTeacher } from "@/utils";
import SelectInput from "@/Components/SelectInput.vue";
import { useForm } from "@inertiajs/vue3";
import moment from "moment/moment";
import { computed } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DatePicker from "@/Components/DatePicker.vue";
import TextArea from "@/Components/TextArea.vue";

const emit = defineEmits(["done"]);
const props = defineProps({
    flaggable: {
        type: Number,
        required: true,
    },
    batchSubjectOptions: {
        type: Object,
        default: null,
    },
    view: {
        type: String,
        default: "student",
    },
    selectedFlag: {
        type: Object,
        default: null,
    },
});

const form = useForm({
    batch_subject_id:
        props.view === "student" ? props.batchSubjectOptions[0].value : null,
    flag_type: props.selectedFlag?.type[0] || "",
    description: props.selectedFlag?.description || "",
    flaggable_id: props.flaggable.id,
    expires_at: new Date(moment().add(1, "weeks")),
    is_homeroom: props.view === "homeroom",
});

const flagTypeOptions = computed(() => [
    { value: "academic", label: "Academic" },
    { value: "attendance", label: "Attendance" },
    { value: "behavioral", label: "Behavioral" },
    { value: "other", label: "Other" },
]);
const handleAddFlag = () => {
    form.post("/teacher/students/flag", {
        preserveState: true,
        onSuccess: () => {
            emit("done");
            form.reset();
        },
    });
};
</script>
<style scoped></style>
