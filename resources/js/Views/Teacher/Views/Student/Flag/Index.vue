<template>
    <div
        class="flex h-full flex-col items-center space-y-5 rounded-lg bg-white p-3 shadow-sm"
    >
        <!--        Header-->
        <div class="flex h-fit w-full items-center justify-between px-2">
            <InformationCircleIcon
                class="w-4 cursor-pointer text-gray-600 hover:scale-125 hover:text-black"
                @click="showInfoModal = true"
            />
            <span
                class="-skew-x-3 px-3 py-0.5 text-center font-semibold"
                :class="
                    flags?.data?.length ? 'bg-red-600 text-white italic' : ' '
                "
            >
                {{ student.user.name }}'s Flag List
            </span>
            <SecondaryButton
                class="h-fit !rounded-2xl bg-zinc-700 !px-4 !py-1 !text-xs text-white"
                title="Add"
                @click="showAddModal = true"
            />
        </div>

        <!--        List-->
        <div v-if="flags?.data?.length" class="flex w-full flex-col">
            <div
                class="mb-2 flex w-full bg-zinc-800 py-2 text-center text-xs text-white"
            >
                <span class="w-4/12 text-center"> CreatedAt </span>
                <span class="w-4/12"> Type </span>
                <span class="w-4/12 text-center">Expiry Date </span>
            </div>
            <div
                v-for="(item, index) in flags.data"
                :key="index"
                class="flex w-full cursor-pointer justify-evenly space-x-2 px-2 py-4 text-xs hover:scale-105 hover:rounded-lg hover:bg-zinc-700 hover:text-gray-200"
                :class="index % 2 === 1 ? 'bg-gray-100/70' : ''"
                @click="
                    selectedFlagItem = item;
                    showDetailModal = true;
                "
            >
                <span class="w-4/12 text-center">
                    {{ moment(item.created_at).format("MMMM DD, YYYY") }}
                </span>
                <span
                    class="w-3/12 rounded-3xl bg-red-600 py-0.5 text-center text-[0.65rem] font-medium uppercase text-white"
                >
                    {{ item.type }}
                </span>
                <span class="w-4/12 text-center italic">
                    {{ moment(item.expires_at).fromNow() }}
                </span>
            </div>
            <Pagination :links="flags.links" class="pt-3" position="center" />
        </div>
        <div v-else>
            <EmptyView title="No Flags Found" />
        </div>
    </div>
    <Modal v-model:view="showAddModal">
        <div class="flex w-full flex-col space-y-5 rounded-lg bg-white p-5">
            <div class="w-full text-center text-2xl font-medium">
                Student Flag Form
            </div>
            <SelectInput
                v-model="form.flag_type"
                label="Flag Type"
                placeholder="Select Type"
                :options="flagTypeOptions"
                :error="form.errors.flag_type"
            />
            <SelectInput
                v-if="batchSubjectOptions.length && isTeacher()"
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
    </Modal>
    <Modal v-model:view="showDetailModal">
        <div
            class="flex w-full flex-col items-center space-y-5 rounded-lg bg-white p-5"
        >
            <div class="flex w-full items-center justify-center space-x-2 px-3">
                <div
                    class="w-fit bg-red-600 px-2 py-0.5 text-center font-semibold uppercase text-white"
                >
                    {{ selectedFlagItem.type }} Flag
                </div>

                <div v-if="selectedFlagItem?.batch_subject">
                    ( {{ selectedFlagItem.batch_subject?.subject?.full_name }} )
                </div>
            </div>

            <div class="p-3 text-sm text-gray-500">
                {{ selectedFlagItem.description }}
            </div>
            <div
                class="flex w-full items-end justify-between px-4 text-center text-sm font-semibold"
            >
                <div class="text-xs font-light italic">
                    Expiry Date:
                    {{
                        moment(selectedFlagItem.expires_at).format(
                            "ddd MMMM DD YYYY"
                        )
                    }}
                </div>
                <div>
                    <div>{{ selectedFlagItem.flagged_by.name }}</div>
                    <div class="pt-1 text-xs font-light">
                        {{
                            moment(selectedFlagItem.created_at).format(
                                "ddd MMMM DD YYYY"
                            )
                        }}
                    </div>
                </div>
            </div>
        </div>
    </Modal>
    <Modal v-model:view="showInfoModal">
        <div class="flex w-full flex-col space-y-5 rounded-lg bg-white p-5">
            <div class="text-center text-2xl font-medium uppercase">
                Understanding Student Flags
            </div>

            <div
                class="flex flex-col space-y-4 px-4 py-3 text-sm text-gray-700"
            >
                <p>
                    The flagging feature for teachers serves as an intuitive
                    mechanism for highlighting particular students for a variety
                    of reasons. By "flagging" a student, teachers effectively
                    mark that student's profile with a note or symbol,
                    indicating that they require special attention or have
                    achieved exceptional performance.
                </p>
                <p>
                    Flags can represent a range of scenarios. For instance, a
                    flag can signal academic struggles, requiring additional
                    assistance in certain subjects, or it may indicate a
                    student's exceptional performance that calls for more
                    challenging work. Flags can also highlight attendance
                    issues, behavioral concerns, or other specific
                    circumstances.
                </p>
                <p>
                    Once a teacher applies a flag to a student, it stays visible
                    on their profile, making it straightforward for the educator
                    to track the student's status and progress. This system aids
                    teachers in adapting their teaching strategies according to
                    each student's distinct requirements and performance levels.
                </p>
                <p>
                    In essence, the flagging feature cultivates an environment
                    that supports individualized learning and progress, ensuring
                    that every student's needs are recognized and addressed. By
                    employing flags, teachers can help students overcome their
                    challenges, boost their achievements, and ultimately, enable
                    them to realize their full potential.
                </p>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { InformationCircleIcon } from "@heroicons/vue/24/outline";
import { computed, ref } from "vue";
import Modal from "@/Components/Modal.vue";
import TextArea from "@/Components/TextArea.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import DatePicker from "@/Components/DatePicker.vue";
import moment from "moment";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Pagination from "@/Components/Pagination.vue";
import { isTeacher } from "@/utils";
import EmptyView from "@/Views/EmptyView.vue";

const props = defineProps({
    student: {
        type: String,
        default: null,
    },
    batchSubjectOptions: {
        type: Object,
        default: null,
    },
});
const showInfoModal = ref(false);
const showAddModal = ref(false);
const showDetailModal = ref(false);
const selectedFlagItem = ref(null);

const flags = computed(() => usePage().props.flags);

const form = useForm({
    batch_subject_id: props.batchSubjectOptions[0].value ?? "",
    flag_type: "",
    description: null,
    flaggable_id: props.student.id,
    expires_at: new Date(moment().add(1, "weeks")),
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
            showAddModal.value = false;
            form.reset();
        },
    });
};
</script>

<style scoped></style>
