<template>
    <div
        class="flex w-full flex-col items-center space-y-6 rounded-lg bg-white p-5"
    >
        <div class="flex w-full items-center justify-center space-x-2 px-3">
            <div>{{ selectedFlagItem.flaggable.user.name }}'s Flag Detail</div>
            <div v-if="selectedFlagItem?.batch_subject">
                ( {{ selectedFlagItem.batch_subject?.subject?.full_name }} )
            </div>
            <div v-if="selectedFlagItem.is_homeroom" class="font-medium">
                (Homeroom)
            </div>
        </div>

        <div class="flex w-full space-x-2">
            <div
                v-for="(type, index) in selectedFlagItem.type"
                :key="index"
                class="w-fit rounded-2xl bg-red-600 px-2 py-0.5 text-center text-xs font-semibold lowercase text-white"
            >
                {{ type }}
            </div>
        </div>

        <div class="p-3 text-sm text-gray-500">
            {{ selectedFlagItem.description }}
        </div>
        <div
            class="flex w-full items-end justify-between px-4 text-center text-sm font-semibold"
        >
            <div class="text-xs font-light italic">
                Expiry Date:
                {{
                    moment(selectedFlagItem.expires_at).format(
                        "ddd MMMM DD YYYY"
                    )
                }}
            </div>

            <div>
                <div>{{ selectedFlagItem.flagged_by.name }}</div>
                <div class="pt-1 text-xs font-light">
                    {{
                        moment(selectedFlagItem.created_at).format(
                            "ddd MMMM DD YYYY"
                        )
                    }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import moment from "moment/moment";

defineProps({
    selectedFlagItem: {
        type: Object,
        default: () => {},
    },
});
</script>
<style scoped></style>
