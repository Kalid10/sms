<template>
    <div
        v-if="
            assessment.status !== 'completed' &&
            assessment.status !== 'marking' &&
            !showDeleteConfirmation
        "
        class="flex w-6/12 items-center justify-center rounded-2xl bg-red-600"
        @click="showDeleteConfirmation = true"
    >
        <TrashIcon class="w-5 text-white" />
        <SecondaryButton
            :title="$t('deleteAssessment.deleteAssessment')"
            class="text-white"
        />
    </div>

    <div
        v-if="showDeleteConfirmation"
        class="flex h-fit w-full animate-scale-up flex-col items-center space-y-3 rounded-md border-2 border-red-600 pb-4 shadow-sm"
    >
        <div
            class="flex w-full items-center justify-between space-x-1 bg-gradient-to-bl from-red-600 to-orange-500 px-4 py-2 text-center text-white"
        >
            <span class="grow text-center">
                {{ $t("deleteAssessment.deleteAssessment") }}
            </span>

            <XMarkIcon
                class="w-4 cursor-pointer hover:scale-125 hover:text-black"
                @click="showDeleteConfirmation = false"
            />
        </div>
        <div class="w-10/12 text-center font-semibold text-brand-text-450">
            {{ $t("deleteAssessment.deleteMessage") }}
        </div>

        <div
            v-if="!showDeleteConfirmationText"
            class="flex w-6/12 items-center justify-center rounded-2xl bg-red-600"
            @click="showDeleteConfirmationText = true"
        >
            <TrashIcon class="w-4 text-white" />
            <SecondaryButton
                :title="$t('deleteAssessment.deleteAssessment')"
                class="text-white"
            />
        </div>
        <div v-else class="flex w-full justify-center space-x-2">
            <TextInput
                v-model="deleteConfirmationText"
                :placeholder="'Type ' + assessment.title + ' to confirm'"
                class="w-8/12"
                class-style=" focus:border-none focus:ring-red-600  placeholder:text-brand-text-300 placeholder:text-xs"
                :error="deleteConfirmationError"
            />
            <div
                v-if="!isLoading"
                class="flex h-9 cursor-pointer items-center justify-center rounded-md bg-brand-450 px-2 shadow-md"
                @click="deleteAssessment"
            >
                <CheckIcon class="w-5 text-white" />
            </div>

            <Loading v-if="isLoading" color="danger" />
        </div>
    </div>
</template>
<script setup>
import { CheckIcon, TrashIcon, XMarkIcon } from "@heroicons/vue/20/solid";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Loading from "@/Components/Loading.vue";
import { router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const props = defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});

const showDeleteConfirmation = ref(false);
const showDeleteConfirmationText = ref(false);
const deleteConfirmationText = ref("");
const deleteConfirmationError = ref("");
const isLoading = ref(false);

function deleteAssessment() {
    if (deleteConfirmationText.value !== props.assessment.title) {
        return (deleteConfirmationError.value = t(
            "deleteAssessment.pleaseTypeAssessment"
        ));
    }

    isLoading.value = true;
    router.delete(
        "/teacher/assessments/delete/" +
            props.assessment.id +
            "?confirmation=" +
            deleteConfirmationText.value,
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                showDeleteConfirmationText.value = true;
                showDeleteConfirmation.value = false;
            },
            onFinish() {
                isLoading.value = false;
                deleteConfirmationError.value = usePage().props.flash.error;
            },
        }
    );
}
</script>
<style scoped></style>
