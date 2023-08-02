<template>
    <div class="grid-rows-12 grid h-screen p-6 sm:grid-cols-12 md:w-full">
        <div class="col-span-3 mb-6 flex shrink-0 flex-col md:mb-0">
            <Heading :value="$t('createTeacher.headerOne')" />
            <Heading
                :value="$t('createTeacher.headerTwo')"
                size="sm"
                class="text-xs !font-light text-zinc-700"
            />
        </div>
        <div class="col-span-8">
            <div class="w-full max-w-4xl rounded-lg bg-white">
                <TeacherFormElement
                    :title="$t('createTeacher.registerTeacher')"
                    @submit="submit"
                    @cancel="form.reset()"
                >
                    <TeacherTextInput
                        v-model="form.name"
                        :label="$t('common.name')"
                        :placeholder="$t('createTeacher.namePlaceholder')"
                        :error="form.errors.name"
                        required
                    />

                    <div class="flex gap-3">
                        <TeacherTextInput
                            v-model="form.username"
                            class="w-full"
                            :label="$t('createTeacher.usernameLabel')"
                            :placeholder="
                                $t('createTeacher.usernamePlaceholder')
                            "
                            :error="form.errors.username"
                            required
                        />

                        <TeacherTextInput
                            v-model="form.phone_number"
                            class="w-full"
                            :label="$t('createTeacher.phoneNumberLabel')"
                            :placeholder="
                                $t('createTeacher.phoneNUmberPlaceholder')
                            "
                            :error="form.errors.phone_number"
                            required
                        />
                    </div>

                    <div class="flex gap-3">
                        <TeacherTextInput
                            v-model="form.email"
                            class="w-full"
                            :label="$t('common.email')"
                            :placeholder="$t('common.email')"
                            type="email"
                            :error="form.errors.email"
                            required
                        />
                        <TeacherSelectInput
                            v-model="form.gender"
                            class="w-full cursor-pointer"
                            :label="$t('common.gender')"
                            :placeholder="$t('createTeacher.genderPlaceholder')"
                            :error="form.errors.gender"
                            :options="genderOptions"
                            required
                        />
                    </div>
                </TeacherFormElement>
            </div>
        </div>
    </div>
</template>

<script setup>
import TeacherFormElement from "@/Components/FormElement.vue";
import TeacherTextInput from "@/Components/TextInput.vue";
import TeacherSelectInput from "@/Components/SelectInput.vue";
import Heading from "@/Components/Heading.vue";
import { useForm } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const genderOptions = [
    { value: "male", label: t("common.male") },
    { value: "female", label: t("common.female") },
];

const form = useForm({
    name: "",
    type: "teacher",
    email: "",
    phone_number: "",
    username: "",
    gender: "",
});

const submit = () => {
    form.post(route("register.admin"));
};
</script>
