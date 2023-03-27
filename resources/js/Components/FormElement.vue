<template>

    <form
        v-if="! modal || (modal && showModal)"
        :class="[ modal ? 'container fixed inset-x-0 top-0 z-50 mx-auto max-w-3xl animate-slide-down rounded-b-md shadow-md' : 'rounded-md shadow-sm' ]"
        class="flex min-h-10 w-full flex-col border bg-white">

        <div class="flex flex-col gap-3 p-4">
            <div class="">
                <h3 class="">{{ title }}</h3>
                <h5 class="text-sm text-gray-500">
                    {{ subtitle }}
                </h5>
            </div>

            <hr>

            <slot/>

        </div>

        <div class="flex min-h-10 w-full items-center justify-end gap-2 bg-neutral-100 py-2 px-4">
            <slot name="form-actions">
            <SecondaryButton :title="modal ? 'Close' : 'Clear'" @click="cancel"/>
            <PrimaryButton title="Submit" @click="submit"/>
            </slot>
        </div>

    </form>

</template>

<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue"
import SecondaryButton from "@/Components/SecondaryButton.vue"

defineProps({
    modal: {
        type: Boolean,
        default: false
    },
    showModal: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        required: true
    },
    subtitle: {
        type: String,
        required: true
    }
})

const emits = defineEmits(['update:showModal', 'cancel', 'submit'])

function cancel() {
    emits('cancel')
    emits('update:showModal', false)
}

function submit() {
    // TODO: implement Inertia form handling
    emits('submit')
    emits('update:showModal', false)
}

</script>

<style scoped>

</style>
