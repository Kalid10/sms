<template>
    <div class="flex w-full flex-col space-y-5 rounded-lg bg-white p-5">
        <div class="text-center text-2xl font-medium uppercase">
          {{ $t('flagInfo.understandingStudentFlags')}}
        </div>

        <div class="flex flex-col space-y-4 px-4 py-3 text-sm text-gray-700">
            <p>
                {{ $t('flagInfo.descriptionOne')}}
            </p>
            <p>
                {{ $t('flagInfo.descriptionTwo')}}
            </p>
            <p>
                {{ $t('flagInfo.descriptionThree')}}
            </p>
            <p>
                {{ $t('flagInfo.descriptionFour')}}
            </p>
        </div>
    </div>
</template>
<script setup></script>

<style scoped></style>
