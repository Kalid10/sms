<template>
    <div
        class="container mx-auto flex h-full max-h-full max-w-7xl flex-col gap-4 px-2 pt-6 md:px-6 md:pt-6"
    >
        <form
            class="flex min-h-max w-full flex-col items-center justify-center py-4"
        >
            <Heading class="mb-1 text-center !font-normal text-black" size="md">
                {{ $t("registerSchoolYear.welcome") }}
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
                    <PencilIcon
                        class="h-3 w-3 stroke-gray-500 stroke-2"
                        @click="focusOnName"
                    />
                    <ArrowPathIcon
                        class="h-3 w-3 stroke-gray-500 stroke-2"
                        @click="resetName"
                    />
                </div>
            </div>

            <Heading class="mt-1 text-center !font-normal">
                {{ $t("registerSchoolYear.schoolYear") }}
            </Heading>

            <div class="mt-8 flex">
                <h3 class="max-w-md text-center text-sm text-brand-text-300">
                    <span v-html="$t('registerSchoolYear.welcomeText')" />
                </h3>
            </div>

            <div class="mt-8 flex flex-col gap-3">
                <DatePicker
                    v-model="formData.start_date"
                    :label="!!formData.start_date ? 'Choose a Start Date' : ''"
                    :label-location="!!formData.start_date ? 'inside' : ''"
                    :minimum="new Date()"
                    class="w-72"
                    :placeholder="$t('registerSchoolYear.chooseStartDate')"
                />
                <SelectInput
                    v-model="formData.number_of_semesters"
                    :label="
                        !!formData.number_of_semesters
                            ? $t('registerSchoolYear.numberOfSemesters')
                            : ''
                    "
                    :label-location="
                        !!formData.number_of_semesters ? 'inside' : ''
                    "
                    :options="noOfSemesters"
                    class="w-72"
                    :placeholder="$t('registerSchoolYear.numberOfSemesters')"
                />

                <div v-if="hideQuarter" class="flex w-72 flex-col">
                    <SelectInput
                        v-model="formData.number_of_quarters"
                        :options="noOfQuarters"
                        :label="
                            !!formData.number_of_quarters
                                ? $t('registerSchoolYear.numberOfQuarters')
                                : ''
                        "
                        :label-location="
                            !!formData.number_of_quarters ? 'inside' : ''
                        "
                        :placeholder="$t('registerSchoolYear.numberOfQuarters')"
                        class="w-72"
                    />

                    <div class="p-2 text-xs text-brand-text-250">
                        <span>
                            {{ $t("registerSchoolYear.ifThereIs") }}
                        </span>
                        <span
                            class="ml-1 cursor-pointer text-red-500 underline-offset-2 hover:font-medium hover:underline"
                            @click="hideQuarters"
                        >
                            {{ $t("registerSchoolYear.removeQuarter") }}
                        </span>
                    </div>

                    <div
                        class="mt-5 w-72 rounded-lg border border-dashed border-gray-300 p-2 text-center text-brand-text-300"
                    >
                        <span
                            v-html="
                                $t('registerSchoolYear.summaryText', {
                                    academicYear: formData.name,
                                    semesters: formData.number_of_semesters,
                                    quartersPerSemester:
                                        formData.number_of_quarters,
                                    totalQuarters,
                                })
                            "
                        />
                    </div>
                </div>

                <PrimaryButton
                    v-if="formComplete"
                    class="w-72"
                    @click="handleSubmit"
                >
                    {{ $t("registerSchoolYear.createAndProceed") }}
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
<script setup>
import { computed, createApp, getCurrentInstance, onMounted, ref } from "vue";
import { ArrowPathIcon, PencilIcon } from "@heroicons/vue/24/outline";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import SelectInput from "@/Components/SelectInput.vue";
import DatePicker from "@/Components/DatePicker.vue";
import Heading from "@/Components/Heading.vue";

// for pencil icon
function renderComponent({ el, component, props, appContext }) {
    let app = createApp(component, props);
    Object.assign(app._context, appContext); // must use Object.assign on _context
    app.mount(el);

    return () => {
        // destroy app/component
        app?.unmount();
        app = undefined;
    };
}

const appContext = getCurrentInstance();

async function insertPencilIcon() {
    renderComponent({
        el: document.getElementById("pencilIcon"),
        component: PencilIcon,
        props: {
            class: "h-3 w-3 inline stroke-black stroke-2",
        },
        appContext,
    });
}

onMounted(async () => {
    await insertPencilIcon();
});

const emits = defineEmits(["success"]);

const hideQuarter = ref(true);

function hideQuarters() {
    hideQuarter.value = false;
    formData.number_of_quarters = null;
}

const noOfSemesters = [
    { value: 1, label: 1 },
    { value: 2, label: 2 },
    { value: 3, label: 3 },
    { value: 4, label: 4 },
    { value: 5, label: 5 },
];

const noOfQuarters = [
    { value: 2, label: 2 },
    { value: 3, label: 3 },
    { value: 4, label: 4 },
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
    number_of_quarters: 2,
});

const formComplete = computed(() => {
    return (
        !!formData.name &&
        !!formData.number_of_semesters &&
        !!formData.start_date
    );
});

const totalQuarters = computed(() => {
    return formData.number_of_semesters * formData.number_of_quarters;
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
