<template>
    <div>
        <Modal v-model:view="delegateModal" title="Delegate tasks">
            <div>
                <RegisterAdmin></RegisterAdmin>
            </div>
        </Modal>
        <div class="relative flex justify-end">
            <div class="absolute">
                <a
                    class="cursor-pointer px-6 text-blue-300 hover:text-blue-500"
                    @click="delegateModal = true"
                    >Delegate</a
                >
            </div>
        </div>
    </div>
</template>
<script setup>
import RegisterAdmin from "@/Views/Admin/GettingStarted/RegisterAdmin.vue";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";

const delegateModal = ref(false);
</script>
