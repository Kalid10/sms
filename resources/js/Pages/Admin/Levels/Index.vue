<template>
    <PrimaryButton class="absolute right-0 mt-4" @click="levelCategoryLink">
        Go To Level Categories
    </PrimaryButton>

    <LevelsTable :levels="levels" :config="config" />
</template>

<script setup>
import { computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import moment from "moment";
import LevelsTable from "@/Views/Admin/Levels/LevelsTable.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const levels = computed(() => {
    return usePage().props.levels.map((level) => {
        return {
            batches: level.batches,
            id: level.id,
            level: {
                id: level.id,
                name: level.name,
                category: level["level_category"],
            },
            updated_at: moment(level.updated_at).fromNow(),
            created_at: level.created_at,
        };
    });
});

const config = [
    {
        name: "Level Category",
        key: "level",
        type: "custom",
    },
    {
        name: "Sections",
        key: "batches",
        type: "custom",
    },
    {
        name: "Updated at",
        key: "updated_at",
        type: "custom",
    },
];

function levelCategoryLink() {
    router.get("/level-categories");
}
</script>

<style scoped></style>
