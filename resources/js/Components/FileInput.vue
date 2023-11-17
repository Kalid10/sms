<template>
    <div class="flex h-4/5 w-full items-center justify-center">
        <label
            class="flex h-fit min-h-[16rem] w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-brand-100 pb-4"
            :class="{ 'bg-brand-150': isDragging }"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop="handleDrop"
        >
            <span class="flex flex-col items-center justify-center pb-6 pt-5">
                <CloudArrowUpIcon class="h-12 w-12 text-brand-text-250" />
                <slot name="header" />
                <span class="pt-5 text-sm text-brand-text-300">
                    <span v-html="$t('fileInput.uploadText')" />
                </span>
                <span class="text-xs text-brand-text-300">
                    ( {{ $t("fileInput.max") }}
                    {{
                        maxFileSize && !isNaN(maxFileSize)
                            ? (maxFileSize / 1024 / 1024).toFixed(2) + " MB"
                            : "N/A"
                    }}, {{ acceptedTypes }})
                </span>
            </span>
            <span>
                <input
                    ref="fileInput"
                    type="file"
                    class="hidden"
                    :accept="acceptedTypes"
                    :multiple="isMultiple"
                    @change="handleFileUpload($event)"
                />
            </span>
            <slot name="description" />
            <span v-if="show" class="my-2">
                <span
                    v-if="isMultiple"
                    class="flex flex-wrap justify-center space-x-2 text-sm font-semibold"
                >
                    <span v-for="(file, index) in form.file" :key="index">
                        <span
                            >{{ file.name }} ({{
                                getFileSize(file.size)
                            }})</span
                        >
                        <span v-if="form.file.length !== index + 1">,</span>
                    </span>
                </span>
                <span v-else-if="form.file" class="text-sm font-light">
                    {{ $t("fileInput.file") }} {{ form.file.name }} ({{
                        getFileSize(form.file.size)
                    }})
                </span>
                <span v-else class="text-sm font-light">
                    {{ $t("fileInput.noFileSelected") }}
                </span>
            </span>
            <span v-if="show">
                <PrimaryButton @click="handleUpload"
                    >{{ $t("fileInput.upload") }}
                </PrimaryButton>
            </span>
        </label>
    </div>
</template>

<script setup>
import { computed, defineEmits, defineProps, inject, reactive, ref } from "vue";
import { CloudArrowUpIcon } from "@heroicons/vue/24/outline";
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
    isMultiple: {
        type: Boolean,
        default: false,
    },
});

const form = reactive({
    file: null,
});

const isDragging = ref(false);

const show = computed(() => form.file !== null);
const showNotification = inject("showNotification");

const emit = defineEmits(["file-uploaded"]);

const handleUpload = () => {
    emit("file-uploaded", form.file);
};

const processFiles = (files) => {
    if (props.isMultiple) {
        let totalSize = 0;
        for (let i = 0; i < files.length; i++) {
            totalSize += files[i].size;
        }
        if (totalSize <= props.maxFileSize) {
            form.file = files;
        } else {
            form.file = null;
            showNotification({
                type: "error",
                message: `Total file size must be less than ${
                    props.maxFileSize && !isNaN(props.maxFileSize)
                        ? (props.maxFileSize / 1024 / 1024).toFixed(2) + " MB"
                        : "N/A"
                }.`,
            });
        }
    } else {
        if (files.length > 0) {
            const file = files[0];
            if (file.size <= props.maxFileSize) {
                form.file = file;
            } else {
                form.file = null;
                showNotification({
                    type: "error",
                    message: `File size must be less than ${
                        props.maxFileSize && !isNaN(props.maxFileSize)
                            ? (props.maxFileSize / 1024 / 1024).toFixed(2) +
                              " MB"
                            : "N/A"
                    }.`,
                });
            }
        }
    }
};

const handleFileUpload = (event) => {
    const files = event.target.files;
    processFiles(files);
};

const handleDrop = (event) => {
    event.preventDefault();
    const files = event.dataTransfer.files;
    processFiles(files);
    isDragging.value = false;
};

const acceptedTypes = computed(() => props.acceptedTypes);

const getFileSize = (bytes) => {
    if (bytes === 0) {
        return "0 B";
    }
    const sizes = ["B", "KB", "MB", "GB", "TB"];
    const i = Math.floor(Math.log(bytes) / Math.log(1024));
    return parseFloat((bytes / Math.pow(1024, i)).toFixed(2)) + " " + sizes[i];
};

const fileInput = ref(null);
</script>
