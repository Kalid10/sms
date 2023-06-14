<template>
    <div
        class="group flex w-full cursor-pointer justify-between px-3 py-4 hover:rounded-lg hover:bg-zinc-700 hover:text-gray-50"
    >
        <p
            class="flex shrink-0 items-center border-r px-2 text-[0.65rem] font-light hover:text-gray-100 sm:text-right"
        >
            {{ moment(announcement.created_at).fromNow() }}
        </p>
        <div class="flex w-full flex-col space-y-2 pl-3 text-xs font-semibold">
            <div>{{ announcement.title }}</div>

            <div
                class="flex w-full justify-between text-[0.62rem] font-light text-black group-hover:text-gray-50"
            >
                <div>
                    <div>
                        Post Targets
                        <span
                            v-for="(target, index) in announcement.target_group"
                            :key="index"
                            class="p-1 text-[0.6rem] font-bold uppercase"
                        >
                            {{ target }}
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
                        Posted by Author - {{ announcement.author.user.name }}
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
