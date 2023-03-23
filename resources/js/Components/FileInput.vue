<template>
    <div class="flex h-4/5 w-full items-center justify-center">
        <label
            class="flex h-64 w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-100"
            :class="{'bg-gray-200': isDragging}"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop="handleDrop"

        >
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <CloudArrowUpIcon class="h-10 w-10"/>
                <p class="mb-2 text-sm text-gray-500"><span class="font-bold">Click to upload</span> or drag and drop
                </p>
                <p class="text-xs text-gray-500">(MAX. 800x400px)</p>
            </div>
            <div>
                <input
                    ref="fileInput"
                    type="file"
                    class="hidden"
                    accept=".pdf,.csv,.png,.jpg,.jpeg,.svg,.gif"
                    @change="handleFileUpload"
                />
            </div>
            <div v-if="show" class="my-2">
                <snap v-model="form.file" class="text-sm font-light">
                    file:
                    {{ form.file.name }}
                </snap>
            </div>
            <div v-if="show">
                <PrimaryButton @click="handleUpload">
                    Upload
                </PrimaryButton>
            </div>
        </label>
    </div>
</template>

<script setup>
import {computed, reactive, ref} from 'vue'
import {CloudArrowUpIcon} from '@heroicons/vue/24/outline'
import PrimaryButton from "@/Components/PrimaryButton.vue";

const form = reactive({
    file: null,
})

const isDragging = ref(false);

const show = computed(() => form.file !== null)

const handleFileUpload = (event) => {
    const files = event.target.files
    if (files.length > 0) {
        form.file = files[0]
    }
}

const handleUpload = () => {
    // Add code to upload the file here
    console.log('Uploading file:', form.file)
}

const handleDrop = (event) => {
    event.preventDefault()
    const files = event.dataTransfer.files
    if (files.length > 0) {
        form.file = files[0]
    }
}

const fileInput = ref(null)

</script>

<style scoped>
</style>
