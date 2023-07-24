<template>
    <Modal
        v-model:view="welcomeModal"
        center
        background-color="black"
        :close-on-outside-click="false"
    >
        <Card class="mx-auto mt-12 !min-w-full">
            <div class="flex w-full flex-col items-center gap-6">
                <div>
                    <Heading class="text-center" size="md">
                        {{ $t("schoolPeriodFormWrapper.almostThere") }}
                    </Heading>
                </div>

                <div class="flex flex-col gap-3">
                    <p
                        class="max-w-2xl text-center text-sm text-brand-text-300"
                    >
                        {{ $t("schoolPeriodFormWrapper.descriptionOne") }}
                    </p>

                    <p
                        class="max-w-2xl text-center text-sm text-brand-text-300"
                    >
                        {{ $t("schoolPeriodFormWrapper.descriptionTwo") }}
                    </p>

                    <p
                        class="max-w-2xl text-center text-sm text-brand-text-300"
                    >
                        {{ $t("schoolPeriodFormWrapper.descriptionThree") }}
                    </p>
                </div>

                <div class="flex flex-col">
                    <PrimaryButton
                        :title="
                            $t('schoolPeriodFormWrapper.addSchoolPeriodTitle')
                        "
                        @click="welcomeModal = false"
                    />
                </div>
            </div>
        </Card>
    </Modal>
</template>
<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Card from "@/Components/Card.vue";
import Modal from "@/Components/Modal.vue";
import Heading from "@/Components/Heading.vue";
import { ref } from "vue";

const welcomeModal = ref(true);
</script>
