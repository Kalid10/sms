<template>
    <TableElement
        :data="teacher"
        :columns="config"
        :selectable="false"
        :header="false"
    >
        <template #filter>
            <div
                class="flex flex-col items-end justify-between gap-3 lg:flex-row"
            >
                <div class="flex w-full flex-col">
                    <h3 class="whitespace-nowrap font-semibold">
                        {{ subject["full_name"] }}
                    </h3>

                    <h3 class="whitespace-nowrap text-sm text-gray-500">
                        {{ $t("subjectTeachers.listOfTeachers") }}
                        {{ subject["short_name"] }}
                        {{ $t("subjectTeachers.faculty") }}
                    </h3>
                </div>
                <div
                    class="flex w-full grow flex-col justify-end gap-2 md:flex-row"
                >
                    <TextInput
                        v-model="searchKey"
                        class="grow"
                        :placeholder="
                            $t('subjectTeachers.searchKeyPlaceholder')
                        "
                    />
                    <RadioGroup
                        v-model="filteredLevelCategory"
                        :options="levelCategoryOptions"
                        name="levelCategories"
                    />
                </div>
            </div>
        </template>

        <template #empty-data>
            <div class="flex flex-col items-center justify-center">
                <ExclamationTriangleIcon
                    class="mb-2 h-6 w-6 text-negative-50"
                />
                <p class="text-sm font-semibold">
                    {{ $t("subjectTeachers.noDataFound") }}
                </p>
                <div v-if="searchKey.length">
                    <p
                        v-if="searchKey === null"
                        class="text-sm text-brand-text-300"
                    >
                        {{ $t("subjectTeachers.NoTeacherEnrolled") }}
                    </p>
                    <p v-else class="text-center text-sm text-brand-text-300">
                        <span
                            v-html="
                                $t('subjectTeachers.yourQueryDidNotMatch', {
                                    searchKey,
                                })
                            "
                        />
                    </p>
                </div>
            </div>
        </template>

        <template #teacher-column="{ data }">
            <Link
                :href="`/admin/teachers/${data['teacher_id']}`"
                class="group flex items-center gap-3"
            >
                <div class="flex flex-col">
                    <div class="flex items-center gap-1.5">
                        <span
                            class="font-semibold underline-offset-2 group-hover:underline"
                            >{{ data["name"] }}</span
                        >
                        <span
                            :class="genderLabels[data['gender']]"
                            class="scale-[.7] rounded-xl px-2.5 py-0.5 font-semibold"
                            >{{ data["gender"] }}</span
                        >
                    </div>
                    <span class="text-gray-500">{{ data["email"] }}</span>
                </div>
            </Link>
        </template>

        <template #batches-column="{ data }">
            <div class="flex items-center justify-end gap-3 py-2">
                <Link
                    v-for="(batch, b) in data"
                    :key="b"
                    :class="
                        levelCategoryLabels[
                            levelCategories.indexOf(batch['level_category'])
                        ]
                    "
                    class="flex w-fit min-w-[6rem] flex-col items-start rounded-md border py-1 pl-2 pr-4"
                >
                    <span class="text-xs">{{ batch["level_category"] }}</span>
                    <span class="text-xs font-semibold"
                        >{{ parseLevel(batch["name"]) }}
                        {{ batch["section"] }}</span
                    >
                </Link>
            </div>
        </template>
    </TableElement>
</template>

<script setup>
import { computed, onMounted, ref, watch } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import { genderLabels, levelCategoryLabels, parseLevel } from "@/utils.js";
import RadioGroup from "@/Components/RadioGroup.vue";
import TextInput from "@/Components/TextInput.vue";
import { debounce } from "lodash";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline/index";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const filteredLevelCategory = ref(null);

const teacher = computed(
    () =>
        usePage().props.teacher?.filter((teacher) => {
            if (!!filteredLevelCategory.value) {
                return teacher.batches
                    .map((batch) => batch["level_category"])
                    .includes(filteredLevelCategory.value);
            }
            return true;
        }) || []
);
const levelCategories = ref([]);
const levelCategoryOptions = computed(() =>
    levelCategories.value.map((levelCategory) => {
        return {
            label: levelCategory,
            value: levelCategory,
        };
    })
);

const searchKey = ref("");

// Debounce is used to send search request 300ms just after the user has stopped typing
const search = debounce(() => {
    router.get(
        "/admin/subjects/" + subject.value.id,
        { search: searchKey.value },
        {
            only: ["teacher"],
            preserveState: true,
            replace: true,
        }
    );
}, 300);

watch([searchKey], () => {
    search();
});

const subject = computed(() => usePage().props.subject);

const config = [
    {
        key: "teacher",
        name: t("subjectTeachers.views"),
        type: "custom",
        align: "left",
        class: "w-full",
    },
    {
        key: "batches",
        name: t("subjectTeachers.grades"),
        type: "custom",
        align: "right",
    },
];

onMounted(() => {
    router.reload({
        only: ["teacher"],
        onSuccess() {
            levelCategories.value = teacher.value.reduce((acc, t) => {
                t.batches.forEach((batch) => {
                    if (!acc.includes(batch["level_category"])) {
                        acc.push(batch["level_category"]);
                    }
                });
                return acc;
            }, []);
        },
    });
});
</script>

<style scoped></style>
