<template>
    <div class="flex w-full flex-col space-y-5 rounded-lg bg-white p-5">
        <div class="text-center text-2xl font-medium">
            {{ selectedAnnouncement.title }}
        </div>

        <div class="flex items-center text-sm font-light">
            {{ selectedAnnouncement.body }}
        </div>

        <div>
            <div class="py-2 text-sm font-medium">
                <span
                    v-for="(target, index) in selectedAnnouncement.target_group"
                    :key="index"
                    class="mr-2 rounded-md bg-brand-400 px-3 py-1 text-xs font-medium uppercase text-white"
                >
                    {{ target }}
                </span>
            </div>
            <div class="flex items-end justify-between space-y-2 text-xs">
                <div>
                    Expires
                    {{ moment(selectedAnnouncement.expires_on).fromNow() }}
                </div>
                <div>
                    <div>
                        Posted
                        {{ moment(selectedAnnouncement.created_at).fromNow() }},
                        By
                        <span
                            class="cursor-pointer underline-offset-2 hover:scale-105 hover:font-semibold hover:underline"
                        >
                            {{ selectedAnnouncement.author.user.name }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import moment from "moment";

defineProps({
    selectedAnnouncement: {
        type: Object,
        required: true,
    },
});
</script>

<style scoped></style>
