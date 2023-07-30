<template>
    <div class="grid-rows-12 grid p-6 sm:grid-cols-12 md:w-full">
        <div class="col-span-3 mb-6 flex shrink-0 flex-col md:mb-0">
            <Heading :value="$t('createAdmin.headingOne')" />
            <Heading
                :value="$t('createAdmin.headingTwo')"
                size="sm"
                class="text-xs !font-light text-zinc-700"
            />
        </div>
        <div class="col-span-8">
            <div class="w-full max-w-4xl rounded-lg bg-white">
                <AdminFormElement
                    :title="$t('createAdmin.adminFormElementTitle')"
                    @submit="submit"
                    @cancel="form.reset()"
                >
                    <AdminTextInput
                        v-model="form.name"
                        class="w-full"
                        :label="$t('common.name')"
                        :placeholder="$t('createAdmin.namePlaceholder')"
                        :error="form.errors.name"
                        required
                    />

                    <div class="flex gap-3">
                        <AdminTextInput
                            v-model="form.email"
                            class="w-full"
                            :label="$t('common.email')"
                            type="email"
                            :error="form.errors.email"
                            :placeholder="$t('common.email')"
                            required
                        />
                        <AdminTextInput
                            v-model="form.phone_number"
                            class="w-full"
                            :placeholder="$t('createAdmin.phoneNumber')"
                            :label="$t('createAdmin.phoneNumber')"
                            :error="form.errors.phone_number"
                            required
                        />
                    </div>
                    <AdminTextInput
                        v-model="form.username"
                        class="w-full"
                        :placeholder="$t('createAdmin.usernamePlaceholder')"
                        :label="$t('createAdmin.usernameLabel')"
                        :error="form.errors.username"
                        required
                    />

                    <div class="flex gap-3">
                        <AdminTextInput
                            v-model="form.position"
                            class="w-full"
                            :placeholder="$t('createAdmin.position')"
                            :label="$t('createAdmin.position')"
                            :error="form.errors.position"
                            required
                        />
                        <AdminSelectInput
                            v-model="form.gender"
                            class="w-full cursor-pointer"
                            :options="genderOptions"
                            :placeholder="$t('createAdmin.genderPlaceholder')"
                            :label="$t('createAdmin.genderLabel')"
                            required
                        />
                    </div>
                </AdminFormElement>
            </div>
        </div>
    </div>
</template>

<script setup>
import AdminFormElement from "@/Components/FormElement.vue";
import AdminTextInput from "@/Components/TextInput.vue";
import Heading from "@/Components/Heading.vue";
import { useForm } from "@inertiajs/vue3";
import AdminSelectInput from "@/Components/SelectInput.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const genderOptions = [
    { value: "male", label: t("common.male") },
    { value: "female", label: t("common.female") },
];

const form = useForm({
    name: "",
    username: "",
    type: "admin",
    email: "",
    phone_number: "",
    position: "",
    gender: "",
});

const submit = () => {
    form.post(route("register.admin"), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>
