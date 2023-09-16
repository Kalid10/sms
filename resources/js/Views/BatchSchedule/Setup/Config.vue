<template>
    <div class="flex h-screen w-full flex-col items-center space-y-10 p-4">
        <Title
            v-if="!gettingStarted"
            class="w-full"
            title="Class Schedule Generator"
        />
        <div
            class="flex h-full w-full justify-center rounded-lg px-1 font-medium"
        >
            <div
                v-if="gettingStarted"
                class="flex h-4/6 w-10/12 flex-col items-center justify-center space-y-14 text-center"
            >
                <div
                    class="flex flex-col items-center justify-center space-y-5 text-5xl font-bold"
                >
                    <div>
                        <div>We've Got You Covered</div>
                        <div>No More Manual Scheduling</div>
                    </div>
                    <p class="w-8/12 text-lg font-medium text-gray-700">
                        Just a couple more steps: Set your daily teacher quotas
                        and assign educators to subjects. Once that's done,
                        you're on the fast track to a seamlessly organized
                        semester. Can't wait? Neither can we.
                    </p>
                </div>
                <div class="flex w-full items-center justify-center space-x-7">
                    <SecondaryButton
                        title="Get Started"
                        class="w-fit bg-brand-400 !py-2 text-white"
                        @click="gettingStarted = false"
                    />

                    <div
                        class="hover:text-bold flex cursor-pointer space-x-1.5 text-sm font-medium text-black hover:scale-105"
                    >
                        <div>Learn more</div>

                        <ArrowRightIcon class="w-3.5" />
                    </div>
                </div>
            </div>
            <div
                v-else
                class="flex h-fit w-7/12 flex-col items-center space-y-8 rounded-lg border-2 border-black p-10"
            >
                <p class="w-9/12 text-center">
                    For optimal scheduling, we kindly ask you to establish the
                    daily and weekly teaching quotas that will be applied to all
                    teachers.
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
import { ArrowRightIcon } from "@heroicons/vue/24/outline";

const emit = defineEmits(["success"]);

const gettingStarted = ref(true);
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
