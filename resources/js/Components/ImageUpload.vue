<template>
    <form class="flex flex-col items-center" @submit.prevent="submit">
        <input
            type="file"
            class="form-input my-2"
            @change="handleImageUpload"
        />

        <img
            v-if="imagePreview"
            :src="imagePreview"
            class="my-2 w-64"
            alt="img"
        />

        <button
            v-if="image"
            type="submit"
            class="rounded-md bg-brand-500 p-2 text-brand-50"
        >
            Upload
        </button>
    </form>
</template>

<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const image = ref(null);

const imagePreview = ref(null);

function handleImageUpload(event) {
    image.value = event.target.files[0];

    let reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(image.value);
}

const submit = () => {
    router.post(
        "/admin/user/register/upload",
        {
            image: image.value,
        },
        {
            preserveState: true,
        }
    );
};
</script>
