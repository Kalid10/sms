<template>
    <div
        class="flex w-full flex-col items-center justify-between space-y-10 p-4"
    >
        <Title class="w-full" title="Class Schedule Generator" />
        <div
            class="flex w-full items-center justify-evenly rounded-lg p-5 font-medium"
        >
            <div
                class="flex h-full w-5/12 flex-col items-center justify-center space-y-8 text-center"
            >
                <div class="text-4xl font-semibold">
                    Welcome to Your Class Schedule Management Portal
                </div>
                <p class="">
                    Begin by selecting a grade from the list to your right. This
                    will unlock access to all corresponding sections. Once a
                    grade is selected, you can assign teachers and set the
                    weekly frequency for each subject. Before proceeding, please
                    specify the daily and weekly class limits for teachers using
                    the fields below.
                </p>

                <SecondaryButton
                    title="Save Configuration"
                    class="w-1/3 !rounded-2xl bg-brand-400 text-white"
                    @click="saveConfig"
                />
            </div>
            <div
                class="flex w-5/12 flex-col items-center space-y-8 rounded-lg border-2 border-black p-10"
            >
                <p class="text-center">
                    For optimal scheduling, we kindly ask you to establish the
                    daily and weekly teaching quotas for each instructor in the
                    fields below.
                </p>

                <TextInput
                    v-model="configForm.daily_period_limit"
                    label="Daily Class Quota per Teacher"
                    class="w-11/12"
                    placeholder="Daily Class Quota per Instructor"
                    type="number"
                    :error="usePage().props.errors.daily_period_limit"
                />
                <TextInput
                    v-model="configForm.weekly_period_limit"
                    label="Weekly Class Quota per Teacher"
                    class="w-11/12"
                    placeholder="Weekly Class Quota per Instructor"
                    type="number"
                    :error="usePage().props.errors.weekly_period_limit"
                />

                <SecondaryButton
                    title="Save Configuration"
                    class="w-1/3 !rounded-2xl bg-brand-400 text-white"
                    @click="saveConfig"
                />
            </div>
        </div>
    </div>

    <Loading v-if="isLoading" is-full-screen />
</template>

<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { ref } from "vue";
import Loading from "@/Components/Loading.vue";

const emit = defineEmits(["success"]);

const isLoading = ref(false);

const configForm = useForm({
    daily_period_limit: null,
    weekly_period_limit: null,
});

const saveConfig = () => {
    isLoading.value = true;
    configForm.post("/admin/batch-schedules/configure", {
        preserveState: true,
        onSuccess: () => {
            emit("success");
        },
        onFinish: () => {
            isLoading.value = false;
        },
    });
};
</script>
<style scoped></style>
