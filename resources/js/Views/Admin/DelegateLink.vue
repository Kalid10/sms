<template>
    <div>
        <Modal
v-model:view="delegateModal"
               :title="$t('delegateLink.modalTitle')">
            <div>
                <RegisterAdmin></RegisterAdmin>
            </div>
        </Modal>
        <div class="relative flex justify-end">
            <div class="absolute">
                <a
                    class="cursor-pointer px-6 text-blue-300 hover:text-blue-500"
                    @click="delegateModal = true"
                    >{{ $t('delegateLink.Delegate')}}</a
                >
            </div>
        </div>
    </div>
</template>
<script setup>
import RegisterAdmin from "@/Views/Admin/GettingStarted/RegisterAdmin.vue";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";
import StudentsList from "@/Views/Teacher/Views/Batches/PerformanceHighlights/StudentsList.vue";

const delegateModal = ref(false);
</script>
