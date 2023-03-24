<template>
    <div>
        <DialogBoxModal :view="confirmModal">
            <div class="relative max-w-md rounded-lg bg-white shadow dark:bg-gray-700 ">
                <div class="flex flex-col justify-center p-4">
                    <div v-if="type === 'confirmation'" class="flex justify-center">
                        <ShieldCheckIcon class="h-10 w-10" />
                    </div>
                    <div v-else-if="type === 'delete'" class="flex justify-center">
                        <TrashIcon class="h-10 w-10" />
                    </div>
                    <h3 class="flex justify-center text-lg font-bold leading-6">
                        {{ type === 'confirmation' ? confirmationTitle : deleteTitle }}
                    </h3>
                    <div class="mt-2 flex justify-center">
                        <slot :text="type === 'confirmation' ? confirmationText : deleteText">
                            <p class="text-sm">{{ type === 'confirmation' ? confirmationText : deleteText }}</p>
                        </slot>
                    </div>
                </div>
                <div class="mt-5 flex justify-center space-x-4 p-2">
                    <PrimaryButton :title="confirmText" @click="$emit('confirm')"></PrimaryButton>
                    <PrimaryButton :title="cancelText" @click="$emit('close')"></PrimaryButton>
                </div>
            </div>
        </DialogBoxModal>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import DialogBoxModal from './Modal.vue';
import PrimaryButton from './PrimaryButton.vue';
import { TrashIcon, ShieldCheckIcon } from '@heroicons/vue/24/outline';

defineEmits(['confirm', 'close'])

const confirmModal = ref(true);
defineProps({
    type: {
        type: String,
        validator: (value) => {
            return ['confirmation', 'delete'].includes(value);
        },
        default: 'confirmation',
    },
    confirmationTitle: {
        type: String,
        default: 'Confirmation',
    },
    confirmationText: {
        type: String,
        default: 'Are you sure you want to proceed?',
    },
    deleteTitle: {
        type: String,
        default: 'Delete',
    },
    deleteText: {
        type: String,
        default: 'Are you sure you want to delete this item?',
    },
    confirmText: {
        type: String,
        default: 'Confirm',
    },
    cancelText: {
        type: String,
        default: 'Cancel',
    },
});
</script>
