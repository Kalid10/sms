<template>
    <div
        class="scrollbar-hide flex max-h-[800px] flex-col space-y-3 overflow-y-scroll rounded-lg bg-white p-4 text-center"
    >
        <div class="flex w-full flex-col justify-center space-y-6">
            <Title title="Add an absentee" />
            <div class="flex flex-col">
                <input
                    v-model="searchKey"
                    class="mb-2 rounded-md border border-gray-300 px-3 py-2"
                    type="text"
                    placeholder="Search for a staff member by name"
                />
                <div
                    v-for="(staff, index) in staffUsers"
                    v-show="searchKey.length > 0"
                    :key="staff"
                    class="cursor-pointer py-2"
                    :class="
                        index % 2 === 0
                            ? 'bg-gray-100 px-10 hover:bg-gray-300'
                            : 'bg-white px-10 hover:bg-gray-300'
                    "
                    @click="selectStaff(staff)"
                >
                    <p class="px-3 py-2">{{ staff.label }}</p>
                </div>
            </div>

            <div
                v-if="selectedStaff && selectedStaff.value"
                class="flex w-full flex-col items-center justify-center gap-5 py-3"
            >
                <div
                    class="flex w-full flex-col items-center justify-center py-3"
                >
                    <div class="flex w-full flex-col">
                        <div class="flex items-center">
                            <div class="mb-4 flex w-4/12 lg:max-w-lg">
                                <p class="text-md font-medium">
                                    Reason for absence:
                                </p>
                            </div>
                            <div class="flex w-full">
                                <TextInput
                                    v-model="form.reason"
                                    label=""
                                    placeholder="Enter reason"
                                    class="max-h-fit w-full lg:max-w-lg"
                                    :subtext="
                                        'You are about to add ' +
                                        selectedStaff.label +
                                        ' as an absentee'
                                    "
                                    :error="form.errors.reason"
                                />
                            </div>
                        </div>

                        <div class="mt-5 flex items-center justify-center">
                            <PrimaryButton
                                title="Add Absentee"
                                @click="addAbsentee"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { computed, ref, watch } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { debounce } from "lodash";
import Title from "@/Views/Teacher/Views/Title.vue";

const emit = defineEmits(["add"]);

const showModal = ref(true);

const staff = computed(() => usePage().props.staff);

const staffUsers = computed(() => {
    return staff.value?.map((staff) => {
        return {
            label: staff.name,
            value: staff.id,
            type: staff.type,
            batch_session_id: staff.batch_session_id,
        };
    });
});

const selectedStaff = ref(null);

const selectStaff = (item) => {
    selectedStaff.value = item;
};

const searchKey = ref("");

watch(searchKey, () => {
    search();
    selectedStaff.value = null;
});

const search = debounce(() => {
    router.get(
        "/admin/absentees",
        {
            search: searchKey.value,
        },
        {
            only: ["staff"],
            preserveState: true,
            replace: true,
        }
    );
}, 300);

const form = useForm({
    batch_session_id: null,
    user_id: "",
    reason: "",
    type: "",
});

const addAbsentee = () => {
    form.user_id = selectedStaff.value.value;
    form.type = selectedStaff.value.type;

    if (form.type === "Teacher") {
        form.batch_session_id = selectedStaff.value.batch_session_id;
    }
    form.post("/absentee/staff/add", {
        onSuccess: () => {
            showModal.value = false;
            form.reset();
            selectedStaff.value = null;
        },
    });
    emit("add", form);
};
</script>
<style scoped></style>
