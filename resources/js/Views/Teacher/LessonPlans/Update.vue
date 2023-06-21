<template>
    <div>
        <FormElement
            :title="$t('views.teacher.lessonPlans.lessonPlansUpdate.formElementTitle')"
            :subtitle="$t('views.teacher.lessonPlans.lessonPlansUpdate.formElementSubtitle')"
            @submit="update"
        >
            <TextInput
                v-model="form.topic"
                required
                :placeholder="$t('views.teacher.lessonPlans.lessonPlansUpdate.topicPlaceholder')"
                :label="$t('views.teacher.lessonPlans.lessonPlansUpdate.topicLabel')"
            />
            <TextArea
                v-model="form.description"
                required
                :placeholder="$t('views.teacher.lessonPlans.lessonPlansUpdate.descriptionPlaceholder')"
                :label="$t('views.teacher.lessonPlans.lessonPlansUpdate.descriptionLabel')"
            />

            <TextInput
                v-model="form.batch_session_id"
                disabled
                required
                :placeholder="form.batch_session_id"
                :label="$t('views.teacher.lessonPlans.lessonPlansUpdate.batchSession')"
            />
        </FormElement>
    </div>
</template>
<script setup>
import FormElement from "@/Components/FormElement.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import TextArea from "@/Components/TextArea.vue";
import { onMounted } from "vue";

const props = defineProps({
    lessonPlan: {
        type: Object,
        default: null,
    },
});

onMounted(() => {
    if (props.lessonPlan) {
        edit();
    }
});

const form = useForm({
    topic: "",
    description: "",
    id: "",
    batch_session_ids: [4],
});

const update = () => {
    form.post("lesson-plan/");
};

function edit() {
    form.topic = props.lessonPlan.topic;
    form.description = props.lessonPlan.description;
    form.id = props.lessonPlan.id;
    form.batch_session_id = props.lessonPlan.batch_session_id;
}
</script>
