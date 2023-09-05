<template>
    <FormElement
        :title="$t('paymentProviders.paymentProviderForm')"
        @submit="submit"
        @cancel="form.reset()"
    >
        <TextInput
            v-model="form.name"
            :placeholder="$t('common.name')"
            :label="$t('common.name')"
            :error="usePage().props.errors.name"
        />

        <Toggle
            v-model="form.is_enabled"
            :label="$t('paymentProviders.enableDisable')"
        />

        <img
            v-if="imagePreview"
            :src="imagePreview"
            alt="avatar"
            class="mx-auto w-16 rounded-full bg-gray-100 object-contain p-4 md:h-52 md:w-52"
        />

        <ImageUpload
            :show-finish-button="false"
            :show-image-preview="false"
            :upload-url="
                isTeacher()
                    ? '/teacher/user/upload'
                    : '/admin/user/register/upload'
            "
            @image-uploaded="setupImage"
        />
        <Loading v-if="isLoading" is-full-screen />
    </FormElement>
</template>
<script setup>
import FormElement from "@/Components/FormElement.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import Toggle from "@/Components/Toggle.vue";
import ImageUpload from "@/Components/ImageUpload.vue";
import { isTeacher } from "@/utils";
import { inject, ref } from "vue";
import Loading from "@/Components/Loading.vue";

const emit = defineEmits(["close"]);

const showNotification = inject("showNotification");

const form = useForm({
    name: "",
    is_enabled: true,
    logo: null,
});

const isLoading = ref(false);

const imagePreview = ref(null);

function setupImage(preview, image) {
    form.logo = image;
    imagePreview.value = preview;
}

function submit() {
    isLoading.value = true;
    form.post("/admin/payment-providers/create", {
        preserveScroll: true,
        onSuccess: () => {
            emit("close");
        },
        onFinish: () => {
            isLoading.value = false;
        },
        onError: (error) => {
            if (error.logo)
                showNotification({
                    type: "error",
                    message: error.logo,
                    position: "top-center",
                });
        },
    });
}
</script>

<style scoped></style>
