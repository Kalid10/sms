<template>
    <div class="flex w-full justify-between space-x-5 rounded-lg py-4">
        <div
            class="flex w-3/12 flex-col justify-center space-y-4 rounded-lg py-5 text-center shadow-sm"
            :class="{
                'bg-positive-100 text-white': grade?.grade_scale?.label === 'A',
                'bg-yellow-400 text-white': grade?.grade_scale?.label === 'B',
                'bg-orange-500 text-white': grade?.grade_scale?.label === 'C',
                'bg-orange-400 text-black': grade?.grade_scale?.label === 'D',
                'bg-red-600 text-white': grade?.grade_scale?.label === 'F',
                'bg-zinc-100': !grade?.score,
            }"
        >
            <div v-if="grade?.grade_scale">
                <span class="text-4xl font-semibold">
                    {{ grade?.score?.toFixed(1) }}
                    <span class="text-xl font-normal">{{
                        grade?.grade_scale?.label
                    }}</span>
                </span>
            </div>
            <div v-else>
                <span class="text-4xl font-semibold"> - </span>
            </div>
            <span class="text-xs font-light">
                {{ batchSubject?.subject.short_name }} GRADE
            </span>
        </div>

        <div
            :class="{
                'bg-positive-100 text-white': attendancePercentage > 99,
                'bg-yellow-300':
                    attendancePercentage > 95 && attendancePercentage < 99,
                'bg-orange-300':
                    attendancePercentage > 85 && attendancePercentage < 95,
                'bg-orange-500 text-white':
                    attendancePercentage < 85 && attendancePercentage > 75,
                'bg-red-500 text-white':
                    attendancePercentage < 75 && attendancePercentage > 65,
                'bg-gradient-to-b from-orange-500 to-red-500 text-white':
                    attendancePercentage < 65,
                'bg-zinc-100': !attendancePercentage,
            }"
            class="flex w-3/12 flex-col justify-center space-y-4 rounded-lg py-5 text-center text-4xl font-semibold shadow-sm"
        >
            <div>{{ attendancePercentage }}%</div>
            <span class="text-xs font-light">
                {{ batchSubject?.subject?.short_name }} ATTENDANCE
            </span>
        </div>

        <div
            :class="{
                'bg-positive-100 text-white': student.conduct === 'A',
                'bg-yellow-400': student.conduct === 'B',
                'bg-orange-500 text-white': student.conduct === 'C',
                'bg-red-500 text-white': student.conduct === 'D',
                'bg-red-600 text-white': student.conduct === 'F',
                'bg-zinc-100': !student.conduct,
            }"
            class="flex w-3/12 flex-col justify-center space-y-4 rounded-lg py-5 text-center text-4xl font-semibold shadow-sm"
        >
            <div class="flex items-center justify-center space-x-2 pl-3">
                <div class="">{{ student.conduct ?? "NC" }}</div>
                <div @click="showConductModal = true">
                    <PencilSquareIcon
                        v-if="
                            auth.user?.teacher?.id ===
                                batchSubject?.teacher_id &&
                            auth.user.type === 'teacher'
                        "
                        class="w-5 cursor-pointer hover:scale-125 hover:text-black"
                    />
                </div>
            </div>
            <span class="text-xs font-light">
                {{ batchSubject?.subject?.short_name }} CONDUCT
            </span>
        </div>
        <div
            :class="{
                ' bg-gradient-to-tl from-purple-500 to-violet-500 text-white':
                    rank,
                'bg-zinc-100': !rank,
            }"
            class="flex w-3/12 flex-col justify-center space-y-4 rounded-lg py-5 text-center text-4xl font-semibold shadow-sm"
        >
            <div>{{ rank ? numberWithOrdinal(rank) : "-" }}</div>
            <span class="text-xs font-light">
                {{ batchSubject?.subject.short_name }} Rank From
                {{ totalStudents }} Students
            </span>
        </div>
        <Modal v-model:view="showConductModal">
            <FormElement title="Update Conduct" @submit="submit">
                <SelectInput
                    v-model="conductForm.conduct"
                    placeholder="Select Conduct"
                    :options="[
                        { label: 'A', value: 'A' },
                        { label: 'B', value: 'B' },
                        { label: 'C', value: 'C' },
                        { label: 'D', value: 'D' },
                        { label: 'F', value: 'F' },
                    ]"
                />
            </FormElement>
        </Modal>
    </div>
</template>
<script setup>
import { computed, ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { PencilSquareIcon } from "@heroicons/vue/24/outline";
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { numberWithOrdinal } from "../../../../utils";

const attendancePercentage = computed(
    () => usePage().props.attendance_percentage
);

const grade = computed(() => usePage().props.grade);
const batchSubject = computed(() => usePage().props.batch_subject);
const student = computed(() => usePage().props.student);
const auth = computed(() => usePage().props.auth);
const rank = computed(() => usePage().props.grade?.rank);
const totalStudents = computed(() => usePage().props.total_batch_students);
const showConductModal = ref(false);
const conductForm = useForm({
    conduct: student.value.conduct ?? "",
    batch_subject_id: batchSubject.value?.id,
});

const submit = () => {
    conductForm.post(
        "/teacher/students/" + student.value.id + "/conduct/update ",
        {
            preserveScroll: true,
            onSuccess: () => {
                showConductModal.value = false;
            },
        }
    );
};
</script>

<style scoped></style>
