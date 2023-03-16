<template>

    <form
        v-if="! modal || (modal && showModal)"
        :class="[ modal ? 'fixed top-0 left-0 z-50 animate-slide-down rounded-b-md shadow-md' : 'rounded-md shadow-sm' ]"
        class="flex min-h-10 w-full flex-col border bg-white">

        <div class="flex flex-col gap-3 p-4">
            <div class="">
                <h3 class="">Register New User</h3>
                <h5 class="text-sm text-gray-500">Fill information of the new user to be registered, including
                    profile and user type</h5>
            </div>

            <hr>

            <slot/>

        </div>

        <div class="flex min-h-10 w-full items-center justify-end gap-2 bg-neutral-100 py-2 px-4">

            <SecondaryButton :click="cancel" :title="modal ? 'Close' : 'Clear'"/>
            <PrimaryButton :click="submit" title="Submit"/>

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
    }
})

const emits = defineEmits(['update:showModal', 'cancel', 'submit'])

function cancel() {
    emits('cancel')
    emits('update:showModal', false)
}

function submit() {
    // TODO: implement Inertia form handling
    emits('cancel')
    emits('update:showModal', false)
}

</script>

<style scoped>

</style>
