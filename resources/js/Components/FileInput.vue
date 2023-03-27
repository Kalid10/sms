<template>
    <div class="flex h-4/5 w-full items-center justify-center">
        <label
            class="flex h-64 w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-100"
            :class="{ 'bg-gray-200': isDragging }"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop="handleDrop"
        >
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <CloudArrowUpIcon class="h-12 w-12 text-gray-400"/>
                <p class="pt-5 text-sm text-gray-500">
                    <span class="font-bold">Click to upload</span> or drag and drop
                </p>
                <p class="text-xs text-gray-500">
                    (MAX. {{
                        maxFileSize && !isNaN(maxFileSize) ? (maxFileSize / 1024 / 1024).toFixed(2) + ' MB' : 'N/A'
                    }}, {{ acceptedTypes }})
                </p>
            </div>
            <div>
                <input
                    ref="fileInput"
                    type="file"
                    class="hidden"
                    :accept="acceptedTypes"
                    @change="handleFileUpload($event)"
                />
            </div>
            <div v-if="show" class="my-2">
        <span v-if="form.file" class="text-sm font-light">
          file: {{ form.file.name }} ({{ getFileSize(form.file.size) }})
        </span>
                <span v-else class="text-sm font-light">
          No file selected.
        </span>
            </div>
            <div v-if="show">
                <PrimaryButton @click="handleUpload">Upload</PrimaryButton>
            </div>
        </label>
    </div>
</template>

<script setup>
import {computed, defineEmits, defineProps, reactive, ref} from "vue";
import {CloudArrowUpIcon} from "@heroicons/vue/24/outline";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    acceptedTypes: {
        type: String,
        default: "file/pdf,file/docx,file/doc,file/xlsx,file/xls",
    },
    maxFileSize: {
        type: Number,
        default: NaN,
    },
});

const form = reactive({
    file: null,
});

const isDragging = ref(false);

const show = computed(() => form.file !== null);

const handleFileUpload = (event) => {
    const files = event.target.files;
    if (files.length > 0) {
        const file = files[0];
        if (file.size <= props.maxFileSize) {
            form.file = file;
        } else {
            form.file = null;
            alert(`File size must be less than ${props.maxFileSize && !isNaN(props.maxFileSize) ? (props.maxFileSize / 1024 / 1024).toFixed(2) + ' MB' : 'N/A'}.`);
        }
    }
};

defineEmits(["file-uploaded"]);

const handleUpload = () => {
    emit("file-uploaded", form.file);
};

const handleDrop = (event) => {
    event.preventDefault();
    const files = event.dataTransfer.files;
    if (files.length > 0) {
        const file = files[0];
        if (file.size <= props.maxFileSize) {
            form.file = file;
        } else {
            form.file = null;
            alert(`The file you selected is too large. Please choose a file that is smaller than ${props.maxFileSize && !isNaN(props.maxFileSize) ? (props.maxFileSize / 1024 / 1024).toFixed(2) + ' MB' : 'N/A'}.`);
        }
    }
    isDragging.value = false;
};

const acceptedTypes = computed(() => props.acceptedTypes);

const getFileSize = (bytes) => {
    if (bytes === 0) {
        return '0 B';
    }
    const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(1024));
    return parseFloat((bytes / Math.pow(1024, i)).toFixed(2)) + ' ' + sizes[i];
};

const fileInput = ref(null);
</script>
