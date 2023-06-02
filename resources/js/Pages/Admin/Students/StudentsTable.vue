<template>

    <TableElement
        :columns="config"
        :selectable="false"
        :header="false"
        row-actionable
        title="Students"
        subtitle="List of all students in the school system"
        :data="formattedStudentsData">

        <template #filter>
            <div class="flex w-full gap-4">
                <TextInput v-model="searchKey" class="w-full" placeholder="Search for a student by name"/>
            </div>
        </template>

        <template #row-actions="{ row }">
            <div class="flex flex-col items-center gap-1">
                <button @click="transferStudent(row)">
                    <ArrowsRightLeftIcon
                        class="h-4 w-4 stroke-2 transition-all duration-150 hover:scale-125 hover:stroke-blue-700"/>
                </button>
            </div>
        </template>

        <template #name-column="{ data }">
            <div class="flex items-start gap-2">
                <span class="font-medium">{{ data }}</span>
            </div>
        </template>

        <template #email-column="{ data }">
            <div class="flex items-center gap-1">
                <span class="text-xs">
                {{ data }}
            </span>
            </div>
        </template>

        <template #grade-column="{ data }">
            <div class="flex items-center gap-1">
                    <span class="text-xs font-semibold">
                {{ data }}
            </span>
            </div>
        </template>

        <template #footer>
            <div class="flex w-full justify-end gap-3">
                <TertiaryButton
                    class="w-full md:w-fit"
                    title="Previous"
                    @click="previousPage"
                />
                <TertiaryButton
                    class="w-full md:w-fit"
                    title="Next"
                    @click="nextPage"
                />
            </div>
        </template>

        <template #empty-data>
            <div class="flex flex-col items-center justify-center">
                <ExclamationTriangleIcon class="mb-2 h-6 w-6 text-negative-50"/>
                <p class="text-sm font-semibold">
                    No data found
                </p>
                <div v-if="searchKey.length">
                    <p v-if="searchKey === null" class="text-sm text-gray-500">
                        No student has been enrolled
                    </p>
                    <p v-else class="text-center text-sm text-gray-500">
                        Your search query "<span class="font-medium text-black">{{ searchKey }}</span>" did not
                        match
                        <span class="block">any student's name</span>
                    </p>
                </div>
            </div>
        </template>
    </TableElement>

    <Modal v-model:view="isModalOpen">
        <FormElement
            :title="'Transfer Student ' + selectedStudentForTransfer.name + ' from ' + ' Grade ' + selectedStudentForTransfer.grade"
            @submit="submit"
        >
            <p class="text-xs text-gray-500">
                Transferring a student will remove them from their current section and add them to the selected section.
            </p>

            <RadioGroupPanel
                v-model="transferForm.destination_batch_id"
                :options="transferOptions"
                label="Select Destination Section"
                name="sections"
            />

        </FormElement>
    </Modal>

</template>
<script setup>
import TableElement from "@/Components/TableElement.vue";
import {computed, ref, watch} from "vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import {debounce} from "lodash";
import TextInput from "@/Components/TextInput.vue";
import {ArrowsRightLeftIcon, ExclamationTriangleIcon} from "@heroicons/vue/24/outline";
import Modal from "@/Components/Modal.vue";
import FormElement from "@/Components/FormElement.vue";
import RadioGroupPanel from "@/Components/RadioGroupPanel.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";

const isModalOpen = ref(false);

const students = computed(() => {
    return usePage().props.students.data;
});

const formattedStudentsData = computed(() => {
    return students.value.map(student => {
        return {
            id: student.id,
            name: student.user.name,
            email: student.user.email,
            gender: student.user.gender,
            batch_id: student.batches[0].batch_id,
            level_id: student.batches[0].level.id,
            grade: student.batches.map(ba => `${ba.level.name}-${ba.batch.section}`).join(', '),
        };
    });
});

const batches = computed(() => {
    return usePage().props.batches;
});

const selectedStudentForTransfer = ref(null);

const transferOptions = computed(() => {

    return batches.value.filter((batch) => {
        return (batch.level_id === selectedStudentForTransfer.value.level_id) &&
            (batch.id !== selectedStudentForTransfer.value.batch_id)
    }).map((batch, b) => {
        return {
            id: b,
            value: batch.id,
            label: batch.level.name + '-' + batch.section,
            description: `Homeroom Teacher: ${batch.homeroom_teacher.teacher.user.name}`
        }
    })

})

function transferStudent(row) {
    selectedStudentForTransfer.value = row;
    isModalOpen.value = !isModalOpen.value;
    transferForm.student_id = selectedStudentForTransfer.value.id;
}

const transferForm = useForm({
    student_id: null,
    destination_batch_id: null,
});

function submit() {
    transferForm.post('/batches/students/transfer', {
        onSuccess: () => {
            isModalOpen.value = false;
        }
    });
}

const searchKey = ref('');
const perPage = ref(15);

const search = debounce(() => {
    router.get(
        "/students/",
        {
            search: searchKey.value,
            perPage: perPage.value,
        },
        {
            only: ["students"],
            preserveState: true, replace: true
        }
    );
}, 300);

watch([searchKey, perPage], () => {
    search();
})

const currentPage = ref(1);

function nextPage() {
    currentPage.value++;
    router.get(
        "/students",
        {
            page: currentPage.value,
            perPage: perPage.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
}

function previousPage() {
    currentPage.value--;
    router.get(
        "/students",
        {
            page: currentPage.value,
            perPage: perPage.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
}


const config = [
    {
        name: 'Name',
        key: 'name',
        link: '/students/{id}',
        align: 'left',
        type: 'custom',
    },
    {
        name: 'Email',
        key: 'email',
        type: 'custom',
    },
    {
        name: 'Gender',
        key: 'gender',
        type: 'enum',
        options: ['female', 'male'],
    },
    {
        name: 'Grade',
        key: 'grade',
        type: 'custom',
        align: 'right',

    },
]


</script>
