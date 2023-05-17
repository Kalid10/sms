<template>

    <div
        class="container mx-auto flex h-full max-h-full max-w-7xl flex-col gap-4 px-2 pt-6 md:px-6 md:pt-6"
    >
        <form
        class="flex min-h-max w-full flex-col items-center justify-center py-4"
    >
        <Heading class="mb-1 text-center !font-normal text-black" size="md"
            >Welcome to the
        </Heading>

        <div class="relative flex items-center">
            <input
                ref="schoolYearName"
                v-model="formData.name"
                class="min-w-[205px] max-w-[212px] appearance-none border-none bg-transparent p-0 text-center text-4xl font-bold focus:outline-0 focus:ring-0"
                type="text"
            />

            <div
                class="absolute top-0 right-0 -mr-4 flex translate-x-full flex-col gap-2"
            >
                <!--                <PencilIcon-->
                <!--                    class="h-4 w-4 stroke-gray-500 stroke-2"-->
                <!--                    @click="focusOnName"/>-->
                <!--                <ArrowPathIcon class="h-4 w-4 stroke-gray-500 stroke-2" @click="resetName"/>-->
            </div>
        </div>

        <Heading class="mt-1 text-center !font-normal">School Year</Heading>

        <div class="mt-8 flex">
            <h3 class="max-w-md text-center text-sm text-gray-500">
                <span
                    ><InformationCircleIcon
                        class="mb-1 inline h-4 w-4 stroke-2"
                /></span>
                Please enter the <span class="text-black">start date</span> of
                the new school year and the
                <span class="whitespace-nowrap text-black"
                    >number of semesters</span
                >
                it includes. You can also change the school year name by
                clicking the
                <span
                    ><PencilIcon class="inline h-3 w-3 stroke-black stroke-2"
                /></span>
                icon on the right.
                <span class="inline">
                    You can always change this in the school year settings
                    later.
                </span>
            </h3>
        </div>

        <div class="mt-8 flex flex-col gap-3">
            <DatePicker
                v-model="formData.start_date"
                :label="!!formData.start_date ? 'Choose a Start Date' : ''"
                :label-location="!!formData.start_date ? 'inside' : ''"
                :minimum="new Date()"
                class="w-72"
                placeholder="Choose a Start Date"
            />
            <SelectInput
                v-model="formData.number_of_semesters"
                :label="
                    !!formData.number_of_semesters ? 'Number of Semesters' : ''
                "
                :label-location="!!formData.number_of_semesters ? 'inside' : ''"
                :options="noOfSemesters"
                class="w-72"
                placeholder="Number of Semesters"
            />
            <PrimaryButton
                :disabled="!formComplete"
                class="w-72"
                @click="handleSubmit"
                >Create and Proceed
            </PrimaryButton>
        </div>
    </form>
    </div>

</template>
<script setup>
import { computed, ref } from "vue";
import { InformationCircleIcon, PencilIcon } from "@heroicons/vue/24/outline";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import SelectInput from "@/Components/SelectInput.vue";
import DatePicker from "@/Components/DatePicker.vue";
import Heading from "@/Components/Heading.vue";

const emits = defineEmits(["success"]);

const noOfSemesters = [
    { value: 1, label: 1 },
    { value: 2, label: 2 },
    { value: 3, label: 3 },
    { value: 4, label: 4 },
    { value: 5, label: 5 },
];

const schoolYearName = ref(null);
const changeName = ref(false);

function focusOnName() {
    schoolYearName.value.select();
}

function resetName() {
    formData.name = "2022/2023";
}

const formData = useForm({
    name: "2022/2023",
    number_of_semesters: 3,
    start_date: null,
});

const formComplete = computed(() => {
    return (
        !!formData.name &&
        !!formData.number_of_semesters &&
        !!formData.start_date
    );
});

function handleSubmit() {
    formData.post("/school-year/create", {
        onSuccess: () => {
            console.log("Success");
            // router.get("/getting-started/register-batches");
            emits("success");
        },
        onError: (error) => {
            console.log("Error");
            console.log(error);
        },
    });
}
</script>
