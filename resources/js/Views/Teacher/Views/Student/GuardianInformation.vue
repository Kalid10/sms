<template>
    <div
        class="flex w-full flex-col items-center justify-center space-y-2 rounded-lg bg-white py-3 shadow-sm"
    >
        <div
            class="flex space-x-2 text-center text-xl font-semibold underline-offset-4"
        >
            <UserGroupIcon class="w-6" />
            <span> {{ $t("guardianInformation.guardianInfo") }}</span>
        </div>

        <div
            class="flex w-11/12 flex-col items-center space-y-2 pt-3 text-center text-xs 2xl:text-xs"
        >
            <div class="flex h-8 w-full items-center justify-center space-x-2">
                <div class="font-semibold">
                    {{ $t("guardianInformation.name") }}
                </div>
                <div>{{ guardian.user.name }}</div>
            </div>
            <div class="flex h-8 w-full items-center justify-center space-x-2">
                <div class="font-semibold">
                    {{ $t("guardianInformation.relation") }}
                </div>
                <div>{{ guardianRelation }}</div>
            </div>
            <div class="flex h-8 w-full items-center justify-center space-x-2">
                <div class="font-semibold">
                    {{ $t("guardianInformation.phoneNumber") }}
                </div>
                <div>
                    {{ guardian.user.phone_number }}
                </div>
            </div>
            <div class="flex h-8 w-full items-center justify-center space-x-2">
                <div class="font-semibold">
                    {{ $t("guardianInformation.email") }}
                </div>
                <div>{{ guardian.user.email }}</div>
            </div>
        </div>

        <div class="flex w-full justify-center py-1">
            <SecondaryButton
                :title="$t('guardianInformation.requestMeeting')"
                class="mt-3 w-8/12 !rounded-2xl bg-brand-450 py-1 text-white"
                @click="showModal = true"
            />
        </div>
    </div>

    <Modal v-model:view="showModal">
        <RequestMeeting @success="showModal = false" />
    </Modal>
</template>
<script setup>
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import { UserGroupIcon } from "@heroicons/vue/24/outline";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import RequestMeeting from "@/Views/Teacher/Views/Student/RequestMeeting.vue";

const guardian = computed(() => usePage().props.student.guardian);
const guardianRelation = computed(
    () => usePage().props.student.guardian_relation
);

const showModal = ref(false);

const info = [
    {
        label: "Name",
        value: "John Doe",
    },
    {
        label: "Email",
        value: "john@gmail.com",
    },
    {
        label: "Phone",
        value: "1234567890",
    },
    {
        label: "Address",
        value: "123, Lorem Ipsumit Amet",
    },
    {
        label: "Interaction",
        value: "Moderate",
    },
];
</script>

<style scoped></style>
