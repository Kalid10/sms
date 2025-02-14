<template>
    <form
        class="flex flex-col items-center space-y-6 py-10"
        @submit.prevent="submit"
    >
        <img
            v-if="imagePreview && showImagePreview"
            :src="imagePreview"
            class="h-56 w-52 rounded-3xl object-cover"
            alt="img"
        />
        <div class="flex items-center">
            <label
                class="block w-96 cursor-pointer rounded-lg border-2 border-brand-500 bg-gray-500 py-1 text-center text-sm font-medium text-white hover:scale-105 focus:outline-none"
                for="fileInput"
            >
                {{ uploadImageButtonText ?? $t("imageUpload.chooseImage") }}
            </label>
            <input
                id="fileInput"
                type="file"
                class="hidden"
                accept="image/*"
                @change="handleImageUpload"
            />
        </div>

        <PrimaryButton
            v-if="image && showFinishButton"
            type="submit"
            class="rounded-md bg-brand-400 p-2 text-brand-50"
            @click="submit"
        >
            {{ finishButtonText }}
        </PrimaryButton>
    </form>
    <Loading v-if="isUploading" :is-full-screen="true" />
</template>

<script setup>
import { inject, ref } from "vue";
import { router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Loading from "@/Components/Loading.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const props = defineProps({
    showImagePreview: {
        type: Boolean,
        default: true,
    },
    finishButtonText: {
        type: String,
        default: "Upload",
    },
    uploadUrl: {
        type: String,
        default: "/admin/user/register/upload",
    },
    showFinishButton: {
        type: Boolean,
        default: true,
    },
    uploadImageButtonText: {
        type: String,
        default: null,
    },
    showResponseNotification: {
        type: Boolean,
        default: false,
    },
    imagePreview: {
        type: String,
        default: null,
    },
});
const emit = defineEmits(["imageUploaded"]);

const image = ref(null);
const imagePreview = ref(props.imagePreview);
const showNotification = inject("showNotification");
const isUploading = ref(false);

function handleImageUpload(event) {
    image.value = event.target.files[0];

    // Set a size limit in bytes
    let sizeLimit = 1024 * 1024;

    if (image.value.size > sizeLimit) {
        showNotification({
            type: "error",
            message: t("imageUpload.imageSizeLimit"),
            position: "top-center",
        });
        return;
    }

    let reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.value = e.target.result;
        emit("imageUploaded", imagePreview.value, image.value);
    };
    reader.readAsDataURL(image.value);
}

const submit = () => {
    isUploading.value = true;
    router.post(
        props.uploadUrl,
        {
            image: image.value,
        },
        {
            preserveState: true,
            onFinish: () => {
                isUploading.value = false;
            },
            onSuccess: () => {
                if (props.showResponseNotification) {
                    showNotification({
                        type: "success",
                        message: t("imageUpload.imageUploadedSuccessfully"),
                        position: "top-center",
                    });
                }
            },
        }
    );
};
</script>
