<template>
    <div class="flex w-full flex-col justify-between space-y-4">
        <div
            class="flex flex-col justify-center space-y-4 rounded-lg py-5 text-center shadow-sm"
            :class="{
                'bg-positive-100 text-white': grade?.grade_scale.label === 'A',
                'bg-yellow-400': grade?.grade_scale.label === 'B',
                'bg-yellow-300': grade?.grade_scale.label === 'C',
                'bg-negative-50 text-white': grade?.grade_scale.label === 'D',
                'bg-negative-100 text-white': grade?.grade_scale.label === 'F',
                'bg-white': !grade,
            }"
        >
            <div v-if="grade">
                <span class="text-4xl font-semibold">
                    {{ grade?.score }}
                    <span class="text-xl font-normal">{{
                        grade?.grade_scale.label
                    }}</span>
                </span>
            </div>
            <div v-else>
                <span class="text-4xl font-semibold"> - </span>
            </div>
            <span class="text-xs font-light">
                {{ batchSubject.subject.short_name }} GRADE
            </span>
        </div>

        <div
            :class="{
                'bg-positive-100 text-white': attendancePercentage > 99,
                'bg-yellow-400':
                    attendancePercentage > 95 && attendancePercentage < 99,
                'bg-yellow-300':
                    attendancePercentage > 85 && attendancePercentage < 95,
                'bg-negative-50 text-white':
                    attendancePercentage < 85 && attendancePercentage > 75,
                'bg-negative-100 text-white':
                    attendancePercentage > 75 && attendancePercentage < 65,
                'bg-white': !attendancePercentage,
            }"
            class="flex flex-col justify-center space-y-4 rounded-lg py-5 text-center text-4xl font-bold shadow-sm"
        >
            <div>{{ attendancePercentage }}%</div>
            <span class="text-xs font-light">
                {{ batchSubject.subject.short_name }} ATTENDANCE
            </span>
        </div>

        <div
            :class="{
                'bg-positive-100 text-white': student.conduct === 'A',
                'bg-yellow-400': student.conduct === 'B',
                'bg-amber-300': student.conduct === 'C',
                'bg-red-500 text-white': student.conduct === 'D',
                'bg-negative-100 text-white': student.conduct === 'F',
                'bg-white text-black': !student.conduct,
            }"
            class="flex flex-col justify-center space-y-4 rounded-lg py-5 text-center text-4xl font-bold shadow-sm"
        >
            <div class="flex items-center justify-center space-x-2 pl-3">
                <div class="">{{ student.conduct ?? "NC" }}</div>
                <div @click="showConductModal = true">
                    <PencilSquareIcon
                        class="w-5 cursor-pointer hover:scale-125 hover:text-black"
                    />
                </div>
            </div>
            <span class="text-xs font-light">
                {{ batchSubject.subject.short_name }} CONDUCT
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

const attendancePercentage = computed(
    () => usePage().props.attendance_percentage
);

const grade = computed(() => usePage().props.batch_subject_grade);
const batchSubject = computed(() => usePage().props.batch_subject);
const student = computed(() => usePage().props.student);

const showConductModal = ref(false);
const conductForm = useForm({
    conduct: student.value.conduct ?? "",
    batch_subject_id: batchSubject.value.id,
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
