<template>
    <Modal v-model:view="showModal">
        <div
            class="scrollbar-hide flex max-h-[800px] flex-col space-y-3 overflow-y-scroll rounded-lg bg-white p-4 text-center"
        >
            <div>
                <Title title="Add Staff Absentee" />
            </div>

            <div class="flex w-full flex-col justify-center space-y-4">
                <div
                    v-if="selectedStaff && selectedStaff.value"
                    class="flex w-full flex-col items-center justify-center gap-5 py-3"
                >
                    <div
                        class="flex w-full flex-col items-center justify-center py-3"
                    >
                        <div class="flex w-full flex-col gap-5 py-3">
                            <h2 class="flex justify-center">
                                Your are about to add
                                <span class="px-2 font-bold">
                                    {{ selectedStaff.type }}
                                    {{ selectedStaff.label }}
                                </span>
                                as an absentee:
                            </h2>

                            <div
                                class="flex flex-col items-center justify-center"
                            >
                                <TextInput
                                    v-model="form.reason"
                                    label="Reason"
                                    placeholder="Enter reason"
                                    class="w-full lg:max-w-lg"
                                    :error="form.errors.reason"
                                />
                            </div>

                            <div class="flex items-center justify-center">
                                <PrimaryButton
                                    title="Add"
                                    @click="addAbsentee"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <span class="text-md font-medium text-gray-500"
                    >Search for a staff member to add as an absentee
                </span>
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
            </div>
        </div>
    </Modal>
</template>
<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Title from "@/Views/Teacher/Views/Title.vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";
import { computed, ref, watch } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { debounce } from "lodash";

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
};
</script>
<style scoped></style>
