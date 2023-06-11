<template>
    <div
        class="group flex w-full cursor-pointer justify-between p-3 hover:rounded-lg hover:bg-zinc-700 hover:text-gray-50"
    >
        <div class="flex w-full flex-col space-y-2 text-sm font-bold">
            <div>{{ announcement.title }}</div>

            <div
                class="flex w-full justify-between text-[0.65rem] text-gray-500 group-hover:text-gray-50"
            >
                <div>
                    <div>
                        Post Targets
                        <span class="capitalize">
                            {{ announcement.target_group }}
                        </span>
                        and expires
                        <span>
                            {{
                                moment(announcement.expires_on).fromNow()
                            }}</span
                        >
                    </div>
                </div>
                <div>
                    <div>
                        Posted
                        <span>
                            {{
                                moment(announcement.created_at).fromNow()
                            }}</span
                        >
                        by Author - {{ announcement.author.user.name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import moment from "moment";

defineProps({
    announcement: {
        type: Object,
        required: true,
    },
});
</script>
<style scoped></style>
