<template>
    <div
        class="flex h-fit w-full flex-col items-center rounded-xl bg-brand-450 text-white"
        :class="isSidebarOpenOnXlDevice ? 'lg:py-3' : 'lg:py-5'"
    >
        <div
            v-if="nextClass"
            :class="[
                isSidebarOpenOnXlDevice
                    ? 'flex w-full flex-col justify-evenly divide-y-2 divide-neutral-800 py-2'
                    : 'flex w-full flex-col justify-evenly divide-y-2 divide-neutral-800 py-2 lg:flex-row lg:divide-y-0 lg:py-0',
            ]"
        >
            <NextClassSection />
        </div>

        <!--        Fall back message-->
        <div
            v-else
            class="flex h-32 w-11/12 flex-col justify-center text-center text-sm font-light leading-relaxed lg:h-36 lg:w-10/12 lg:text-base"
        >
            <div>
                {{ $t("nextClassIndex.noUpcomingClasses") }}
                <span class="font-medium">{{
                    $t("nextClassIndex.admin")
                }}</span>
                {{ $t("nextClassIndex.forAssistance") }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { isSidebarOpenOnXlDevice } from "@/utils";
import NextClassSection from "@/Views/Teacher/Views/NextClass/NextClass.vue";

const nextClass = usePage().props.teacher.next_batch_session;
</script>

<style scoped></style>
