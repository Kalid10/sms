<template>
    <div ref="container" class="relative w-full">
        <div class="flex flex-col">
            <TextInput
                v-model="query"
                class-style="rounded-2xl w-full placeholder:text-xs focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:border-transparent"
                type="text"
                placeholder="Search For A Parent"
            />
            <div
                v-if="filteredGuardians.length > 0 && showGuardians"
                class="mt-10 w-full rounded-lg bg-white p-2 shadow-sm"
            >
                <div
                    v-for="(guardian, index) in filteredGuardians"
                    v-show="query.length > 0"
                    :key="guardian"
                    class="cursor-pointer py-2 text-xs hover:rounded-lg hover:bg-brand-400 hover:text-white"
                    :class="
                        index % 2 === 0
                            ? 'bg-brand-50 px-10 hover:bg-brand-200'
                            : 'bg-white px-10 hover:bg-brand-200'
                    "
                    @click="selectGuardian(guardian)"
                >
                    <p class="px-3 py-2">
                        {{ guardian.user.name }}
                    </p>
                </div>
            </div>
            <div
                v-else-if="showLoading"
                class="mt-2 rounded-lg bg-white py-8 shadow-sm"
            >
                <Loading color="secondary" />
            </div>
            <div
                v-else-if="query.length > 0 && showGuardians"
                class="mt-1 flex flex-col items-center justify-center rounded-lg border border-zinc-100 bg-white py-8 shadow-sm"
            >
                <p v-if="!!query" class="text-sm text-black">
                    No parent found with the name "{{ query }}"
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import TextInput from "@/Components/TextInput.vue";
import { computed, defineEmits, ref, watch } from "vue";
import { debounce } from "lodash";
import { router, usePage } from "@inertiajs/vue3";
import { onClickOutside } from "@vueuse/core";
import Loading from "@/Components/Loading.vue";

const guardians = computed(() => usePage().props.guardians);
const query = ref("");
const showGuardians = ref(false);
const showLoading = ref(false);

const container = ref(null);

onClickOutside(container, () => {
    showGuardians.value = false;
});

const emits = defineEmits(["select-guardian"]);

const selectGuardian = (guardian) => {
    query.value = guardian.user.name;
    emits("select-guardian", guardian);
    showGuardians.value = false;
};

watch(query, () => {
    showGuardians.value = true;
    fetchGuardians();
});

const fetchGuardians = debounce(async function () {
    showLoading.value = true;
    router.get(
        "/admin/user/register/student/",
        {
            search: query.value,
        },
        {
            only: ["guardians"],
            preserveState: true,
            replace: true,
            onFinish: () => {
                showLoading.value = false;
            },
        }
    );
}, 500);

const filteredGuardians = computed(() => {
    if (query.value === "") {
        return guardians.value || [];
    } else {
        return guardians.value
            ? guardians.value.filter((guardian) =>
                  guardian.user.name
                      .toLowerCase()
                      .includes(query.value.toLowerCase())
              )
            : [];
    }
});
</script>

<style scoped></style>
