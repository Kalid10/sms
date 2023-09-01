<template>
    <div
        class="scrollbar-hide flex max-h-[800px] flex-col space-y-3 overflow-y-scroll rounded-lg bg-white p-4 text-center"
    >
        <div class="flex w-full flex-col justify-center space-y-6">
            <div class="flex w-full justify-evenly">
                <ArrowLeftCircleIcon
                    v-if="selectedStaff"
                    class="w-6 cursor-pointer text-brand-text-350 hover:scale-110"
                    @click="
                        showSearchResult = true;
                        selectedStaff = null;
                        titleText = $t('absenteeAddModal.addAbsentee');
                    "
                />
                <Title :title="titleText" class="w-11/12" />
            </div>
            <div class="flex w-full flex-col items-center space-y-4 py-2">
                <TextInput
                    v-if="!selectedStaff"
                    v-model="searchKey"
                    class="w-11/12"
                    class-style="rounded-2xl focus:ring-1 focus:border-none focus:ring-zinc-800 focus:outline-none placeholder:text-xs"
                    :placeholder="$t('absenteeAddModal.searchStaffMember')"
                />

                <Loading v-if="showLoading" color="secondary" />
                <div
                    v-if="showSearchResult"
                    class="flex w-full flex-col items-center"
                >
                    <div
                        v-for="(staff, index) in staffUsers"
                        v-show="searchKey.length > 0"
                        :key="staff"
                        class="w-11/12 cursor-pointer py-2 hover:rounded-lg hover:bg-brand-400 hover:text-white"
                        :class="
                            index % 2 === 0
                                ? 'bg-brand-100 px-10 '
                                : 'bg-white px-10 '
                        "
                        @click="selectStaff(staff)"
                    >
                        <p class="px-3 py-2 text-sm">{{ staff.label }}</p>
                    </div>
                </div>

                <div
                    v-if="selectedStaff && selectedStaff.value"
                    class="flex w-full flex-col items-center justify-center gap-5 py-3"
                >
                    <div
                        class="flex w-full flex-col items-center justify-center"
                    >
                        <div class="flex w-full flex-col space-y-4">
                            <TextArea
                                v-model="form.reason"
                                :label="$t('common.reason')"
                                :placeholder="
                                    $t('absenteeAddModal.enterReason')
                                "
                                class="max-h-fit w-full text-start"
                                :subtext="
                                    $t('absenteeAddModal.youAreAbout', {
                                        selectedStaffLabel: selectedStaff.label,
                                    })
                                "
                                :error="form.errors.reason"
                            />
                            <Toggle
                                v-model="form.is_leave"
                                label="Is this a valid leave?"
                            />
                        </div>

                        <div class="mt-5 flex items-center justify-center">
                            <PrimaryButton
                                :title="$t('common.submit')"
                                class="!rounded-2xl bg-brand-500"
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
import TextArea from "@/Components/TextArea.vue";
import { ArrowLeftCircleIcon } from "@heroicons/vue/20/solid";
import Loading from "@/Components/Loading.vue";
import Toggle from "@/Components/Toggle.vue";

const emit = defineEmits(["add"]);

const showModal = ref(true);
const showLoading = ref(false);

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
const showSearchResult = ref(false);
const titleText = ref("Add An Absentee");
const selectStaff = (item) => {
    selectedStaff.value = item;
    showSearchResult.value = false;
    titleText.value = item.label;
};

const searchKey = ref("");

watch(searchKey, () => {
    search();
    selectedStaff.value = null;
});

const search = debounce(() => {
    showLoading.value = true;
    router.get(
        "/admin/absentees",
        {
            search: searchKey.value,
        },
        {
            only: ["staff"],
            preserveState: true,
            replace: true,
            onFinish: () => {
                showSearchResult.value = true;
                showLoading.value = false;
            },
        }
    );
}, 300);

const form = useForm({
    batch_session_id: null,
    user_id: "",
    reason: "",
    type: "",
    is_leave: false,
});

const addAbsentee = () => {
    form.user_id = selectedStaff.value.value;
    form.type = selectedStaff.value.type;

    if (form.type === "Teacher") {
        form.batch_session_id = selectedStaff.value.batch_session_id;
    }
    form.post("/admin/absentee/staff/add", {
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
