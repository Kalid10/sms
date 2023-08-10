<template>
    <div class="flex w-full flex-col space-y-5 rounded-lg bg-white p-5">
        <div class="w-full text-center text-2xl font-medium">
            {{ flaggable.user.name }} {{ $t("addFlag.flagForm") }}
        </div>

        <div class="flex flex-col">
            <label
                for="target-group"
                class="block text-sm font-medium text-black"
                >{{ $t("addFlag.selectFlagType") }}</label
            >
            <div
                class="mt-1 flex w-full justify-between rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
            >
                <div
                    v-for="type in flagTypeOptions"
                    :key="type"
                    :class="{
                        'bg-brand-400 text-white':
                            form.flag_type.includes(type),
                    }"
                    class="flex cursor-pointer flex-row justify-between space-x-2 rounded-2xl bg-brand-100 p-2 px-8 text-black"
                    @click="toggleSelection(type)"
                >
                    <CheckCircleIcon
                        v-if="form.flag_type.includes(type)"
                        class="w-4"
                    />
                    <span>
                        {{ type }}
                    </span>
                </div>
            </div>
            <div v-if="form.errors.flag_type" class="text-xs text-negative-50">
                * {{ form.errors.flag_type }}
            </div>
        </div>
        <SelectInput
            v-if="
                batchSubjectOptions?.length &&
                isTeacher() &&
                view !== 'homeroom'
            "
            v-model="form.batch_subject_id"
            :label="$t('common.subject')"
            :placeholder="$t('addFlag.selectSubject')"
            :options="batchSubjectOptions"
            :error="form.errors.batch_subject_id"
        />
        <TextArea
            v-model="form.description"
            :label="$t('common.description')"
            :placeholder="$t('addFlag.enterFlagDescription')"
            rows="10"
            :error="form.errors.description"
        />

        <DatePicker
            v-model="form.expires_at"
            :label="$t('addFlag.expiryDate')"
        />

        <div class="flex w-full justify-end">
            <PrimaryButton
                class="w-3/12 !rounded-2xl bg-brand-450 text-white shadow-sm"
                :title="$t('common.submit')"
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
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DatePicker from "@/Components/DatePicker.vue";
import TextArea from "@/Components/TextArea.vue";
import { CheckCircleIcon } from "@heroicons/vue/20/solid";

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
    flag_type: props.selectedFlag?.type || [],
    description: props.selectedFlag?.description || "",
    flaggable_id: props.flaggable.id,
    expires_at: new Date(moment().add(1, "weeks")),
    is_homeroom: props.view === "homeroom",
});

const flagTypeOptions = ["academic", "attendance", "behavioral", "other"];
const handleAddFlag = () => {
    form.post("/teacher/students/flag", {
        preserveState: true,
        onSuccess: () => {
            emit("done");
            form.reset();
        },
    });
};

function toggleSelection(type) {
    const index = this.form.flag_type.indexOf(type);
    if (index < 0) {
        this.form.flag_type.push(type);
    } else {
        this.form.flag_type.splice(index, 1);
    }
}
</script>
<style scoped></style>
