<template>
    <div class="flex h-12 w-11/12 items-center space-x-2">
        <!--                 Image-->
        <div class="h-12 w-2/12">
            <img
                :src="
                    student?.user?.profile_image ??
                    'https://avatars.dicebear.com/api/open-peeps/' +
                        student.user.name +
                        '.svg'
                "
                alt="avatar"
                class="mx-auto h-11 w-11 rounded-full object-contain"
            />
        </div>

        <!--                Name and Progress section-->
        <div class="flex h-11 w-9/12 flex-col justify-evenly text-start">
            <div class="grow text-sm">
                {{ student.user.name }}
            </div>

            <div class="flex items-center space-x-3">
                <div
                    class="flex items-center justify-center text-center text-xs font-light"
                >
                    {{ grade }} / {{ totalScore }}
                </div>
            </div>
        </div>

        <!--                Rank section-->
        <div class="flex w-11 items-center justify-center space-y-1 rounded-lg">
            <div
                class="flex h-10 w-11 items-center justify-center rounded-full text-sm"
            >
                {{ rank }}
            </div>
        </div>
    </div>
</template>
<script setup>
defineProps({
    student: {
        type: String,
        required: true,
    },
    progress: {
        type: Number,
        required: true,
    },
    attendance: {
        type: Number,
        required: true,
    },
    grade: {
        type: String,
        required: true,
    },
    rank: {
        type: Number,
        required: true,
    },
    progressType: {
        type: String,
        required: true,
    },
    totalScore: {
        type: Number,
        required: true,
    },
});
</script>
<style scoped></style>
